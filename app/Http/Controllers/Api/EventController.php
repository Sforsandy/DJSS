<?php


namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Event;
use App\EventJoin;
use App\EventRule;
use App\PaymentTransaction;
use App\LeaderboardLavel;
use App\UserLeaderboardPoint;
use App\EventJoinActivity;
use App\EventWinner;
use Illuminate\Support\Facades\Auth;
use Validator;
use Response;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Input;

class EventController extends Controller
{


    public $successStatus = 200;


    public function getEvents(Request $request)
    {
        \LogActivity::addToLog('Get events','getevents',request('device_type'));
        $rules = array (
            'user_id' => 'required',
            'game_id' => 'required'
        );
        $validator = Validator::make ( Input::all (), $rules );

        if ($validator->fails ()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        }
        $pageno = (request('page') == '') ? 0 : request('page');
		$no_of_records_per_page = 10;
        $offset = ($pageno-1) * $no_of_records_per_page;

        $user_id  = request('user_id');
        $status  = request('status');
        $game_id  = request('game_id');
        $eventsQry  = Event::where('game',$game_id);
        if(!empty($status))
        {
            if($status == 1)
            {
                $eventsQry->whereNotIn('events.status', [2]);
            }if($status == 2)
            {
                $eventsQry->whereIn('events.status', [2]);
            }
        }else{
            $eventsQry->whereNotIn('events.status', [2]);
        }
    	$eventsQry->orderBy('events.schedule_datetime', 'DESC');
        if(request('page') > 0)
        {
            $eventsQry->skip($offset)->take($no_of_records_per_page);
        }
    	$events  = $eventsQry->get();
       	$EventJoinData = EventJoin::groupBy('event_id')->select('*', DB::raw('count(*) as total'))->get();
        $eventData  = array();
        foreach ($events->toArray() as $EventsKey => $EventsValue) {
            $eventData[$EventsKey] = $EventsValue;
            $eventData[$EventsKey]['player_joined'] = 0;
            $eventData[$EventsKey]['is_joined'] = EventJoin::where('event_id',$EventsValue['id'])->where('user_id',$user_id)->count();
            $eventData[$EventsKey]['event_rules'] = EventRule::where('event_id',$EventsValue['id'])->orWhere('game_id',$game_id)->get();
            // $eventData[$EventsKey]['event_rules']['game'] = EventRule::whereNull('event_id')->where('game_id',$game_id)->get();
            // $eventData[$EventsKey]['event_rules']['game'] = [];
            // $eventData[$EventsKey]['event_rules']['global'] = EventRule::whereNull('event_id')->get();
            // $eventData[$EventsKey]['event_rules']['game'] = EventRule::whereNull('event_id')->get();
            foreach ($EventJoinData->toArray() as $JoinKey => $JoinValue) {
                if($JoinValue['event_id'] == $EventsValue['id'])
                {
                    $eventData[$EventsKey]['player_joined'] = $EventsValue['team_size'] * $JoinValue['total'];
                }
            }
        }
        return response()->json(['success'=>'1','message'=>'Events listed successfully.','data'=>$eventData,'total_record'=>$events->count(),'current_page'=>(int) $pageno], $this->successStatus);
    }

