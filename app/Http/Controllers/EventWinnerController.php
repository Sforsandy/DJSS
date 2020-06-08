<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\EventWinner;
use App\Event;
use App\EventJoin;
use App\WinnerPosition;
use Auth;
use Session;
use Validator;
use Toastr;
use Response;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
class EventWinnerController extends Controller {
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
    	return view('event_winner/index');
    }
    public function create()
    {
        $users = User::where('id','>','1')->get();
        // $events = Event::where('status','1')->get();
        if(Auth::user()->hasRole('moderator'))
        {
            $events = Event::where('created_by',Auth::user()->id)->get();
        }
        else
        {
            // $events = Event::where('status','1')->get();
            $events = Event::all();
        }
        $winnerpositions = WinnerPosition::all();
        return view('event_winner/create',compact('users','events','winnerpositions'));
    }
    public function winnerRequestShow()
    {
        $winnerpositions = WinnerPosition::all();return view('event_winner/winner-request',compact('winnerpositions'));
    }

    public function data()
    {
        $event_ids = array();
        if(Auth::user()->hasRole('moderator'))
        {
            $event_ids = Event::where('created_by',Auth::user()->id)->get()->pluck('id');
            $EventWinners = EventWinner::whereIn('event_id',$event_ids)->with('users','events','winnerpositions');
        }
        else
        {
            $EventWinners = EventWinner::with('users','events','winnerpositions')->get();
        }

        return DataTables::of($EventWinners)
        ->addColumn('event_name', function ($getEventWinners) {
            $data  =  $getEventWinners->events;
            if(isset($data))
            {
                $eventLink = "<a href='".route('event-details', ["id"=>$getEventWinners->event_id])."' class='purple'>".$getEventWinners->events->event_name."</a>";
                return $eventLink;
            }
            else{return 'All';}
        })
        ->addColumn('user_name', function ($getEventWinners) {
            $data  =  $getEventWinners->users;
            if(isset($data))
            {
                return $getEventWinners->users->firstname.' '.@$getEventWinners->users->lastname;
            }
            else{return '';}
        })
        ->addColumn('winner_position', function ($getEventWinners) {
            $data  =  $getEventWinners->winnerpositions;
            if(isset($data))
            {
                return $getEventWinners->winnerpositions->position;
            }
            else{return '';}
        })
        ->addColumn('upload_screenshot', function ($getEventWinners) {
            $data  =  $getEventWinners->upload_screenshot;
            if(isset($data))
            {
                $imgLink = "<a target='_blank' href='".url("public/uploads/winners").'/'.$getEventWinners->upload_screenshot."' class='purple'>".$getEventWinners->upload_screenshot."</a>";
                return $imgLink;
            }
            else{return '-';}
        })
        ->addColumn('action', function ($getEventWinners) {

            $editBtn = "<a href='".route('event-winner.edit', $getEventWinners->id)."' class='purple'> <i class='fa fa-pencil'></i> </a>";
            $deleteBtn = '';
            
            $deleteBtn = "<a href='javascript:;' id='deleteUser' data-id='".$getEventWinners->id."'onclick='if (confirm(\"Are you sure you want to delete ?\")) deleteRow(".$getEventWinners->id.") ' class='blue'> <i class='fa fa-trash'></i> </a>";
            return $editBtn.$deleteBtn;
        })
        ->rawColumns(['event_name','user_name', 'action','upload_screenshot','winner_position'])

        ->make(true);
    }

    public function getWinnerRequests()
    {
        $event_ids = array();
        if(Auth::user()->hasRole('moderator'))
        {
            $event_ids = Event::where('created_by',Auth::user()->id)->get()->pluck('id');
            $EventJoinedUsers = EventJoin::whereIn('event_id',$event_ids)->with('users','events');
        }
        else
        {
            $EventJoinedUsers = EventJoin::with('users','events')->get();
        }
        $winnerpositions = WinnerPosition::all();
        foreach ($EventJoinedUsers as $key => $value) {
            $getWinnerData = EventWinner::with('winnerpositions')->where('user_id',$value->user_id)->where('event_id',$value->event_id)->first();
            $EventJoinedUsers[$key]->winner_position = @$getWinnerData->winnerpositions->position;
        }
        // echo "<pre>";
        // print_r($EventJoinedUsers->toArray());die;
        return DataTables::of($EventJoinedUsers)
        ->addColumn('event_name', function ($getEventJoinedUsers) {
            $data  =  $getEventJoinedUsers->events;
            if(isset($data))
            {
                $eventLink = "<a href='".route('event-details', ["id"=>$getEventJoinedUsers->event_id])."' class='purple'>".$getEventJoinedUsers->events->event_name."</a>";
                return $eventLink;
            }
            else{return 'All';}
        })
        ->addColumn('mobile_no', function ($getEventJoinedUsers) {
            $data  =  $getEventJoinedUsers->users;
            if(isset($data))
            {
                return $getEventJoinedUsers->users->mobile_no;
            }else{return '';}
        })
        ->addColumn('user_name', function ($getEventJoinedUsers) {
            $data  =  $getEventJoinedUsers->users;
            if(isset($data))
            {
                return $getEventJoinedUsers->users->firstname.' '.@$getEventJoinedUsers->users->lastname;
            }
            else{return '';}
        })
        // ->addColumn('winner_position', function ($getEventJoinedUsers) {
        //     $data  =  $getEventJoinedUsers->position;
        //     if(isset($data))
        //     {
        //         return $getEventJoinedUsers->position;
        //     }
        //     else{return '';}
        // })
        ->addColumn('game_screenshot', function ($getEventJoinedUsers) {
            $data  =  $getEventJoinedUsers->game_screenshot;
            if(isset($data))
            {
                $imgLink = "<a target='_blank' href='".url("public/uploads/game_screenshot").'/'.$getEventJoinedUsers->game_screenshot."' class='purple'>".$getEventJoinedUsers->game_screenshot."</a>";
                return $imgLink;
            }
            else{return '-';}
        })
        ->addColumn('action', function ($getEventJoinedUsers) {
            $acceptBtn = '';
            $rejectBtn = '';
            if(empty($getEventJoinedUsers->winner_position))
            {
                $acceptBtn = "<a href='javascript:;'  onclick='winnerStatusChange(".$getEventJoinedUsers->user_id.",".$getEventJoinedUsers->event_id.",1)' class='green'> <i class='fa fa-check'></i> </a>";

                $rejectBtn = "<a href='javascript:;'   onclick='if (confirm(\"Are you sure you want to reject ?\")) winnerStatusChange(".$getEventJoinedUsers->user_id.",".$getEventJoinedUsers->event_id.",2) ' class='red'> <i class='fa fa-close'></i> </a>";
            }
            
            return $acceptBtn.$rejectBtn;
        })
        ->rawColumns(['event_name','user_name','mobile_no', 'action','game_screenshot'])

        ->make(true);
    }

    public function winnerRequestChangeStatus(Request $request)
    {
        $rules = array(
            'event_id' => 'required'
            ,'user_id' => 'required'
            ,'amount' => 'required'
            ,'winner_position' => 'required'
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {

            $event_id = $request['event_id'];
            $user_id = $request['user_id'];
            $amount = $request['amount'];
            $winner_position = $request['winner_position'];

            $eventData = Event::find($event_id);
            // $getWinnerData = EventWinner::where('user_id',request('user_id'))->where('event_id',request('event_id'))->first();

            $eventwinner = new EventWinner();
            $eventwinner->event_id = $event_id;
            $eventwinner->game_id = $eventData->game;
            $eventwinner->user_id = $user_id;
            $eventwinner->amount = $amount;
            $eventwinner->winner_position = $winner_position;
            $eventwinner->save();

            switch ($winner_position) {
                case '1':
                $position = 'winner';
                break;
                case '2':
                $position = 'runnerup';
                break;
                case '3':
                $position = 'second_runnerup';
                break;
                default:
                $position = '';
            }

            $eventData = Event::find($event_id);
            $updateWonWallet = \CommonHelper::updateWonWallet($user_id, $amount, '0', $event_id, $eventData->game);
            \CommonHelper::addUserTransactions($user_id, $amount, '0' ,'won_event' ,'success', 'WON',$event_id,$eventData->game);
            $WinnerPoint = \CommonHelper::updateUserLeaderboardPoint($user_id,$position,$event_id,$eventData->game);

            $NotificationData['notification_title'] = 'Won event';
            $NotificationData['notification_desc']  = 'You won event.';
            $NotificationData['notification_type']  = 'event_won';
            $NotificationData['event_id'] = $event_id;
            $NotificationData['user_id']  = $user_id;
            $NotificationData['is_redirect'] = '1';
            \CommonHelper::addUserNotification($NotificationData);
            return Response::json(array('success' => 1,'message'=>'Event winner added successfully.','data' => $eventwinner));
        }
    }

    public function store(Request $request)
    {
        $rules = array(
            'event_id' => 'required'
            ,'user_id' => 'required'
            ,'game_id' => 'required'
            ,'winner_position' => 'required'
            // ,'payment_date' => 'required'
            // ,'amount' => 'required'
            // ,'upload_screenshot' => 'sometimes|image|mimes:jpeg,png,jpg|max:1024',
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {

            $event_id = $request['event_id'];
            $user_id = $request['user_id'];
            $game_id = $request['game_id'];
            $winner_position = $request['winner_position'];
            // $payment_date = $request['payment_date'];
            // $amount = $request['amount'];
            $fileName = '';
            if($request->hasFile('upload_screenshot')) {
                $fileGET = $request->file('upload_screenshot');
                $fileName = time().$event_id.$user_id.'.'.$fileGET->getClientOriginalExtension();
                $request->file('upload_screenshot')->move(public_path("/uploads/winners"), $fileName);
            }
            $eventwinner = new EventWinner();
            $eventwinner->event_id = $event_id;
            $eventwinner->game_id = $game_id;
            $eventwinner->user_id = $user_id;
            $eventwinner->winner_position = $winner_position;
            // $eventwinner->amount = $amount;
            // $eventwinner->upload_screenshot = $fileName;
            // $eventwinner->payment_date = Carbon::parse($payment_date)->format('Y-m-d');
            $eventwinner->save();

            switch ($winner_position) {
                case '1':
                $position = 'winner';
                break;
                case '2':
                $position = 'runnerup';
                break;
                case '3':
                $position = 'second_runnerup';
                break;
                default:
                $position = '';
            }
            // $updateWonWallet = \CommonHelper::updateWonWallet($user_id, $amount, '0', $event_id, $game_id);
            // \CommonHelper::addUserTransactions($user_id, $amount, '0' ,'won_event' ,'success', 'WON',$event_id,$game_id);
            // $WinnerPoint = \CommonHelper::updateUserLeaderboardPoint($user_id,$position,$event_id,$game_id);
            return Response::json(array('success' => 1,'message'=>'Event winner added successfully.','data' => $eventwinner));
        }
    }

    public function edit($id) 
    {
        $data = EventWinner::find($id);
        // $events = Event::where('status','1')->get();

        if(Auth::user()->hasRole('moderator'))
        {
            $events = Event::where('created_by',Auth::user()->id)->get();
        }
        else
        {
            $events = Event::all();
        }
        if(Auth::user()->hasRole('moderator') && @Event::find($data->event_id)->created_by != Auth::user()->id)
        {
            return view('errors/403');
        }
        $winnerpositions = WinnerPosition::all();
        return view('event_winner/edit', compact('data','events','winnerpositions'));
    }

    public function update(Request $request)
    {
        $rules = array(
            'event_id' => 'required'
            ,'game_id' => 'required'
            ,'user_id' => 'required'
            ,'winner_position' => 'required'
            // ,'payment_date' => 'required'
            // ,'upload_screenshot' => 'sometimes|image|mimes:jpeg,png,jpg|max:1024',
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {

            $event_id = $request['event_id'];
            $game_id = $request['game_id'];
            $user_id = $request['user_id'];
            $winner_position = $request['winner_position'];
            $payment_date = $request['payment_date'];
            if($request->hasFile('upload_screenshot')) {
                $fileGET = $request->file('upload_screenshot');
                $fileName = time().$event_id.$user_id.'.'.$fileGET->getClientOriginalExtension();
                $request->file('upload_screenshot')->move(public_path("/uploads/winners"), $fileName);
            }
            $id = $request['id'];

            $eventwinner = EventWinner::find($id);
            $eventwinner->event_id = $event_id;
            $eventwinner->game_id = $game_id;
            $eventwinner->user_id = $user_id;
            $eventwinner->winner_position = $winner_position;
            // if($request->hasFile('upload_screenshot')) {
            // $eventwinner->upload_screenshot = $fileName;
            // }
            // $eventwinner->payment_date = Carbon::parse($payment_date)->format('Y-m-d');
            $eventwinner->save();
            
            return Response::json(array('success' => 1,'message'=>'Event winner update successfully.','data' => $eventwinner));
        }
    }

    public function destroy(Request $request)
    {
        EventWinner::find($request->id)->delete();
        return response()->json();
    }

    public function getEventUser(Request $request)
    {
        $rules = array(
            'event_id' => 'required'
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {

            $event_id = $request['event_id'];
            $user_ids = EventJoin::where('event_id',$event_id)->get()->pluck('user_id');
            $EventUsers = User::whereIn('id',$user_ids)->get();
            
            return Response::json(array('success' => 1,'message'=>'Event user list successfully.','data' => $EventUsers));
        }
    }
}