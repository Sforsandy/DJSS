<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\Event;
use App\EventType;
use App\EventFormat;
use App\Game;
use App\EventRule;
use App\EventJoin;
use App\PayTm;
use App\PaymentTransaction;
use App\EventWinner;
use Auth;
use Session;
use Validator;
use Toastr;
use Response;
use DataTables;
use DB;
use Carbon\Carbon;
use Redirect;
use Illuminate\Support\Facades\Input;
class EventController extends Controller {
    

    public function showEvents()
    {
        return view('home');
    }
    public function QRcode()
    {
        \QrCode::size(500)
            ->format('png')
            ->generate('gamerzbyte.com', public_path('uploads/qrcode/qrcode.png'));
        return view('qrcode');
    }
    public function showAllEvents()
    {
        $moderatorUsers = User::whereHas('roles', function($q){$q->where('name', 'moderator');})->get();
        $games = Game::where('status','1')->get();
        return view('events/all_events',compact('moderatorUsers','games'));
    }

    public function getEvents(Request $request)
    {
        $status = $request['status'];
        $pageno = ($request['page'] == '') ? 0 : $request['page'];

        $no_of_records_per_page = 10;
        $offset = ($pageno) * $no_of_records_per_page;

        $created_by = $request['created_by'];
        $game = $request['game'];
        $eventsQry = Event::with('event_types','event_formats','games','creaters');
                if($status == -2)
                {
                    $eventsQry->whereIn('events.status', [0,1]);
                }
                if($status >= 0)
                {
                    $eventsQry->where('events.status', $status);
                }
                if($created_by > 1)
                {
                    $eventsQry->where('events.created_by', $created_by);
                }
                if($game >= 1)
                {
                    $eventsQry->where('events.game', $game);
                }
                $eventsQry->select('*',DB::raw("DATE_FORMAT(events.schedule_date, '%d-%b') as schedule_date"),DB::raw("DATE_FORMAT(events.schedule_time, '%h:%i %p') as schedule_time"));
                $eventsQry->orderBy('events.schedule_datetime', 'DESC');
                $eventsQry->skip($offset)->take($no_of_records_per_page);
        $events  = $eventsQry->get();

        $EventJoinData = EventJoin::groupBy('event_id')->select('*', DB::raw('count(*) as total'))->get();
        $eventData  = array();
        foreach ($events->toArray() as $EventsKey => $EventsValue) {
            
            $eventData[$EventsKey] = $EventsValue;
            $eventData[$EventsKey]['event_joined'] = 0;
            foreach ($EventJoinData->toArray() as $JoinKey => $JoinValue) {
                if($JoinValue['event_id'] == $EventsValue['id'])
                {
                    $eventData[$EventsKey]['event_joined'] = $EventsValue['team_size'] * $JoinValue['total'];
                }
            }
        }
        $page = -1;
        // echo $events->count();
        if($events->count() > 0)
        {
            $page = $pageno+1;
        }
        return Response::json(array('start'=>$offset,'per_page'=>$no_of_records_per_page,'page'=>$page,'data' => $eventData,'success'=>1,'meassage'=>'List successfully.'));
    }
    public function showEventDetails()
    {
        // $event_data = Event::find($id);
        $id = Input::get('id');
        $event_data = Event::where('id',$id)->with('event_types','event_formats','games')->first();
        if(empty($event_data))
        {
            return view('errors/404');
        }

        $event_rules = EventRule::where('event_id',$id)->whereNotNull('game_id')->get();
        // echo "<pre>";
        // print_r($event_rules->toArray());
        $game_rules = EventRule::whereNull('event_id')->where('game_id',$event_data->game)->get();
        // print_r($game_rules->toArray());
        $event_rules_blobal = EventRule::whereNull('event_id')->whereNull('game_id')->get();
        // print_r($event_rules_blobal->toArray());
        $event_winners = EventWinner::where('event_id',$id)->get();
        // die;
        $joinexist = 0;
        if(Auth::check())
        {
            $joinexist = EventJoin::where('event_id',$id)->where('user_id',Auth::user()->id)->count();
        }
        $tolaljoin = EventJoin::where('event_id',$id)->count();
        $EventFlag['joinexist'] = $joinexist;
        $EventFlag['tolaljoin'] = $tolaljoin * $event_data->team_size;
        $EventFlag['is_full'] = ($event_data->team_size * $tolaljoin == $event_data->capacity) ? '1' : '0';
        // echo "<pre>";
        // print_r($EventFlag);
        // print_r($event_data->toArray());die;
        $eventflag = (object)$EventFlag;

        \QrCode::size(500)
            ->format('png')
            ->generate('gamerzbyte.com', public_path('uploads/qrcode/qrcode.png'));
            
        return view('events/event_details',compact('event_data','event_rules','game_rules','event_rules_blobal','eventflag','event_winners'));
    }

