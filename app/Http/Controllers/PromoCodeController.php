<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\PromoCode;
use Auth;
use Session;
use Validator;
use Toastr;
use Response;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
class PromoCodeController extends Controller {
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
    	return view('promo_codes/index');
    }
    public function create()
    {
        $users = User::where('id','>','1')->get();
        return view('promo_codes/create',compact('users'));
    }

    public function data()
    {
        $PromoCodes = PromoCode::all();
        return DataTables::of($PromoCodes)
        ->addColumn('action', function ($getPromoCode) {
            $editBtn = "<a href='".route('promo-code.edit', $getPromoCode->id)."' class='purple'> <i class='fa fa-pencil'></i> </a>";
            $deleteBtn = "<a href='javascript:;' id='deleteUser' data-id='".$getPromoCode->id."' onclick='if (confirm(\"Are you sure you want to delete ?\")) deleteRow(".$getPromoCode->id.") ' class='blue'> <i class='fa fa-trash'></i> </a>";
            return $editBtn.$deleteBtn;
        })
        ->addColumn('credit_wallat_type', function ($getPromoCode) {
            switch ($getPromoCode->credit_wallat_type) {
                case "1":
                $wallat = 'Deposited';
                break;
                case "2":
                $wallat = 'Winnings';
                break;
                case "3":
                $wallat = 'Bonus';
                break;
                default:
                $wallat = '';
            }
            return $wallat;
        })
        ->rawColumns([ 'credit_wallat_type','action'])

        ->make(true);
    }


    public function store(Request $request)
    {
        $rules = array(
            'promocode' => 'required|max:20|unique:promo_codes,promocode',
            'amount' => 'required',
            'used_per_user' => 'required',
            'credit_wallat_type' => 'required',
            'expire_date' => 'required'
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {

            $promocode = $request['promocode'];
            $amount = $request['amount'];
            $used_per_user = $request['used_per_user'];
            $credit_wallat_type = $request['credit_wallat_type'];
            $user_id = $request['user_id'];
            $expire_date =  Carbon::parse($request['expire_date'])->format('Y-m-d');
            
            $PromoCode = new PromoCode();
            $PromoCode->promocode = $promocode;
            $PromoCode->amount = $amount;
            $PromoCode->used_per_user = $used_per_user;
            $PromoCode->credit_wallat_type = $credit_wallat_type;
            $PromoCode->expire_date = $expire_date;
            $PromoCode->user_id = $user_id;
            $PromoCode->save(); 
            
            
            return Response::json(array('success' => 1,'message'=>'PromoCode added successfully.','data' => $PromoCode));
        }
    }

    public function edit($id) 
    {
        $data = PromoCode::find($id);
        $users = User::where('id','>','1')->get();
        return view('promo_codes/edit', compact('data','users'));
    }

    public function update(Request $request)
    {
        $rules = array(
            'promocode' => 'required|max:20|unique:promo_codes,promocode,'.$request->id,
            'amount' => 'required',
            'used_per_user' => 'required',
            'credit_wallat_type' => 'required',
            'expire_date' => 'required'
        );

        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {
            $promocode = $request['promocode'];
            $amount = $request['amount'];
            $used_per_user = $request['used_per_user'];
            $credit_wallat_type = $request['credit_wallat_type'];
            $user_id = $request['user_id'];
            $expire_date = $request['expire_date'];
            $id = $request['id'];

            $PromoCode = PromoCode::find($id);
            $PromoCode->promocode = $promocode;
            $PromoCode->amount = $amount;
            $PromoCode->used_per_user = $used_per_user;
            $PromoCode->credit_wallat_type = $credit_wallat_type;
            $PromoCode->expire_date = $expire_date;
            $PromoCode->user_id = $user_id;
            $PromoCode->save();
            
            return Response::json(array('success' => 1,'message'=>'PromoCode update successfully.','data' => $PromoCode));
        }
    }

    public function destroy(Request $request)
    {
        PromoCode::find($request->id)->delete();
        return response()->json();
    }
}