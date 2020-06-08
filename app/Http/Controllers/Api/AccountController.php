<?php


namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\UserIdProof;
use App\PaymentTransaction;
use App\UserTransaction;
use App\WithdrawalRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Validator;
use Response;
use Mail;
use Illuminate\Support\Facades\Input;

class AccountController extends Controller
{


    public $successStatus = 200;


    public function UploadIdProof(Request $request)
    {
        // \LogActivity::addToLog('Upload user id proof','uploadidproof',request('device_type'));
        $rules = array(
            'user_id' => 'required|exists:users,id',
            'proof_type' => 'required',
            'id_proof_image' => 'required|image|mimes:jpeg,png,jpg|max:1024'
        );


        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails ()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        }
        else {
            $user = User::find(request('user_id'));
            if(empty($user))
            {
                return response()->json(['success'=>'0','message'=>'User not found.'], $this->successStatus);
            }
            $idProofImageName = '';
            if($request->hasFile('id_proof_image')) {
                $fileGET = $request->file('id_proof_image');
                $idProofImageName = time().rand(1000,9999).'.'.$fileGET->getClientOriginalExtension();
                $request->file('id_proof_image')->move(public_path("/uploads/user_proof"), $idProofImageName);
            }

            $userData = UserIdProof::find(request('user_id'));
            if(!empty($userData))
            {
                $userData = UserIdProof::find(request('user_id'));
                $userData->id_proof_image = $idProofImageName;
                $userData->proof_type = request('proof_type');
                $userData->user_id = request('user_id');
                $userData->save();
            }
            else
            {
                $userData = new UserIdProof;
                $userData->id_proof_image = $idProofImageName;
                $userData->proof_type = request('proof_type');
                $userData->user_id = request('user_id');
                $userData->save();
            }
            $userData = User::find(request('user_id'));
            $userData->id_proof_verified = '1';
            $userData->save();
            
            
            return response()->json(['success'=>'1','message'=>'Id proof uploaded successfully.'], $this->successStatus);
        }
    }

    public function getAccountInfo(Request $request)
    {
        \LogActivity::addToLog('Get account info','getaccountinfo',request('device_type'));
        $rules = array (
            'user_id' => 'required|exists:users,id'
        );
        $validator = Validator::make ( Input::all (), $rules );

        if ($validator->fails ()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        }
        // ->where('id',request('user_id'))
        $userData = User::selectRaw('id,deposited_balance,winnings_balance,bonus_balance,SUM(deposited_balance+winnings_balance+bonus_balance) as total_balance,email_verified,id_proof_verified,paymentupi')->where('id',request('user_id'))->first();
        if(empty($userData))
            {
                return response()->json(['success'=>'0','message'=>'User not found.'], $this->successStatus);
            }
        $userData['paymentupi_updated'] = empty($userData->paymentupi) ? '0' : '1';
       	return response()->json(['success'=>'1','message'=>'Accounts data showed successfully.','data'=>$userData], $this->successStatus);
    }


    public function UpdateUpiID(Request $request)
    {
        \LogActivity::addToLog('Update user upi id and bank holder name','update_upiid',request('device_type'));
        $rules = array(
            'user_id' => 'required',
            'bank_holder_name' => 'required',
            'paymentupi' => 'required'
        );

        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        else {

            $updateUPI = User::find(request('user_id'));
            $updateUPI->bank_holder_name = request('bank_holder_name');
            $updateUPI->paymentupi = request('paymentupi');
            $updateUPI->save(); 
            
            return response()->json(['success'=>'1','message'=>'UPI ID update successfully.'], $this->successStatus);
        }
    }

    public function EmailVerification(Request $request)
    {
        \LogActivity::addToLog('User email verification','email_verification',request('device_type'));
        $rules = array(
            'user_id' => 'required',
            'email' => 'required|string|email|max:255|unique:users,email,'.request('user_id'),
        );

        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        else {
            
            $updateUPI = User::find(request('user_id'));
            $updateUPI->email = request('email');
            $updateUPI->save(); 
            $url = url('/').'/email-verification/'.base64_encode($updateUPI->email).'/'.base64_encode(request('user_id')).'/'.base64_encode(date('Y-m-d H:i:s'));
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
            
            return response()->json(['success'=>'1','message'=>'Please check your mail inbox for verifiy your email.'], $this->successStatus);
        }
    }

    public function addBalance(Request $request)
    {
        \LogActivity::addToLog('User can add balance','add_balance',request('device_type'));
        $rules = array(
            'user_id' => 'required',
            'amount' => 'required'
        );

        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        else {

            $pendingTxn = PaymentTransaction::where('user_id',request('user_id'))
            ->whereNull('status')
            ->OrderBy('id', 'desc')
            ->first();

            if(empty($pendingTxn))
            {
                $eventtransaction = new PaymentTransaction();
                $eventtransaction->user_id          = request('user_id');
                $eventtransaction->order_id         =  \CommonHelper::generateORDNumber('WBC',request('user_id'));
                $eventtransaction->event_id         =   NULL;
                $eventtransaction->txn_amount        =   request('amount');
                $eventtransaction->save();
                $transaction_id = $eventtransaction->id;

            }
            else
            {
                $transaction_id = $pendingTxn->id;
                $transactionUpdateOrderId = PaymentTransaction::find($transaction_id);
                $transactionUpdateOrderId->order_id = \CommonHelper::generateORDNumber('WBC',request('user_id'));
                $transactionUpdateOrderId->event_id   = NULL;
                $transactionUpdateOrderId->txn_amount = request('amount');
                $transactionUpdateOrderId->save();
            }
            // CREATE NULL TRANSACTION END
            return response()->json(['success'=>'1','message'=>'Redirect url to complete payment.','data' => url('/').'/app/payment/paytm/'.$transaction_id], $this->successStatus);
        }
    }

    public function checkPaymentStatus(Request $request)
    {
        \LogActivity::addToLog('Get transactions list','get_transactions_list',request('device_type'));
        $rules = array (
            'transaction_id' => 'required|exists:payment_transactions,id'
            ,'user_id' => 'required'
        );
        $validator = Validator::make ( Input::all (), $rules );

        if ($validator->fails ()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        }

        $trascnData = PaymentTransaction::where('id',request('transaction_id'))->where('user_id',request('user_id'))->whereNotNull('status')->first();

        return response()->json(['success'=>'1','message'=>'Transactions found successfully.','data'=>$trascnData], $this->successStatus);
    }

    public function getDocumentImage(Request $request)
    {
        \LogActivity::addToLog('Get document image','get_document_image',request('device_type'));
        $rules = array (
            'user_id' => 'required'
        );
        $validator = Validator::make ( Input::all (), $rules );

        if ($validator->fails ()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        }

        $data = UserIdProof::where('user_id',request('user_id'))->first();

        return response()->json(['success'=>'1','message'=>'Document found successfully.','data'=>$data], $this->successStatus);
    }

    public function addWithdrawalRequest(Request $request)
    {
        \LogActivity::addToLog('Add Withdrawal Request','add_withdrawal_request',request('device_type'));
        $rules = array (
            'amount' => 'required|numeric'
            ,'user_id' => 'required|exists:users,id'
        );
        $validator = Validator::make ( Input::all (), $rules );
        
        if ($validator->fails ()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        }
        $userData = User::find(request('user_id'));
        // if($userData->winnings_balance <= request('amount'))
        // {
        //     return response()->json(['success'=>'1','message'=>'Withdrawal amount less than your won balance.'], $this->successStatus);
        // }
        $withdrawalRequest = new WithdrawalRequest();
        $withdrawalRequest->user_id = request('user_id');
        $withdrawalRequest->amount  = request('amount');
        $withdrawalRequest->status  = '0';
        $withdrawalRequest->save();

         $updateWonWallet = \CommonHelper::updateWonWallet(request('user_id'), request('amount'), '1');
         $Transactions = \CommonHelper::addUserTransactions(request('user_id'), request('amount'), '1' ,'balance_debit' ,'success', 'WBD');

        return response()->json(['success'=>'1','message'=>'Withdrawal successfully.'], $this->successStatus);
    }

    public function getTransactionsList(Request $request)
    {
        \LogActivity::addToLog('Get transactions list','get_transactions_list',request('device_type'));
        $rules = array (
            'user_id' => 'required|exists:users,id'
        );
        $validator = Validator::make ( Input::all (), $rules );

        if ($validator->fails ()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        }

        $trascnData = UserTransaction::with('games')
        ->where('user_id',request('user_id'))
        ->where('status','success')
        ->orderBy('id', 'DESC')
        ->orderBy('txn_date', 'DESC')
        ->get();
        $data = array();
        foreach ($trascnData as $key => $value) {
            $data[$key]['game'] = (string) @$value->games->game_name;
            $data[$key]['txn_amount'] = $value->txn_amount;
            $data[$key]['txn_date'] = $value->txn_date;
            $data[$key]['txn_title'] = $value->txn_title;
            // $data[$key]['txn_title'] = ucfirst(str_replace('_', ' ', $value->txn_title));
            $data[$key]['txn_type'] = $value->txn_type;
            $data[$key]['order_id'] = $value->order_id;
        }

        return response()->json(['success'=>'1','message'=>'Transactions listed successfully.','data'=>$data], $this->successStatus);
    }

}