    public function getEventAmtCalculation(Request $request)
    {
        \LogActivity::addToLog('Get event amount calculation','get_event_calculation',request('device_type'));
        $rules = array (
            'user_id' => 'required',
            'event_id' => 'required'
        );
        $validator = Validator::make ( Input::all (), $rules );

        if ($validator->fails ()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        }
        $eventData = Event::find(request('event_id'));
        $userData  = User::find(request('user_id'));

        $resData['deposited_balance'] = $userData->deposited_balance;
        $resData['bonus_balance'] = $userData->bonus_balance;
        $resData['winnings_balance'] = $userData->winnings_balance;
        $resData['event_fee'] = $eventData->fee;
        $resData['wallet_type'] = $eventData->wallet_type;
        if((int)$eventData->fee > 0)
        {
            if($eventData->wallet_type == 2) // Used bonus wallet only
            {
                $used_from_bonus = $eventData->fee;
                $resData['used_from_bonus'] = $used_from_bonus;
                $resData['used_from_deposited'] = "0";
                $resData['to_pay'] = "0";
                $resData['pay_via_payment_gateway'] = "0";
            }
            if($eventData->wallet_type == 1 || $eventData->wallet_type == 3) // Used deposited wallet only OR Used deposited and bonus wallet both
            {
                $used_from_bonus = round(($eventData->fee * $eventData->bonus_wallet_per)/100);
                if($used_from_bonus > $eventData->bonus_max_amt)
                {
                    $used_from_bonus = $eventData->bonus_max_amt;
                }
                if($userData->bonus_balance  < $used_from_bonus)
                {
                    $used_from_bonus = $userData->bonus_balance;
                }
                if($eventData->wallet_type == 1) // Used deposited wallet only
                {
                    $used_from_bonus = "0";
                }
                $resData['is_able_to_join_event'] = "1";
                $resData['used_from_bonus'] = $used_from_bonus;
                $payable_amount =  $eventData->fee - $used_from_bonus;
                $used_from_deposited =  $eventData->fee - $payable_amount;
                $resData['used_from_deposited'] = (string) $payable_amount;
                if($payable_amount > $userData->deposited_balance)
                {
                    $resData['used_from_deposited'] = (string) $userData->deposited_balance;
                }

                $resData['to_pay'] = (string) $payable_amount;
                $pay_via_payment_gateway = $payable_amount - $userData->deposited_balance;
                $resData['pay_via_payment_gateway'] = (float) ($payable_amount > $userData->deposited_balance) ? $pay_via_payment_gateway : "0";
            }
        }else
        {
            $resData['used_from_bonus'] = "0";
            $resData['used_from_deposited'] = "0";
            $resData['to_pay'] = "0";
            $resData['pay_via_payment_gateway'] = "0";
        }
        
        
        return response()->json(['success'=>'1','message'=>'Events calculation successfully.','data'=>$resData,'eventdata'=>$eventData], $this->successStatus);
    }

