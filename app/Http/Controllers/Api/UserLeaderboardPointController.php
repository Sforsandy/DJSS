<?php


namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\UserLeaderboardPoint;
use App\LeaderboardLavel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Validator;
use Response;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class UserLeaderboardPointController extends Controller
{


    public $successStatus = 200;


    public function getLavel($lavels = array(),$user_point)
    {
        $lavel = 'Beginner';
        foreach ($lavels as $key => $value) {if(($user_point >= $value->start_point) && ($user_point <= $value->end_point))
            {
                $lavel = $value->lavel;
            }
        }
        return $lavel;
    }

    // public function getUserLeaderboard(Request $request)
    // {
    //     \LogActivity::addToLog('Get user leaderborad','get_user_leaderboard',request('device_type'));
    //     $rules = array(
    //         'start_date' => 'required|date|date_format:Y-m-d',
    //         'end_date' => 'required|date|date_format:Y-m-d'
    //     );


    //     $validator = Validator::make(Input::all(), $rules);

    //     if ($validator->fails ()) {
    //         return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
    //     }
    //     $StartDate = request('start_date');
    //     $EndDate = request('end_date');
    //     $LeaderboardLavels = LeaderboardLavel::all();

    //     $UserLeaderboardPointQry = UserLeaderboardPoint::with('user');
    //     $UserLeaderboardPointQry->selectRaw('*,sum(point) as total_point');
    //     if($StartDate != '' && $EndDate !=  '')
    //     {
    //         $EndDate =  Carbon::createFromFormat('Y-m-d', $EndDate)->addDays(1)->toDateTimeString();
    //         $UserLeaderboardPointQry->whereBetween('point_added_date',[$StartDate,$EndDate]);
    //     }
    //     $UserLeaderboardPointQry->orderBy('total_point', 'DESC');
    //     $UserLeaderboardPointQry->groupBy('user_id');
    //     $UserLeaderboardPoint = $UserLeaderboardPointQry->get();
    //     $i = 1;
    //     $UserLeaderboard = array();
    //     foreach ($UserLeaderboardPoint as $key => $value) {

    //         $UserLeaderboard[$key]['firstname'] = $value->user->firstname;
    //         $UserLeaderboard[$key]['lastname'] = $value->user->lastname;
    //         $UserLeaderboard[$key]['mobile_no'] = $value->user->mobile_no;
    //         $UserLeaderboard[$key]['total_point'] = $value->total_point;
    //         $UserLeaderboard[$key]['lavel'] = $this->getLavel($LeaderboardLavels,$value['total_point']);
    //         $total_point1 = @$UserLeaderboardPoint[intval($key)-1]['total_point'];
    //         $total_point2 = $UserLeaderboardPoint[$key]['total_point'];
    //         if($total_point1 == $total_point2)
    //         {

    //             $UserLeaderboard[$key]['ranking'] = $i-1;
    //         }
    //         else
    //         {
    //             $UserLeaderboard[$key]['ranking'] = $i++;
    //         }
            
    //     }

    //     return response()->json(['success'=>'1','message'=>'User leaderborad listed successfully.','data'=>$UserLeaderboard], $this->successStatus);
    // }

    public function getUserLeaderboard(Request $request)
    {
        \LogActivity::addToLog('Get user leaderborad','get_user_leaderboard',request('device_type'));
        $rules = array(
            'user_id' => 'required',
            'start_date' => 'required|date|date_format:Y-m-d',
            'end_date' => 'required|date|date_format:Y-m-d'
        );


        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails ()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        }
        $StartDate = request('start_date');
        $EndDate = request('end_date');
        $LeaderboardLavels = LeaderboardLavel::all();

        $UserLeaderboardPointQry = UserLeaderboardPoint::with('user');
        $UserLeaderboardPointQry->selectRaw('*,sum(point) as total_point');
        if($StartDate != '' && $EndDate !=  '')
        {
            $EndDate =  Carbon::createFromFormat('Y-m-d', $EndDate)->addDays(1)->toDateTimeString();
            $UserLeaderboardPointQry->whereBetween('point_added_date',[$StartDate,$EndDate]);
        }
        $UserLeaderboardPointQry->orderBy('total_point', 'DESC');
        $UserLeaderboardPointQry->groupBy('user_id');
        $UserLeaderboardPoint = $UserLeaderboardPointQry->get();
        $i = 1;
        $UserLeaderboard = array();
        $LoginUserData = null;
        $userData = User::find(request('user_id'));
        $LoginUserData['firstname'] = $userData->firstname;
        $LoginUserData['lastname'] = $userData->lastname;
        $LoginUserData['user_id'] = request('user_id');
        $LoginUserData['total_point'] = "0";
        $LoginUserData['level'] = "Beginner";
        $LoginUserData['ranking'] = "0";
        foreach ($UserLeaderboardPoint as $key => $value) {

            $user_id = $value->user_id;
            $total_point = $value->total_point;
            $lavel = $this->getLavel($LeaderboardLavels,$value['total_point']);
            $total_point1 = @$UserLeaderboardPoint[intval($key)-1]['total_point'];
            $total_point2 = $UserLeaderboardPoint[$key]['total_point'];
            // if($total_point1 == $total_point2)
            // {

            //     $ranking = $i-1;
            // }
            // else
            // {
                $ranking = $i++;
            // }
            if($user_id == request('user_id'))
            {
                $LoginUserData = array();
                $LoginUserData['firstname'] = $value->user->firstname;
                $LoginUserData['lastname'] = $value->user->lastname;
                $LoginUserData['user_id'] = $user_id;
                $LoginUserData['total_point'] = $total_point;
                $LoginUserData['level'] = $lavel;
                $LoginUserData['ranking'] = $ranking;
            }else{
                $temp['firstname'] = $value->user->firstname;
                $temp['lastname'] = $value->user->lastname;
                $temp['user_id'] = $user_id;
                $temp['total_point'] = $total_point;
                $temp['level'] = $lavel;
                $temp['ranking'] = $ranking;
                array_push($UserLeaderboard,$temp);
            }
        }
        // 'login_user_data'=>$LoginUserData,
        return response()->json(['success'=>'1','message'=>'User leaderborad listed successfully.','login_user_data'=>$LoginUserData,'data'=>$UserLeaderboard], $this->successStatus);
    }


}