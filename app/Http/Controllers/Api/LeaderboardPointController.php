<?php


namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\LeaderboardPoint;
use Illuminate\Support\Facades\Auth;
use Validator;
use Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class LeaderboardPointController extends Controller
{


    public $successStatus = 200;


    public function getLeaderboardPoint(Request $request)
    {
        \LogActivity::addToLog('Get leaderborad points','getLeaderboardPoint',request('device_type'));
       
        $LeaderboardPoint = LeaderboardPoint::all();
        return response()->json(['success'=>'1','message'=>'Leaderboardpoint listed successfully.','data'=>$LeaderboardPoint], $this->successStatus);
    }


}