    public function JoinEvent(Request $request)
    {
        \LogActivity::addToLog('Join events','join_event',request('device_type'));
        $rules = array(
            'user_id'=> 'required|exists:users,id'
            ,'event_id' => 'required|numeric|exists:events,id'
            ,'used_from_bonus' => 'required'
            ,'used_from_deposited' => 'required'
            ,'pay_via_payment_gateway' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        else {
            // echo "<pre>";
            // $EventJoinBonus = \CommonHelper::addEventJoinBonus(request('user_id'),request('event_id'));
            
            // print_r($EventJoinBonus);
            // $NotificationData['notification_title'] = 'New event joined.';
            // $NotificationData['notification_desc']  = 'You have joined event.';
            // $NotificationData['notification_type']  = 'event_joined';
            // $NotificationData['event_id'] = 1;
            // $NotificationData['user_id']  = 3;
            // $NotificationData['is_redirect'] = '1';
            // \CommonHelper::addUserNotification($NotificationData);
            // die;
            $event_id = request('event_id');
            $used_from_bonus = request('used_from_bonus');
            $used_from_deposited = request('used_from_deposited');
            $pay_via_payment_gateway = request('pay_via_payment_gateway');
            $userData = User::find(request('user_id'));
            $joinExist = EventJoin::where('event_id',$event_id)->where('user_id',$userData->id)->count();
            if($joinExist > 0)
            {
                return Response::json(array('success' => 0,'message' => 'Already join this event.'));
            }
            if($used_from_bonus > $userData->bonus_balance )
            {
                 return Response::json(array('success' => 0,'message' => 'Bonus balance not enough to join event.'));
            }
            $eventData = Event::find($event_id);
            $chkCapacity = $eventData->capacity;
            $totalJoin = EventJoin::where('event_id',$event_id)->count();
            if($eventData->created_by == $userData->id)
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
            $getActivityData = \CommonHelper::addEventJoinActivity($userData->id, $event_id, $used_from_bonus ,$used_from_deposited ,$pay_via_payment_gateway);
            if($pay_via_payment_gateway > 0 && $eventData->fee > 0) // CHK PAID OR FREE EVENT
            {
                $pendingTxn = PaymentTransaction::where('user_id',request('user_id'))
                ->whereNull('status')
                ->OrderBy('id', 'desc')
                ->first();

                if(empty($pendingTxn))
                {
                    $eventtransaction = new PaymentTransaction();
                    $eventtransaction->user_id          = request('user_id');
                    $eventtransaction->order_id         =   \CommonHelper::generateORDNumber('JGE',request('user_id'));
                    $eventtransaction->event_id         =   $event_id;
                    $eventtransaction->game_id         =   $eventData->game;
                    $eventtransaction->txn_amount       =  request('pay_via_payment_gateway');
                    $eventtransaction->save();
                    $transaction_id = $eventtransaction->id;

                }
                else
                {
                    $transaction_id = $pendingTxn->id;
                    $transactionUpdateOrderId = PaymentTransaction::find($transaction_id);
                    $transactionUpdateOrderId->user_id          = request('user_id');
                    $transactionUpdateOrderId->order_id = \CommonHelper::generateORDNumber('JGE',request('user_id'));;
                    $transactionUpdateOrderId->event_id = $event_id;
                    $transactionUpdateOrderId->game_id  = $eventData->game;
                    $transactionUpdateOrderId->txn_amount       =  request('pay_via_payment_gateway');
                    $transactionUpdateOrderId->save();
                }
                
                return response()->json(['success'=>'1','message'=>'Redirect url to complete payment.','data' => url('/').'/app/payment/paytm/'.$transaction_id], $this->successStatus);
            }
            else
            {
                if($used_from_bonus > 0)
                {
                    $bonusWallet = \CommonHelper::updateBonusWallet(request('user_id'), $used_from_bonus, '1','event_joined' ,$event_id,$eventData->game);
                }
                if($used_from_deposited > 0)
                {
                    $DepositedWallet = \CommonHelper::updateDepositedWallet(request('user_id'), $used_from_deposited,'1', $event_id,$eventData->game);
                }
                $total_amt = $used_from_bonus + $used_from_deposited + $pay_via_payment_gateway;
               \CommonHelper::addUserTransactions($userData->id, $total_amt, '1' ,'joined_event' ,'success', 'JGE',$event_id,$eventData->game);

               $eventjoin = new EventJoin();
               $eventjoin->user_id = $userData->id;
               $eventjoin->event_id = $event_id;
               $eventjoin->game_id = $eventData->game;
               $eventjoin->joined_date = date("Y-m-d H:i:s");
               $eventjoin->save(); 
               $EventJoinPoint = \CommonHelper::updateUserLeaderboardPoint($userData->id,'join_event',$event_id,$eventData->game);
               if($eventData->fee >= '5')
               {
                $EventJoinBonus = \CommonHelper::addEventJoinBonus($userData->id,$event_id);
               }
               $NotificationData['notification_title'] = 'New event joined.';
               $NotificationData['notification_desc']  = 'You have joined event.';
               $NotificationData['notification_type']  = 'event_joined';
               $NotificationData['event_id'] = $event_id;
               $NotificationData['user_id']  = $userData->id;
               $NotificationData['is_redirect'] = '1';
               \CommonHelper::addUserNotification($NotificationData);
            }
            return Response::json(array('success'=>1,'message'=>'Event join successfully.'));
        }
    }

    public function NotificationTest(Request $request)
    {
        \LogActivity::addToLog('Join events','notification_test',request('device_type'));
        $rules = array(
            'user_id'=> 'required'
            ,'event_id' => 'required'
            ,'notification_title' => 'required'
            ,'notification_desc' => 'required'
            ,'notification_type' => 'required'
            ,'is_redirect' => 'required'
            ,'fcm_token' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        else {
            
            $NotificationData['notification_title'] = request('notification_title');
            $NotificationData['notification_desc']  = request('notification_desc');
            $NotificationData['notification_type']  = request('notification_type');
            $NotificationData['token']  = request('token');
            $NotificationData['event_id'] = request('event_id');
            $NotificationData['user_id']  = request('user_id');
            $NotificationData['is_redirect'] = request('is_redirect');
            $data = \CommonHelper::addUserNotificationTest($NotificationData);
            return Response::json(array('success'=>1,'message'=>'Success.','data'=>$data));
        }
    }

    public function getEventId(Request $request)
    {
        \LogActivity::addToLog('Get event id','get_event_id',request('device_type'));
        $rules = array (
            'user_id' => 'required',
            'event_invite_code' => 'required|exists:events,invite_code'
        );
        $validator = Validator::make ( Input::all (), $rules );

        if ($validator->fails ()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        }
        $eventData = Event::where('invite_code',request('event_invite_code'))->first();
        return response()->json(['success'=>'1','message'=>'Events find successfully.','data'=>$eventData->id], $this->successStatus);
    }

    public function getEventDetails(Request $request)
    {
        \LogActivity::addToLog('Get event details','get_event_details',request('device_type'));
        $rules = array (
            'user_id' => 'required',
            'event_id' => 'required'
        );
        $validator = Validator::make ( Input::all (), $rules );

        if ($validator->fails ()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        }

        $eventData  = Event::find(request('event_id'));
        $eventData->player_joined = 0;
        $eventData->is_joined = EventJoin::where('event_id',$eventData->id)->where('user_id',request('user_id'))->count();
        $EventJoinData = EventJoin::where('event_id',$eventData->id)
        ->groupBy('event_id')
        ->select('*', DB::raw('count(*) as total'))->first();
        $eventData->player_joined = $eventData->team_size * $EventJoinData->total;

        // $eventRules['event'] = EventRule::where('event_id',request('event_id'))->where('game_id',$eventData->game)->get();
        // $eventRules['common'] = EventRule::whereNull('event_id')->get();
        $eventRules = EventRule::where('event_id',request('event_id'))->orWhere('game_id',$eventData->game)->get();
        $eventJoinData  = EventJoin::where('user_id',request('user_id'))->where('event_id',request('event_id'))->first();
        
        return response()->json(['success'=>'1','message'=>'Events details showed successfully.','data'=>$eventData,'eventjoindata'=>$eventJoinData,'eventrules'=>$eventRules], $this->successStatus);
    }

    public function uploadUserGameScreenShot(Request $request)
    {
        // \LogActivity::addToLog('User_game_screenshot','upload_user_game_screenshot',request('device_type'));
        $rules = array (
            'user_id' => 'required',
            'event_id' => 'required',
            'event_joined_id' => 'required|numeric|exists:event_joined_users,id',
            'game_screenshot' => 'required|image|mimes:jpeg,png,jpg|max:1024'
        );
        $validator = Validator::make ( Input::all (), $rules );

        if ($validator->fails ()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        }
        $gameScreenshotName = '';
        if($request->hasFile('game_screenshot')) {
            $fileGET = $request->file('game_screenshot');
            $gameScreenshotName = time().rand(1000,9999).'.'.$fileGET->getClientOriginalExtension();
            $request->file('game_screenshot')->move(public_path("/uploads/game_screenshot"), $gameScreenshotName);
        }
        $eventJoin = EventJoin::find(request('event_joined_id'));
        $eventJoin->game_screenshot = $gameScreenshotName;
        $eventJoin->save();
        
        
        return response()->json(['success'=>'1','message'=>'Game screenshot upload successfully.','data'=>$eventJoin], $this->successStatus);
    }

    public function getEventWinnerUsers(Request $request)
    {
        \LogActivity::addToLog('Get event winnerusers','get_event_winnerusers',request('device_type'));
        $rules = array (
            'user_id' => 'required',
            'event_id' => 'required'
        );
        $validator = Validator::make ( Input::all (), $rules );

        if ($validator->fails ()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        }

        $eventWinnerData  = EventWinner::with('users','winnerpositions')
        ->where('event_id',request('event_id'))
        ->get();
        // print_r($eventWinnerData);
        $winnerData = array();
        foreach ($eventWinnerData as $key => $value) {
            $joinData = EventJoin::where('event_id',request('event_id'))
            ->where('user_id',$value->user_id)
            ->first();
           $winnerData[$key]['id'] = $value->id;
           $winnerData[$key]['user_id'] = $value->user_id;
           $winnerData[$key]['event_id'] = $value->event_id;
           $winnerData[$key]['winner_position'] = $value->winnerpositions->position;
           $winnerData[$key]['upload_screenshot'] = @$joinData->game_screenshot;
           $winnerData[$key]['firstname'] = $value->users->firstname;
           $winnerData[$key]['lastname'] = $value->users->lastname;
           $winnerData[$key]['amount'] = $value->amount;
        }
       
        return response()->json(['success'=>'1','message'=>'Events winner showed successfully.','data'=>$winnerData], $this->successStatus);
    }
    public function getLavel($lavels = array(),$user_point)
    {
        $lavel = '1';
        foreach ($lavels as $key => $value) {if(($user_point >= $value->start_point) && ($user_point <= $value->end_point))
            {
                $lavel = $value->lavel;
            }
        }
        return $lavel;
    }

    public function getEventPlayers(Request $request)
    {
        \LogActivity::addToLog('Get event players','get_event_players',request('device_type'));
        $rules = array(
            'user_id'=> 'required|exists:users,id',
            'event_id' => 'required|numeric|exists:events,id'
        );


        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails ()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        }
        $LeaderboardLavels = LeaderboardLavel::all();
        $JoinedUser = EventJoin::where('event_id',request('event_id'))->pluck('user_id')->toArray();
        if(empty($JoinedUser))
        {
            return response()->json(['success'=>'1','message'=>'Not joined yet any user.'], $this->successStatus);
        }
        $UserLeaderboardPointQry = UserLeaderboardPoint::with('user');
        $UserLeaderboardPointQry->selectRaw('*,sum(point) as total_point');
        $UserLeaderboardPointQry->whereIn('user_id',$JoinedUser);
        $UserLeaderboardPointQry->where('event_id',request('event_id'));
        $UserLeaderboardPointQry->orderBy('total_point', 'DESC');
        $UserLeaderboardPointQry->groupBy('user_id');
        $UserLeaderboardPoint = $UserLeaderboardPointQry->get();
        $i = 1;
        $UserLeaderboard = array();
        foreach ($UserLeaderboardPoint as $key => $value) {

            $UserLeaderboard[$key]['firstname'] = $value->user->firstname;
            $UserLeaderboard[$key]['lastname'] = $value->user->lastname;
            $UserLeaderboard[$key]['mobile_no'] = $value->user->mobile_no;
            $UserLeaderboard[$key]['total_point'] = $value->total_point;
            $UserLeaderboard[$key]['lavel'] = $this->getLavel($LeaderboardLavels,$value['total_point']);
            $total_point1 = @$UserLeaderboardPoint[intval($key)-1]['total_point'];
            $total_point2 = $UserLeaderboardPoint[$key]['total_point'];
            if($total_point1 == $total_point2)
            {

                $UserLeaderboard[$key]['ranking'] = $i-1;
            }
            else
            {
                $UserLeaderboard[$key]['ranking'] = $i++;
            }
            
        }
        return response()->json(['success'=>'1','message'=>'Event players listed successfully.','data'=>$UserLeaderboard], $this->successStatus);
    }

}