    public function JoinEvent(Request $request)
    {
        if(!Auth::check()) 
        {
            session()->forget('req-url');
            session(['req-url' => 'event-details?id='.$request['event_id']]);
            return Response::json(array('success' => 2,'message' => 'Login first'));
        }
        if(Auth::user()->hasRole('admin'))
        {
            return Response::json(array('success' => 0,'message' => 'Oops! Access denied!!'));
        }
        $rules = array(
            'event_id' => 'required|numeric|exists:events,id'
            // ,'user_id' => 'unique:event_joined_users,event_id|exists:event_joined_users,user_id'
            // ,'user_id' =>'exists:event_joined_users,user_id,event_id,'.Auth::user()->id,
        );
        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {
            $event_id = $request['event_id'];
            $joinExist = EventJoin::where('event_id',$event_id)->where('user_id',Auth::user()->id)->count();
            if($joinExist > 0)
            {
                return Response::json(array('success' => 0,'message' => 'Already join this event.'));
            }
            $eventData = Event::find($event_id);
            $chkCapacity = $eventData->capacity;
            $totalJoin = EventJoin::where('event_id',$event_id)->count();
            if($eventData->created_by == Auth::user()->id)
            {
                return Response::json(array('success' => 0,'message' => "Can't join self event."));
            }

            if($eventData->schedule_datetime < date("Y-m-d H:i:s")) 
            {
                return Response::json(array('success' => 0,'message' => "Event is ongoing or past you can't join."));
            }
            if($eventData->status == 1)
            {
                return Response::json(array('success' => 0,'message' => "Event is ongoing you can't join."));
            }
            if($eventData->status == 2)
            {
                return Response::json(array('success' => 0,'message' => "Event is past you can't join."));
            }
            if($chkCapacity <= ($totalJoin * $eventData->team_size))
            {
                return Response::json(array('success' => 0,'message' => 'Event full you can not join now.'));
            }

            $eventjoin = new EventJoin();
            $eventjoin->user_id = Auth::user()->id;
            $eventjoin->event_id = $event_id;
            $eventjoin->game_id = $eventData->game;
            $eventjoin->joined_date = date("Y-m-d H:i:s");
            $eventjoin->save();
                
            $eventtransaction = new  PaymentTransaction();
            $eventtransaction->event_id         =   $event_id;
            $eventtransaction->user_id          =   Auth::user()->id;
            $eventtransaction->txn_id           =   'Free';
            $eventtransaction->txn_date         =   date('Y-m-d H:i:s');
            $eventtransaction->status           =   'TXN_SUCCESS';
            $eventtransaction->resp_msg           =   'TRANSACTION_SUCCESS';
            $eventtransaction->event_type       =   0;
            $eventtransaction->save();
            $EventJoinPoint = \CommonHelper::updateUserLeaderboardPoint(Auth::user()->id,'join_event',$event_id,$eventData->game);
            return Response::json(array('success'=>1,'message'=>'Event join successfully.'));
        }
    }

