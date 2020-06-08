<?php


namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Event;
use App\EventJoin;
use App\EventRule;
use App\LeaderboardLavel;
use App\UserLeaderboardPoint;
use App\EventWinner;
use Illuminate\Support\Facades\Auth;
use Validator;
use Response;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Input;

class MyEventController extends Controller
{


    public $successStatus = 200;


    public function getUpcomingEvents(Request $request)
    {
        \LogActivity::addToLog('Get upcoming events','get_upcoming_events',request('device_type'));
        $rules = array (
            'user_id' => 'required'
        );
        $validator = Validator::make ( Input::all (), $rules );

        if ($validator->fails()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        }
        $pageno = (request('page') == '') ? 0 : request('page');
		$no_of_records_per_page = 10;
        $offset = ($pageno-1) * $no_of_records_per_page;
        $user_id  = request('user_id');

        $JoinedEvent = EventJoin::where('user_id',request('user_id'))->pluck('event_id')->toArray();
        if(empty($JoinedEvent))
        {
            return response()->json(['success'=>'1','message'=>'Not joined yet any event.'], $this->successStatus);
        }

        $eventsQry  = Event::with('games')->whereIn('events.status', [0,1])->whereIn('id', $JoinedEvent);
    	$eventsQry->orderBy('events.schedule_datetime', 'DESC');
    	$eventsQry->skip($offset)->take($no_of_records_per_page);
    	$events  = $eventsQry->get();
       	$EventJoinData = EventJoin::groupBy('event_id')->select('*', DB::raw('count(*) as total'))->get();
        $eventData  = array();
        foreach ($events->toArray() as $EventsKey => $EventsValue) {
            $eventData[$EventsKey] = $EventsValue;
            $eventData[$EventsKey]['game'] = $EventsValue['games']['game_name'];
            $eventData[$EventsKey]['player_joined'] = 0;
            $eventData[$EventsKey]['event_rules'] = EventRule::where('event_id',$EventsValue['id'])->orWhere('game_id',$EventsValue['game'])->get();
            // $eventData[$EventsKey]['event_rules']['event'] = EventRule::where('event_id',$EventsValue['id'])->get();
            // $eventData[$EventsKey]['event_rules']['game'] = EventRule::whereNull('event_id')->where('game_id',$EventsValue['game'])->get();
            // $eventData[$EventsKey]['event_rules']['global'] = EventRule::whereNull('event_id')->whereNull('game_id')->get();
            foreach ($EventJoinData->toArray() as $JoinKey => $JoinValue) {
                if($JoinValue['event_id'] == $EventsValue['id'])
                {
                    $eventData[$EventsKey]['player_joined'] = $EventsValue['team_size'] * $JoinValue['total'];
                }
            }
        }
        return response()->json(['success'=>'1','message'=>'Events listed successfully.','data'=>$eventData,'total_record'=>$events->count(),'current_page'=>(int) $pageno], $this->successStatus);
    }

    public function getPastEvents(Request $request)
    {
        \LogActivity::addToLog('Get upcoming events','get_upcoming_events',request('device_type'));
        $rules = array (
            'user_id' => 'required'
        );
        $validator = Validator::make ( Input::all (), $rules );

        if ($validator->fails ()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        }
        $pageno = (request('page') == '') ? 0 : request('page');
        $no_of_records_per_page = 10;
        $offset = ($pageno-1) * $no_of_records_per_page;
        $user_id  = request('user_id');

        $JoinedEvent = EventJoin::where('user_id',request('user_id'))->pluck('event_id')->toArray();
        if(empty($JoinedEvent))
        {
            return response()->json(['success'=>'1','message'=>'Not joined yet any event.'], $this->successStatus);
        }

        $eventsQry  = Event::with('games')->whereIn('events.status', [2])->whereIn('id', $JoinedEvent);
        $eventsQry->orderBy('events.schedule_datetime', 'DESC');
        $eventsQry->skip($offset)->take($no_of_records_per_page);
        $events  = $eventsQry->get();
        $EventJoinData = EventJoin::groupBy('event_id')->select('*', DB::raw('count(*) as total'))->get();
        $eventData  = array();
        foreach ($events->toArray() as $EventsKey => $EventsValue) {
            $eventData[$EventsKey] = $EventsValue;
            $eventData[$EventsKey]['game'] = $EventsValue['games']['game_name'];
            $eventData[$EventsKey]['player_joined'] = 0;
            $eventData[$EventsKey]['event_rules'] = EventRule::where('event_id',$EventsValue['id'])->orWhere('game_id',$EventsValue['game'])->get();
            // $eventData[$EventsKey]['event_rules']['event'] = EventRule::where('event_id',$EventsValue['id'])->get();
            // $eventData[$EventsKey]['event_rules']['game'] = EventRule::whereNull('event_id')->where('game_id',$EventsValue['game'])->get();
            // $eventData[$EventsKey]['event_rules']['global'] = EventRule::whereNull('event_id')->whereNull('game_id')->get();
            foreach ($EventJoinData->toArray() as $JoinKey => $JoinValue) {
                if($JoinValue['event_id'] == $EventsValue['id'])
                {
                    $eventData[$EventsKey]['player_joined'] = $EventsValue['team_size'] * $JoinValue['total'];
                }
            }
        }
        return response()->json(['success'=>'1','message'=>'Events listed successfully.','data'=>$eventData,'total_record'=>$events->count(),'current_page'=>(int) $pageno], $this->successStatus);
    }

}