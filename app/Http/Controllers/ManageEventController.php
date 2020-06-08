<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\Event;
use App\EventType;
use App\EventFormat;
use App\EventJoin;
use App\PaymentTransaction;
use App\Game;
use Auth;
use Session;
use Validator;
use Toastr;
use Response;
use DataTables;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
class ManageEventController extends Controller 
{    

    public function showEvents()
    {
        return view('home');
    }
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showManageEvents()
    {
        $moderatorUsers = User::whereHas('roles', function($q){$q->where('name', 'moderator');})->get();
        $users = User::all();
        $users = User::where('status','1')->orderBy('reg_date', 'desc')->get();
        return view('events/index',compact('moderatorUsers','users'));
    }
    public function create()
    {
        $event_types = EventType::all();
        $event_formats = EventFormat::all();
        $games = Game::where('status','1')->get();
        return view('events/create',compact('event_types','event_formats','games'));
    }
    public function data(Request $request)
    {
        $status = $request['status'];
        $created_by = $request['created_by'];
        $StartDate = $request['StartDate'];
        $EndDate = $request['EndDate'];
        if(Auth::user()->hasRole('moderator'))
        {
            $created_by = Auth::user()->id;
        }
        $AdminUsers = User::whereHas('roles', function($q){$q->where('name', 'admin');})->get()->pluck('id');
        $AdminUsers->all();
        $EventQuery  = DB::table('events')
                ->join('event_types as et', 'events.event_type', '=', 'et.id')
                ->join('event_formats as ef', 'events.event_format', '=', 'ef.id')
                ->join('games as g', 'events.game', '=', 'g.id')
                ->select('events.*','et.event_type_name','ef.event_format_name','g.game_name',DB::raw('(select count(*) from event_joined_users where event_id = events.id) as event_joined'));
                if($status >= 0)
                {
                    $EventQuery->where('events.status', $status);
                }
                if($created_by == 1)
                {
                    $EventQuery->whereIn('events.created_by', $AdminUsers);
                }
                if($created_by > 1)
                {
                    $EventQuery->where('events.created_by', $created_by);
                }
                if($StartDate != '' && $EndDate !=  '')
                {
                    $EndDate =  Carbon::createFromFormat('Y-m-d', $EndDate)->addDays(1)->toDateString();
                    $EventQuery->whereBetween('events.schedule_date',[$StartDate,$EndDate]);
                }
        $Event = $EventQuery->get();
        return DataTables::of($Event)
        ->addColumn('event_joined', function ($getEvent) {
            return $getEvent->event_joined * $getEvent->team_size;
        })
        ->addColumn('status', function ($getEvent) {
            $btn = '';
            switch ($getEvent->status) {
                case "0":
                    $btn = '<span class="label label-success">Upcoming</span>';
                    break;
                case "1":
                    $btn = '<span class="label label-success">Ongoing</span>';
                    break;
                case "2":
                    $btn = '<span class="label label-success">Past</span>';
                    break;
            }
            return $btn;
        })
        ->addColumn('action', function ($getEvent) {

            $editBtn = "<a href='".route('manage-event.edit', $getEvent->id)."' class='purple'> <i class='fa fa-pencil'></i> </a>";
            $deleteBtn = '';
            $EventMessages = '';
            $AddUsers = '';
            if(Auth::user()->hasRole('admin'))
            {
            $deleteBtn = "<a href='javascript:;' id='deleteUser' data-id='".$getEvent->id."' data-name='".$getEvent->event_name."' onclick='if (confirm(\"Are you sure you want to delete ?\")) deleteRow(".$getEvent->id.") ' class='blue'> <i class='fa fa-trash'></i> </a>";
            $EventMessages = "<a href='javascript:;' id='SendMessages' data-id='".$getEvent->id."' class='purple'> <i class='fa fa-envelope'></i> </a>";
            $AddUsers = "<a href='javascript:;' id='AddUserInEvent' data-id='".$getEvent->id."' class='purple'> <i class='fa fa-plus'></i> </a>";
            }
            $UserJoinList = "<a href='javascript:;' id='UserJoinList' data-id='".$getEvent->id."' class='purple'> <i class='fa fa-users'></i> </a>";
            return $editBtn.$deleteBtn.$UserJoinList.$EventMessages.$AddUsers;
        })
        ->rawColumns(['status','action'])

        ->make(true);
    }

