<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\UserLeaderboardPoint;
use App\LeaderboardLavel;
use Auth;
use Session;
use Validator;
use Toastr;
use Response;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
class UserLeaderboardPointController extends Controller {
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
    	return view('user_leaderboard/index');
    }
    
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

    public function data(Request $request)
    {
        $StartDate = $request['StartDate'];
        $EndDate = $request['EndDate'];

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
        foreach ($UserLeaderboardPoint as $key => $value) {
            
            $UserLeaderboardPoint[$key]['lavel'] = $this->getLavel($LeaderboardLavels,$value['total_point']);
            $total_point1 = @$UserLeaderboardPoint[intval($key)-1]['total_point'];
            $total_point2 = $UserLeaderboardPoint[$key]['total_point'];
            // if($total_point1 == $total_point2)
            // {

            //     $UserLeaderboardPoint[$key]['ranking'] = $i-1;
            // }
            // else
            // {
                $UserLeaderboardPoint[$key]['ranking'] = $i++;
            // }
            
        }
        return DataTables::of($UserLeaderboardPoint)
        ->addColumn('user_name', function ($getLeaderboard) {
            $data  =  $getLeaderboard->user;
            if(isset($data))
            {

                return $getLeaderboard->user->firstname;
                // . ' ('.'xxxxxx'.substr($getLeaderboard->user->mobile_no, 6, 4).')'
            }
            else{return '-';}
            
        })

        ->make(true);
    }

}