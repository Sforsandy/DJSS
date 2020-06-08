<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\UserIdProof;
use App\WithdrawalRequest;
use Auth;
use Session;
use Validator;
use Toastr;
use Response;
use DataTables;
use DB;
use Mail;
use Illuminate\Support\Facades\Input;
class AccountController extends Controller {
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
    	return view('accounts/index');
    }
    public function IdVerification()
    {
        return view('accounts/idverification');
    }
    public function WithdrawalRequests()
    {
        return view('accounts/withdrawal_requests');
    }

    public function UploadIdProof(Request $request)
    {
        $rules = array(
            'proof_type' => 'required',
            'id_proof_image' => 'required|image|mimes:jpeg,png,jpg|max:1024'
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {
            $idProofImageName = '';
            if($request->hasFile('id_proof_image')) {
                $fileGET = $request->file('id_proof_image');
                $idProofImageName = time().round(1000,9999).'.'.$fileGET->getClientOriginalExtension();
                $request->file('id_proof_image')->move(public_path("/uploads/user_proof"), $idProofImageName);
            }
            
            $userData = UserIdProof::find(Auth::user()->id);
            if(!empty($userData))
            {
                $userData = UserIdProof::find(Auth::user()->id);
                $userData->id_proof_image = $idProofImageName;
                $userData->proof_type = request('proof_type');
                $userData->user_id = Auth::user()->id;
                $userData->save();
            }
            else
            {
                $userData = new UserIdProof;
                $userData->id_proof_image = $idProofImageName;
                $userData->proof_type = request('proof_type');
                $userData->user_id = Auth::user()->id;
                $userData->save();
            }
            $userData = User::find(Auth::user()->id);
            $userData->id_proof_verified = '1';
            $userData->save();

            return Response::json(array('success' => 1,'message'=>'Proof uploaded successfully.','data' => $userData));
        }
    }

    public function getIdProof()
    {
        $IdProofs = DB::table('user_id_proofs')
        ->leftJoin('users', 'user_id_proofs.user_id', '=', 'users.id')
        ->where('users.id_proof_verified', '1')
        ->get();
        
        return DataTables::of($IdProofs)
        ->addColumn('id_proof_image', function ($getIdProofs) {

            $bannerImabe = "<a target='_blank' href=".url('public/uploads/user_proof').'/'.$getIdProofs->id_proof_image."> <img style='width: 75px;' src=".url('public/uploads/user_proof').'/'.$getIdProofs->id_proof_image."></a>";
            return $bannerImabe;
        })
        ->addColumn('user_name', function ($getIdProofs) {
            return $getIdProofs->firstname.' '.$getIdProofs->lastname;
        })
        ->addColumn('mobile_no', function ($getIdProofs) {
            return $getIdProofs->mobile_no;
        })
        ->addColumn('action', function ($getIdProofs) {

            $acceptBtn = "<a href='javascript:;' data-id='".$getIdProofs->user_id."'  onclick='if (confirm(\"Are you sure you want to accept ?\")) changeStatus(".$getIdProofs->user_id.",2) ' class='green'> <i class='fa fa-check'></i> </a>";
            
            $rejectBtn = "<a href='javascript:;'  data-id='".$getIdProofs->user_id."'  onclick='if (confirm(\"Are you sure you want to reject ?\")) changeStatus(".$getIdProofs->user_id.",3) ' class='red'> <i class='fa fa-close'></i> </a>";
            return $acceptBtn.$rejectBtn;
        })
        ->rawColumns(['id_proof_image','user_name','action'])

        ->make(true);
    }

    public function IdProofApproval(Request $request)
    {
        $rules = array(
            'id' => 'required',
            'status' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {

            $id = $request['id'];
            $id_proof_verified = $request['status'];
            
            $updateProofStatus = User::find($id);
            $updateProofStatus->id_proof_verified = $id_proof_verified;
            $updateProofStatus->save(); 
            
            
            return Response::json(array('success' => 1,'message'=>'Id proof status update successfully.','data' => $updateProofStatus));
        }
    }
    

    public function UpdateUpiID(Request $request)
    {
        $rules = array(
            'bank_holder_name' => 'required',
            'paymentupi' => 'required'
        );

        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {
            
            $updateUPI = User::find(Auth::user()->id);
            $updateUPI->bank_holder_name = request('bank_holder_name');
            $updateUPI->paymentupi = request('paymentupi');
            $updateUPI->save(); 
            
            
            return Response::json(array('success' => 1,'message'=>'UPI ID update successfully.','data' => $updateUPI));
        }
    }

    public function EmailVerification(Request $request)
    {
        $rules = array(
            'email' => 'required|string|email|max:255|unique:users,email,'.Auth::user()->id,
        );

        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {
            
            $updateUPI = User::find(Auth::user()->id);
            $updateUPI->email = request('email');
            $updateUPI->save(); 
            $url = url('/').'/email-verification/'.base64_encode($updateUPI->email).'/'.base64_encode(Auth::user()->id).'/'.base64_encode(date('Y-m-d H:i:s'));
            $data = array(
                'receiver_name' => $updateUPI->firstname.' '.$updateUPI->lastname,
                'message_content' => 'Email Verification GamerzByte India',
                'email' => $updateUPI->email,
                'sub' => "Email Verification GamerzByte India",
                'link' => $url
            );

            Mail::send('emails.email_verification', $data, function($m) use ($data) {
                $m->from('support@gamersbyte.in', 'GamerzByte ');
                $m->to($data['email']);
                $m->subject($data['sub']);
            });
            
            return Response::json(array('success' => 1,'message'=>'Please check your mail inbox for verifiy your email.','data' => $updateUPI,'emailData'=>$data));
        }
    }
    
    public function getWithdrawalRequests()
    {
        $IdProofs = WithdrawalRequest::with('users')->where('status','0')->get();
        
        return DataTables::of($IdProofs)
        ->addColumn('user_name', function ($getWithdrawalRequests) {
            return $getWithdrawalRequests->users->firstname.' '.$getWithdrawalRequests->users->lastname;
        })
        ->addColumn('bank_holder_name', function ($getWithdrawalRequests) {
            return $getWithdrawalRequests->users->bank_holder_name;
        })
        ->addColumn('paymentupi', function ($getWithdrawalRequests) {
            return $getWithdrawalRequests->users->paymentupi;
        })
        ->addColumn('mobile_no', function ($getWithdrawalRequests) {
            return $getWithdrawalRequests->users->mobile_no;
        })
        ->addColumn('action', function ($getWithdrawalRequests) {

            $acceptBtn = "<a href='javascript:;' data-id='".$getWithdrawalRequests->user_id."'  onclick='if (confirm(\"Are you sure you want to accept ?\")) changeRequestStatus(".$getWithdrawalRequests->id.",1) ' class='green'> <i class='fa fa-check'></i> </a>";
            
            $rejectBtn = "<a href='javascript:;'  data-id='".$getWithdrawalRequests->user_id."'  onclick='if (confirm(\"Are you sure you want to reject ?\")) changeRequestStatus(".$getWithdrawalRequests->id.",2) ' class='red'> <i class='fa fa-close'></i> </a>";
            return $acceptBtn.$rejectBtn;
        })
        ->rawColumns(['id_proof_image','user_name','action'])

        ->make(true);
    }

    public function changeRequestStatus(Request $request)
    {
        $rules = array(
            'id' => 'required',
            'status' => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {

            $id = $request['id'];
            $status = $request['status'];
            
            $withdrawalRequestStatus = WithdrawalRequest::find($id);
            $withdrawalRequestStatus->status = $status;
            $withdrawalRequestStatus->txn_date = date('Y-m-d H:i:s');
            $withdrawalRequestStatus->save(); 
            // if($status == '1')
            // {
                // $updateWonWallet = \CommonHelper::updateWonWallet($withdrawalRequestStatus->user_id, $withdrawalRequestStatus->amount, '1');
                // $Transactions = \CommonHelper::addUserTransactions($withdrawalRequestStatus->user_id, $withdrawalRequestStatus->amount, '1' ,'balance_debit' ,'success', 'WBD');
                // $withdrawalRequestStatus = WithdrawalRequest::find($id);
                // $withdrawalRequestStatus->txn_id = $Transactions->id;
                // $withdrawalRequestStatus->txn_date = date('Y-m-d H:i:s');
                // $withdrawalRequestStatus->save();
            // }
            
            
            return Response::json(array('success' => 1,'message'=>'Withdrawal request update successfully.','data' => $withdrawalRequestStatus));
        }
    }
}