    public function store(Request $request)
    {
        $rules = array(
            'event_name' => 'required'
            ,'event_description' => 'required'
            ,'game' => 'required|numeric'
            ,'event_format' => 'required|numeric'
            ,'event_type' => 'required|numeric'
            ,'capacity' => 'sometimes|nullable|numeric'
            // ,'fee' => 'sometimes|nullable|numeric'
            ,'fee' => 'required_if:event_type,2|required_if:event_type,4'
            ,'location' => 'required_if:event_type,3|required_if:event_type,4'
            ,'team_size' => 'required|numeric'
            ,'total_prize' => 'sometimes|nullable|numeric'
            ,'winner_details' => 'sometimes|nullable'
            // ,'winner_prize' => 'sometimes|nullable|numeric'
            // ,'runner_up1_prize' => 'sometimes|nullable|numeric'
            // ,'runner_up2_prize' => 'sometimes|nullable|numeric'
            // ,'runner_up2_prize' => 'sometimes|nullable|numeric'
            ,'schedule_date' => 'required'
            ,'schedule_time' => 'required'
            ,'access_details' => 'sometimes|nullable'
            ,'stream_url' => 'sometimes|nullable|url'
            ,'wallet_type' => 'required_if:event_type,2|required_if:event_type,4'
            ,'bonus_wallet_per' => 'required_if:wallet_type,3'
            ,'bonus_max_amt' => 'required_if:wallet_type,3'
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {
            $event_format_name = $request['event_format_name'];
            $event_name = $request['event_name'];
            $event_description  = $request['event_description'];
            $game  = $request['game'];
            $event_format   = $request['event_format'];
            $event_type = $request['event_type'];
            $capacity   = $request['capacity'];
            $fee    = $request['fee'];
            $location    = $request['location'];
            $team_size    = $request['team_size'];
            $total_prize    = $request['total_prize'];
            $winner_details    = $request['winner_details'];
            $winner_prize   = $request['winner_prize'];
            $runner_up1_prize   = $request['runner_up1_prize'];
            $runner_up2_prize   = $request['runner_up2_prize'];
            $schedule_date  = Carbon::parse($request['schedule_date'])->format('Y-m-d');
            $schedule_time  = $request['schedule_time'];
            $access_details = $request['access_details'];
            $stream_url = $request['stream_url'];
            $wallet_type = $request['wallet_type'];
            $bonus_wallet_per = $request['bonus_wallet_per'];
            $bonus_max_amt = $request['bonus_max_amt'];
            $discord_url = 'https://discord.gg/JS8jEzs';
            $invite_code = strtoupper(substr(bin2hex(random_bytes(rand(1000,9999))),0, 6));
            if ($schedule_date.' '.$schedule_time < date("Y-m-d H:i:s")) 
            {
                return Response::json(array('success' => 0,'message' => "Time already passed."));
            }

            $event = new Event();
            $event->event_name = $event_name;
            $event->event_description = $event_description;
            $event->game = $game;
            $event->event_format = $event_format;
            $event->event_type = $event_type;
            $event->capacity = $capacity;
            $event->fee = $fee;
            $event->location = $location;
            $event->team_size = $team_size;
            $event->total_prize = $total_prize;
            $event->winner_details = $winner_details;
            $event->winner_prize = $winner_prize;
            $event->runner_up1_prize = $runner_up1_prize;
            $event->runner_up2_prize = $runner_up2_prize;
            $event->schedule_date = $schedule_date;
            $event->schedule_time = $schedule_time;
            $event->schedule_datetime = $schedule_date.' '.$schedule_time;
            $event->access_details = $access_details;
            $event->stream_url = $stream_url;
            $event->discord_url = $discord_url;
            $event->wallet_type = $wallet_type;
            $event->bonus_wallet_per = $bonus_wallet_per;
            $event->bonus_max_amt = $bonus_max_amt;
            $event->invite_code = $invite_code;
            $event->created_by = Auth::user()->id;
            $event->save(); 
            
            
            return Response::json(array('success' => 1,'message'=>'Event added successfully.','data' => $event));
        }
    }

    public function edit($id) 
    {
        $event_data = Event::find($id);
        if(Auth::user()->hasRole('moderator') && $event_data->created_by != Auth::user()->id)
        {
            return view('errors/403');
        }

        $event_types = EventType::all();
        $event_formats = EventFormat::all();
        $games = Game::where('status','1')->get();
        return view('events/edit',compact('event_data','event_types','event_formats','games'));
    }

    public function update(Request $request)
    {
        $rules = array(
            'event_id' => 'required'
            ,'event_name' => 'required'
            ,'event_description' => 'required'
            ,'game' => 'required|numeric'
            ,'event_format' => 'required|numeric'
            ,'event_type' => 'required|numeric'
            ,'capacity' => 'sometimes|nullable|numeric'
            ,'fee' => 'required_if:event_type,2|required_if:event_type,4'
            ,'location' => 'required_if:event_type,3|required_if:event_type,4'
            ,'team_size' => 'required|numeric'
            ,'total_prize' => 'sometimes|nullable|numeric'
            ,'winner_details' => 'sometimes|nullable'
            // ,'winner_prize' => 'sometimes|nullable|numeric'
            // ,'runner_up1_prize' => 'sometimes|nullable|numeric'
            // ,'runner_up2_prize' => 'sometimes|nullable|numeric'
            // ,'schedule_date' => 'required|date|date_format:dd/mm/Y|after_or_equal:today'
            ,'schedule_date' => 'required'
            ,'schedule_time' => 'required'
            ,'access_details' => 'sometimes|nullable'
            ,'stream_url' => 'sometimes|nullable|url'
            ,'bonus_wallet_per' => 'required_if:wallet_type,3'
            ,'bonus_max_amt' => 'required_if:wallet_type,3'
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {
            $event_id = $request['event_id'];
            $event_format_name = $request['event_format_name'];
            $event_name = $request['event_name'];
            $event_description  = $request['event_description'];
            $game  = $request['game'];
            $event_format   = $request['event_format'];
            $event_type = $request['event_type'];
            $capacity   = $request['capacity'];
            $fee    = $request['fee'];
            $location    = $request['location'];
            $team_size    = $request['team_size'];
            $total_prize    = $request['total_prize'];
            $winner_details    = $request['winner_details'];
            $winner_prize   = $request['winner_prize'];
            $runner_up1_prize   = $request['runner_up1_prize'];
            $runner_up2_prize   = $request['runner_up2_prize'];
            $schedule_date  = Carbon::parse($request['schedule_date'])->format('Y-m-d');
            $schedule_time  = $request['schedule_time'];
            $access_details = $request['access_details'];
            $stream_url = $request['stream_url'];
            $wallet_type = $request['wallet_type'];
            $bonus_wallet_per = $request['bonus_wallet_per'];
            $bonus_max_amt = $request['bonus_max_amt'];
            $discord_url = 'https://discord.gg/JS8jEzs';
            $status = $request['status'];
            
            $event = Event::find($event_id);
            // check when moderator edit events
            if(Auth::user()->hasRole('moderator') && $event->created_by != Auth::user()->id)
            {
                return Response::json(array('success' => 0,'message'=>'Oops! Access denied!!.'));
            }
            // if ($schedule_date.' '.$schedule_time < date("Y-m-d H:i:s")) 
            // {
            //     return Response::json(array('success' => 0,'message' => "Time already passed."));
            // }
            $event->event_name = $event_name;
            $event->event_description = $event_description;
            $event->game = $game;
            $event->event_format = $event_format;
            $event->event_type = $event_type;
            $event->capacity = $capacity;
            $event->team_size = $team_size;
            $event->location = $location;
            $event->wallet_type = $wallet_type;
            $event->bonus_wallet_per = $bonus_wallet_per;
            $event->bonus_max_amt = $bonus_max_amt;
            if(Auth::user()->hasRole('admin'))
            {
            $event->fee = $fee;
            $event->total_prize = $total_prize;
            $event->winner_details = $winner_details;
            $event->winner_prize = $winner_prize;
            $event->runner_up1_prize = $runner_up1_prize;
            $event->runner_up2_prize = $runner_up2_prize;
            }
            $event->schedule_date = $schedule_date;
            $event->schedule_time = $schedule_time;
            $event->schedule_datetime = $schedule_date.' '.$schedule_time;
            $event->access_details = $access_details;
            $event->stream_url = $stream_url;
            $event->discord_url = $discord_url;
            $event->status = $status;
            // $event->created_by = Auth::user()->id;
            $event->save(); 
            
            
            return Response::json(array('success' => 1,'message'=>'Event update successfully.','data' => $event));
        }
    }

    public function destroy(Request $request)
    {
        $event = Event::find($request->id);
         // check when moderator delete events
        if(Auth::user()->hasRole('moderator') && $event->created_by != Auth::user()->id)
        {
            return Response::json(array('success' => 0,'message'=>'Oops! Access denied!!.'));
        }
        Event::find($request->id)->delete();
        return response()->json();
    }

    public function getEventJoinedUser(Request $request)
    {
        $EventJoins = EventJoin::where('event_id',$request->event_id)->with('users')->get();
        return DataTables::of($EventJoins)

        ->addColumn('username', function ($getEvents) {
                return $getEvents->users->firstname.' '.$getEvents->users->lastname;
        })
        ->addColumn('character_name', function ($getEvents) {
                return $getEvents->users->character_name;
        })
        ->rawColumns(['username','character_name'])

        ->make(true);
    }

    public function AddUserToEvent(Request $request)
    {
        $rules = array(
            'event_id' => 'required',
            'user_id' => 'required',
            'txn_id' => 'required'
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {

            $event_id = $request['event_id'];
            $user_id = $request['user_id'];
            $txn_id = $request['txn_id'];

            $eventData = Event::find($event_id);

            $eventjoin = new EventJoin();
            $eventjoin->user_id = $user_id;
            $eventjoin->event_id = $event_id;
            $eventjoin->game_id = $eventData->game;
            $eventjoin->joined_date = date("Y-m-d H:i:s");
            $eventjoin->save(); 

            $eventtransaction = new  PaymentTransaction();
            $eventtransaction->event_id         =   $event_id;
            $eventtransaction->user_id          =   $user_id;
            $eventtransaction->txn_id           =   $txn_id;
            $eventtransaction->txn_date         =   date('Y-m-d H:i:s');
            $eventtransaction->status           =   'TXN_SUCCESS';
            $eventtransaction->resp_msg           =   'TRANSACTION_SUCCESS';
            $eventtransaction->event_type       =   $eventData->event_type;
            $eventtransaction->save();
            
            return Response::json(array('success' => 1,'message'=>'User added successfully.','data' => $eventjoin));
        }
    }
}