    public function JoinPaidEvent(Request $request)
    {
        if(!Auth::check()) 
        {
            session()->forget('req-url');
            session(['req-url' => 'event-details?id='.$request['event_id']]);
            // return Response::json(array('success' => 2,'message' => 'Login first'));
            Toastr::error("Login first.", $title = null, $options = []);
            return redirect()->route('login');
        }
        if(Auth::user()->hasRole('admin'))
        {
            Toastr::error("Oops! Access denied!!", $title = null, $options = []);
            return Redirect::back();
        }
        $rules = array(
            'event_id' => 'required|numeric|exists:events,id'
            // ,'user_id' => 'unique:event_joined_users,event_id|exists:event_joined_users,user_id'
            // ,'user_id' =>'exists:event_joined_users,user_id,event_id,'.Auth::user()->id,
        );
        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {
            $event_id = $request['event_id'];
            $joinExist = EventJoin::where('event_id',$event_id)->where('user_id',Auth::user()->id)->count();
            if($joinExist > 0)
            {
                Toastr::error("Already join this event.", $title = null, $options = []);
                return redirect()->route('event-details', ['id' => $event_id]);
            }
            $eventData = Event::find($event_id);
            // echo "<pre>";
            // echo $event_id;
            // echo $eventData->capacity;
            // print_r($eventData->toArray());
            // die;
            $chkCapacity = $eventData->capacity;
            $totalJoin = EventJoin::where('event_id',$event_id)->count();
            if($eventData->created_by == Auth::user()->id)
            {
                Toastr::error("Can't join self event.", $title = null, $options = []);
                return redirect()->route('event-details', ['id' => $event_id]);
            }
            if($eventData->schedule_datetime < date("Y-m-d H:i:s")) 
            {
                Toastr::error("Event is ongoing or past you can't join.", $title = null, $options = []);
                return redirect()->route('event-details', ['id' => $event_id]);
            }
            if($eventData->status == 1)
            {
                Toastr::error("Event is ongoing you can't join.", $title = null, $options = []);
                return redirect()->route('event-details', ['id' => $event_id]);
            }
            if($eventData->status == 2)
            {
                Toastr::error("Event is past you can't join.", $title = null, $options = []);
                return redirect()->route('event-details', ['id' => $event_id]);
            }
            if($chkCapacity <= ($totalJoin * $eventData->team_size))
            {
                Toastr::error("Event full you can not join now.", $title = null, $options = []);
                return redirect()->route('event-details', ['id' => $event_id]);
            }


            if($eventData->event_type ==  2 || $eventData->event_type ==  4 )
            {

                $successTxn = PaymentTransaction::where('event_id',$event_id)
                ->where('user_id',Auth::user()->id)
                ->where('status','TXN_SUCCESS')
                // ->whereNotIn('status',['TXN_SUCCESS'])
                // ->orwhereNull('status')
                ->OrderBy('id', 'desc')
                ->first();

                if($successTxn)
                {
                    Toastr::success('Payment made already.', $title = null, $options = []);
                    return redirect()->route('event-details', ['id' => $event_id]);
                }
                $pendingTxn = PaymentTransaction::where('event_id',$event_id)
                                ->where('user_id',Auth::user()->id)
                                ->whereNull('status')
                                ->OrderBy('id', 'desc')
                                ->first();
                
                
                // echo "<pre>";
                // print_r($pendingTxn);
                // die;
            
                if(empty($pendingTxn))
                {
                    
                    $order_id = 'ORDS'.Auth::user()->id.$event_id.time();
                    $eventtransaction = new PaymentTransaction();
                    $eventtransaction->user_id          = Auth::user()->id;
                    $eventtransaction->event_id         =   $event_id;
                    $eventtransaction->order_id         =   $order_id;
                    $eventtransaction->save();
                    $transaction_id = $eventtransaction->id;

                }
                else
                {
                    $order_id = 'ORDS'.Auth::user()->id.$event_id.time();
                    $transaction_id = $pendingTxn->id;
                    $transactionUpdateOrderId = PaymentTransaction::find($transaction_id);
                    $transactionUpdateOrderId->order_id = $order_id;
                    $transactionUpdateOrderId->save();
                }

                // CREATE NULL TRANSACTION END
                $checkSum = "";
                $paramList = array();
                $paramList["MID"] = config('app.PAYTM_MERCHANT_MID');
                
                // $paramList["ORDER_ID"] = $order_id;
                $paramList["ORDER_ID"] = $order_id;
                $paramList["CUST_ID"] = Auth::user()->id;
                $paramList["INDUSTRY_TYPE_ID"] = 'Retail';
                $paramList["CHANNEL_ID"] = 'WEB';
                $paramList["TXN_AMOUNT"] = $eventData->fee;
                $paramList["WEBSITE"] = config('app.PAYTM_MERCHANT_WEBSITE');

                // $paramList["CALLBACK_URL"] = "http://nextgenbuddy.com/PayTmDemo/pgResponse.php";
                $paramList["CALLBACK_URL"] = config('app.url')."payresponse/".$transaction_id;
                // $paramList["CALLBACK_URL"] = "http://nextgenbuddy.com/PayTmDemo/pgResponse.php";
                $paramList["MSISDN"] = Auth::user()->mobile_no; //Mobile number of customer
                $paramList["EMAIL"] = Auth::user()->email; //Email ID of customer
                $paramList["VERIFIED_BY"] = "EMAIL"; //
                $paramList["IS_USER_VERIFIED"] = "YES"; //
                //Here checksum string will return by getChecksumFromArray() function.
                $PayTm = new PayTm();
                $paramList["checkSum"] = $PayTm->getChecksumFromArray($paramList,config('app.PAYTM_MERCHANT_KEY'));
                $paramList = (object)$paramList;
                // echo "<pre>";
                // print_r($paramList);

              return view('paytm/redirect',compact('paramList'));
            }
            else
            {
                $eventjoin = new EventJoin();
                $eventjoin->user_id = Auth::user()->id;
                $eventjoin->event_id = $event_id;
                $eventjoin->game_id = $eventData->game;
                $eventjoin->joined_date = date("Y-m-d H:i:s");
                $eventjoin->save();
            }
            
            
            // return Response::json(array('success'=>1,'message'=>'Event join successfully.'));
            Toastr::error('Something went wrong.', $title = null, $options = []);
            return redirect()->route('event-details', ['id' => $event_id]);
        }
    }

    public function Cron1mChangeEventStatus()
    {
        DB::table('events')
        ->whereDate('schedule_date', '>=', Carbon::now()->toDateString())
        ->whereDate('schedule_time', '>=', Carbon::now()->toTimeString())
        ->where('status', 0)
        ->update(['status' => 1]);
    }
}