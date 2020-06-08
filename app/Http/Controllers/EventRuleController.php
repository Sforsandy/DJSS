<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\EventRule;
use App\Event;
use App\Game;
use Auth;
use Session;
use Validator;
use Toastr;
use Response;
use DataTables;
use Illuminate\Support\Facades\Input;
class EventRuleController extends Controller {
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
    	return view('event_rules/index');
    }
    public function create()
    {
        if(Auth::user()->hasRole('moderator'))
        {
            $events = Event::where('created_by',Auth::user()->id)->get();
        }
        else
        {
            $events = Event::all();
        }
        $games = Game::all();
        return view('event_rules/create',compact('events','games'));
    }

    public function data()
    {
        $event_ids = array();
        if(Auth::user()->hasRole('moderator'))
        {
            $event_ids = Event::where('created_by',Auth::user()->id)->get()->pluck('id');
            $EventRules = EventRule::whereIn('event_id',$event_ids)->with('events');
        }
        else
        {
            $EventRules = EventRule::with('events','games');
        }
        return DataTables::of($EventRules)
        ->addColumn('event_name', function ($getEventRule) {
            $data  =  $getEventRule->events;
            if(isset($data))
            {
                $eventLink = "<a href='".route('event-details', ["id"=>$getEventRule->event_id])."' class='purple'>".$getEventRule->events->event_name."</a>";
                return $eventLink;
            }
            else{return 'All';}
        })
        ->addColumn('game_name', function ($getEventRule) {
            $data  =  $getEventRule->games;
            if(isset($data))
            {
                return $getEventRule->games->game_name;
            }
            else{return '-';}
        })
        ->addColumn('action', function ($getEventRule) {

            $editBtn = "<a href='".route('event-rule.edit', $getEventRule->id)."' class='purple'> <i class='fa fa-pencil'></i> </a>";
            $deleteBtn = '';
            
            $deleteBtn = "<a href='javascript:;' id='deleteUser' data-id='".$getEventRule->id."' onclick='if (confirm(\"Are you sure you want to delete ?\")) deleteRow(".$getEventRule->id.") ' class='blue'> <i class='fa fa-trash'></i> </a>";
            return $editBtn.$deleteBtn;
        })
        ->rawColumns(['event_name', 'action','game_name'])

        ->make(true);
    }


    public function store(Request $request)
    {
        $rules = array(
            'rules' => 'required'
        );
        if(Auth::user()->hasRole('moderator'))
        {
            $rules['game_id'] = 'required';
        }

        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {

            $rules = $request['rules'];
            $event_id = $request['event_id'];
            $game_id = $request['game_id'];
            
            $eventrule = new EventRule();
            $eventrule->rules = $rules;
            $eventrule->event_id = $event_id;
            $eventrule->game_id = $game_id;
            $eventrule->save(); 
            
            
            return Response::json(array('success' => 1,'message'=>'Event rule added successfully.','data' => $eventrule));
        }
    }

    public function edit($id) 
    {

        $data = EventRule::find($id);
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
        $games = Game::all();
        return view('event_rules/edit', compact('data','events','games'));
    }

    public function update(Request $request)
    {
        $rules = array(
            'rules' => 'required'
        );
        if(Auth::user()->hasRole('moderator'))
        {
            $rules['game_id'] = 'required';
        }

        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {

            $rules = $request['rules'];
            $event_id = $request['event_id'];
            $game_id = $request['game_id'];
            $id = $request['id'];

            if(Auth::user()->hasRole('moderator') && @Event::find($event_id)->created_by != Auth::user()->id)
            {
                return Response::json(array('success' => 0,'message'=>'Oops! Access denied!!.'));
            }
            $eventrule = EventRule::find($id);
            $eventrule->rules = $rules;
            $eventrule->event_id = $event_id;
            $eventrule->game_id = $game_id;
            $eventrule->save();
            
            return Response::json(array('success' => 1,'message'=>'Event rule update successfully.','data' => $eventrule));
        }
    }

    public function destroy(Request $request)
    {
        EventRule::find($request->id)->delete();

        return response()->json();
    }

    public function getGameEvents(Request $request)
    {
        $game = $request['game'];
        if($game > 0)
        {
            if(Auth::user()->hasRole('moderator'))
            {
                $EventList = Event::where('created_by',Auth::user()->id)->where('game',$game)->get();
            }else{$EventList = Event::where('game',$game)->get();}
            
        }
        else
        {
            $EventList = Event::all();
        }

        return Response::json(array('success' => 1,'message'=>'Event list successfully.','data' => $EventList));
    }
}