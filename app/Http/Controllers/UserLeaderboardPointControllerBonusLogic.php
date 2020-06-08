<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\UserBonusWallet;
use Auth;
use Session;
use Validator;
use Toastr;
use Response;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
class UserLeaderboardPointControllerBonusLogic extends Controller {
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
    
    public function getProjectBidCnt($UserBonusWalletArr,$attrId) {
        if ($attrId != "") {
            if (isset($UserBonusWalletArr[$attrId])) {
                return $result = $UserBonusWalletArr[$attrId];
            }
        }
        return "0";
    }

    public function array_sort_by_column(&$arr, $col, $dir = SORT_DESC) {
        $sort_col = array();
        $arrr = array();
        foreach ($arr as $key=> $row) {
            $sort_col[$key] = $row[$col];
            array_push($arrr, $row);
        }

        array_multisort($sort_col, $dir, $arr);
        return $arrr;
    }

    

    public function data(Request $request)
    {
        $StartDate = $request['StartDate'];
        $EndDate = $request['EndDate'];

        $UserBonusWalletCreditQry = UserBonusWallet::with('user');
        // $UserBonusWalletCreditQry = UserBonusWallet::selectRaw('user_id,sum(point) as total_point_credit');
        $UserBonusWalletCreditQry->where('txn_type','0');
        $EndDate =  Carbon::createFromFormat('Y-m-d', $EndDate)->addDays(1)->toDateTimeString();
        $UserBonusWalletCreditQry->whereBetween('txn_date',[$StartDate,$EndDate]);
        $UserBonusWalletCreditQry->groupBy('user_id');
        $UserBonusWalletCredit = $UserBonusWalletCreditQry->get();
        $UserBonusWalletCreditArr = array_column($UserBonusWalletCredit->toArray(), 'user_id');

        $UserBonusWalletDebitQry = UserBonusWallet::selectRaw('*,sum(point) as total_point_debit')->where('txn_type','1');
        $UserBonusWalletDebitQry->whereIn('user_id',$UserBonusWalletCreditArr);
        $UserBonusWalletDebitQry->whereBetween('txn_date',[$StartDate,$EndDate]);
        $UserBonusWalletDebitQry->groupBy('user_id');
        $UserBonusWalletDebit= $UserBonusWalletDebitQry->get();
        $UserBonusWalletArr = array();
        foreach ($UserBonusWalletDebit->toArray() as $key => $value) {
            $UserBonusWalletArr[$value['user_id']] = $value['total_point_debit'];
        }

        foreach ($UserBonusWalletCredit as $key1 => $value1) {
            $UserBonusWalletCredit[$key1]['total_point'] = $this->getProjectBidCnt($UserBonusWalletArr,$value1['user_id']);
        }
        $UserBonusWalletCredit =  $UserBonusWalletCredit->toArray();
        $UserBonusWalletCredit =  $this->array_sort_by_column($UserBonusWalletCredit,'total_point');


        $i = 1;
        foreach ($UserBonusWalletCredit as $key => $value) {
            $total_point1 = @$UserBonusWalletCredit[intval($key)-1]['total_point'];
            $total_point2 = $UserBonusWalletCredit[$key]['total_point'];
            if($total_point1 == $total_point2)
            {

                $UserBonusWalletCredit[$key]['ranking'] = $i-1;
            }
            else
            {
                $UserBonusWalletCredit[$key]['ranking'] = $i++;
            }
            
        }

        return DataTables::of($UserBonusWalletCredit)
        ->addColumn('user_name', function ($getLeaderboard) {
            $data  =  $getLeaderboard['user'];
            if(isset($data))
            {

                return $getLeaderboard['user']['firstname']. ' ('.'xxxxxx'.substr($getLeaderboard['user']['mobile_no'], 6, 4).')';
            }
            else{return '-';}
            
        })

        ->make(true);
    }

}