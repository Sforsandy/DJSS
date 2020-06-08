<?php


namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\SmsVerification;
use App\SmsLogs;
use App\UserBonusWallet;
use App\State;
use App\City;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Validator;
use Response;
use Mail;
use Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class UserController extends Controller
{


    public $successStatus = 200;


    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(){
        \LogActivity::addToLog('User Login','login',request('device_type'));
        $rules = array (
            'mobile_no' => 'required|numeric|digits:10',
            'password' => 'required' 
        );
        $validator = Validator::make ( Input::all (), $rules );

        if ($validator->fails ()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        }
        if(Auth::attempt(['mobile_no' => request('mobile_no'), 'password' => request('password')])){
            if(Auth::user()->hasRole('admin'))
            {
                return response()->json(['success'=>'0','message'=>'Please check your credentials and try again.'], $this->successStatus);
            }
            $user = Auth::user();
            $token =  $user->createToken($user->firstname)->accessToken;
            $user = User::find($user->id);
            $user->device_type = request('device_type');
            $user->fcm_token = request('fcm_token');
            $user->save();
            return response()->json(['token' => $token,'data'=>$user,'success'=>'1','message'=>'User login successfully.'], $this->successStatus);
        }
        else{
            return response()->json(['success'=>'0','message'=>'Please check your credentials and try again.'], $this->successStatus);
        }
    }

    public function login_via_facebook(){
        \LogActivity::addToLog('User login via facebook','login_via_facebook',request('device_type'));
        $rules = array (
            'firstname' => 'required'
            ,'facebook_id' => 'required' 
        );
        $validator = Validator::make ( Input::all (), $rules );

        if ($validator->fails ()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        }

        $chkFbisValid = User::where('facebook_id',request('facebook_id'))->first();
        if(!empty($chkFbisValid))
        {
            $user = Auth::loginUsingId($chkFbisValid->id);
            $token =  $user->createToken($user->firstname)->accessToken;
            $user = User::find($user->id);
            $user->device_type = request('device_type');
            $user->fcm_token = request('fcm_token');
            $user->save();
            return response()->json(['token' => $token,'data'=>$user,'success'=>'1','message'=>'User login successfully.'], $this->successStatus);
        }
        else{
            $chkEmail = User::where('email',request('email'))->first();
            if(!empty($chkEmail))
            {
                $user = User::find($chkEmail->id);
                $user->facebook_id = request('facebook_id');
                $user->status = 1;
                $user->save();
                $token =  $user->createToken($user->firstname)->accessToken;
                return response()->json(['token' => $token,'data'=>$user,'success'=>'1','message'=>'User login successfully.'], $this->successStatus);
            }
            $chkFb = User::where('facebook_id',request('facebook_id'))->count();
            if($chkFb > 0)
            {
                return response()->json(['success'=>'0','message'=>'Facebook account already used!.'], $this->successStatus);
            }else
            {
                $user = new User();
                $user->firstname = request('firstname');
                $user->email = request('email');
                $user->facebook_id = request('facebook_id');
                $user->device_type = request('device_type');
                $user->fcm_token = request('fcm_token');
                $user->refer_code = strtoupper(substr(request('firstname'), 0, 3).bin2hex(random_bytes(1)).rand(100,999));;
                $user->gender = '-';
                $user->status = 0;
                $user->save();
                $user->attachRole(3);
            }
            
            $token =  $user->createToken($user->firstname)->accessToken;
            return response()->json(['token' => $token,'data'=>$user,'success'=>'1','message'=>'User login successfully.'], $this->successStatus);
        }
    }
    public function SendOTPFacebookUser(Request $request)
    {
        \LogActivity::addToLog('Send otp for facebook user','send_otp_facebook_user',request('device_type'));
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'email' => 'required',
            'mobile_no' => 'required|numeric|digits:10|unique:users,mobile_no'
        ]);

        if ($validator->fails()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);            
        }
        $user = User::find(request('user_id'));
        $user->gender = request('gender');
        $user->email = request('email');
        $user->save();

        $otp = rand(1000, 9999);
        $token = Str::random(25);
        $SmsVerification = new SmsVerification();
        $SmsVerification->code = $otp;
        $SmsVerification->token = $token;
        $SmsVerification->mobile_no = $request->mobile_no;
        $SmsVerification->save();
        $this->SendOtp(array('mobile_no'=>$request->mobile_no,'otp'=>$otp));
        return response()->json(['success'=>'1','message'=>'OTP Send successfully.','token'=>$token], $this->successStatus);
    }

    public function OtpVerificationFacebookUser(Request $request) {
         \LogActivity::addToLog('Send otp verification for facebook user','otp_verification_facebook_user',request('device_type'));
        $rules = array (
            'user_id' => 'required|exists:users,id',
            'mobile_no' => 'required|numeric|digits:10|unique:users',
            'otp_number' => 'required|numeric|digits:4',
            'token' => 'required',
        );
        $validator = Validator::make ( Input::all (), $rules );
        if ($validator->fails ()) {
           return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        } else {
            $verifycode = SmsVerification::where('mobile_no', $request['mobile_no'])->where('code',$request['otp_number'])->where('token',$request['token'])->delete();
            if($verifycode == 1){
                $user = User::find(request('user_id'));
                $user->status = 1;
                $user->mobile_no = request('mobile_no');
                $user->save();
                $token =  $user->createToken($user->firstname)->accessToken;
                return response()->json(['token' => $token,'data'=>$user,'success'=>'1','message'=>'User login successfully.'], $this->successStatus);
            }
            else
            {
                return response()->json(['success'=>'0','message'=>'OTP not matched.'], $this->successStatus);
            }
        }
    }

    public function tokenRefresh(){
        \LogActivity::addToLog('User token refresh','token_refresh',request('device_type'));
        $rules = array (
            'user_id' => 'required|numeric'
        );
        $validator = Validator::make ( Input::all (), $rules );

        if ($validator->fails ()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        }
        $user = User::find(request('user_id'));
        $token =  $user->createToken($user->firstname)->accessToken;
       return response()->json(['token' => $token,'success'=>'1','message'=>'Token refresh successfully.'], $this->successStatus);
        
    }
    public function fcmTokenRefresh(){
        \LogActivity::addToLog('User fcm token refresh','fcm_token_refresh',request('device_type'));
        $rules = array (
            'user_id' => 'required|numeric',
            'fcm_token' => 'required'
        );
        $validator = Validator::make ( Input::all (), $rules );

        if ($validator->fails ()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        }
        $user = User::find(request('user_id'));
        $user->device_type = request('device_type');
        $user->fcm_token = request('fcm_token');
        $user->save();
        $token =  $user->createToken($user->firstname)->accessToken;
       return response()->json(['fcm_token' => request('fcm_token'),'success'=>'1','message'=>'Token refresh successfully.'], $this->successStatus);
        
    }
    public function AddDailyLoginPoint(){
        \LogActivity::addToLog('User daily login point','add_dailylogin_point',request('device_type'));
        $rules = array (
            'user_id' => 'required|numeric'
        );
        $validator = Validator::make ( Input::all (), $rules );

        if ($validator->fails ()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        }
        $user = User::find(request('user_id'));
        $DailyLoginPoint = \CommonHelper::updateUserLeaderboardPoint($user->id,'daily_login');
        return response()->json(['dailyloginpoint'=>$DailyLoginPoint,'success'=>'1','message'=>'User point added successfully.'], $this->successStatus);
        
    }


    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function ValidateUser(Request $request)
    {
        \LogActivity::addToLog('Check user validation','validateuser',request('device_type'));
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'email' => 'required|string|email|max:255',
            'password' => 'required|min:6',
            'mobile_no' => 'required|numeric|digits:10|unique:users',
            'gender' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);            
        }
        $chkEmail = User::where('email',request('email'))->where('status','1')->count();
        if($chkEmail > 0)
        {
            return response()->json(['success'=>'1','message'=>'The email has already beed taken.'], $this->successStatus);
        }
        $otp = rand(1000, 9999);
        $token = Str::random(25);
        $SmsVerification = new SmsVerification();
        $SmsVerification->code = $otp;
        $SmsVerification->token = $token;
        $SmsVerification->mobile_no = $request->mobile_no;
        $SmsVerification->save();
        $this->SendOtp(array('mobile_no'=>$request->mobile_no,'otp'=>$otp));

        // $input = $request->all();
        // $input['password'] = bcrypt($input['password']);
        // $user = User::create($input);
        // $success['token'] =  $user->createToken('MyApp')->accessToken;
        // $success['name'] =  $user->name;


        return response()->json(['success'=>'1','message'=>'OTP Send successfully.','token'=>$token], $this->successStatus);
    }


    //  varification of user otp
    public function OtpVerification(Request $request) {
        $rules = array (
            'mobile_no' => 'required|numeric|digits:10|unique:users',
            'otp_number' => 'required|numeric|digits:4',
            'token' => 'required',
        );
        $validator = Validator::make ( Input::all (), $rules );
        if ($validator->fails ()) {
            return Response::json(array('success'=>0 ,'message' => $validator->getMessageBag()->toArray()));
        } else {
            // $verifycode = 1;
            $verifycode = SmsVerification::where('mobile_no', $request['mobile_no'])->where('code',$request['otp_number'])->where('token',$request['token'])->delete();
            if($verifycode == 1){
                return Response::json(array('success'=>1));
            }
            else
            {
                return Response::json(array('success'=>0,'message'=>array("0"=>'OTP not matched.')));
            }
        }
    }
    public function ResendOTP(Request $request) {
        $rules = array (
            'mobile_no' => 'required|numeric|digits:10|unique:users'
        );
        $validator = Validator::make ( Input::all (), $rules );
        if ($validator->fails ()) {
             return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        } else {
            $otp = rand(1000, 9999);
            $token = Str::random(25);
            $SmsVerification = new SmsVerification();
            $SmsVerification->code = $otp;
            $SmsVerification->token = $token;
            $SmsVerification->mobile_no = $request->mobile_no;
            $SmsVerification->save();
            $this->SendOtp(array('mobile_no'=>$request->mobile_no,'otp'=>$otp));
            return response()->json(['success'=>'1','message'=>'OTP Send successfully.','token'=>$token], $this->successStatus);
        }
    }

    public function Register(Request $request)
    {
        \LogActivity::addToLog('User register','register',request('device_type'));
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'email' => 'required|string|email|max:255',
            'password' => 'required|min:6',
            'mobile_no' => 'required|numeric|digits:10|unique:users',
            'gender' => 'required',
            'otp_number' => 'required|numeric|digits:4',
            'token' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);         
        }
        $OtpData = new \Illuminate\Http\Request();
        $OtpData->setMethod('POST');
        $OtpData->request->add(['otp_number' => $request->otp_number]);
        $OtpData->request->add(['token' => $request->token]);
        $OtpData->request->add(['mobile_no' => $request->mobile_no]);
        $OtpVerification =  $this->OtpVerification($OtpData);
        
        if($OtpVerification->getData()->success == 0){
            return response()->json(['success'=>'0','message'=>'OTP not matched.'], $this->successStatus);
             // return response()->json(['success'=>'0','message'=>@$OtpVerification->getData()->message], $this->successStatus);
        }


        $firstname = request('firstname');
        $lastname = request('lastname');
        $email = request('email');
        $password = request('password');
        $mobile_no = request('mobile_no');
        $character_name = request('character_name');
        $gender = request('gender');
        $referral_code = request('referral_code');

        $chkEmail = User::where('email',request('email'))->where('status','0')->first();
        if(!empty($chkEmail))
        {
            $user =  User::find($chkEmail->id);
            $user->firstname = $firstname;
            $user->lastname = $lastname;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->mobile_no = $mobile_no;
            $user->gender = $gender;
            $user->status = 1;
            $user->character_name = $character_name;
            $user->device_type = request('device_type');
            $user->fcm_token = request('fcm_token');
            $user->refer_code = strtoupper(substr($firstname, 0, 3).bin2hex(random_bytes(1)).rand(100,999));
            $user->reg_date = date('Y-m-d H:i:s');
            $user->save();
            $user_id = $user->id;
        }
        else
        {
            // ADD IN USERS TABLE
            $user = new User();
            $user->firstname = $firstname;
            $user->lastname = $lastname;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->mobile_no = $mobile_no;
            $user->gender = $gender;
            $user->status = 1;
            $user->character_name = $character_name;
            $user->device_type = request('device_type');
            $user->fcm_token = request('fcm_token');
            $user->refer_code = strtoupper(substr($firstname, 0, 3).bin2hex(random_bytes(1)).rand(100,999));
            $user->reg_date = date('Y-m-d H:i:s');
            $user->save();
            $user_id = $user->id;
            $user->attachRole(3);
        }
        
        \CommonHelper::addReferUserBonus($user_id,$referral_code);
        $data = array(
            'receiver_name' => $firstname.' '.$lastname,
            'message_content' => 'Welcome to GamerzByte India',
            'email' => $email,
            'sub' => "Welcome to GamerzByte India"
        );
        Mail::send('emails.welcome', $data, function($m) use ($data) {
            $m->from('support@gamersbyte.in', 'GamerzByte ');
            $m->to($data['email']);
            $m->subject($data['sub']);
        });
        $token = $user->createToken($user->firstname)->accessToken;
        return response()->json(['token'=>$token,'data'=>User::find($user_id),'success'=>'1','message'=>'Register successfully.'], $this->successStatus);
    }

    // send otp using curl
    public function SendOtp($request)
    {
        $result = '';
        $ch = curl_init();

        $user = "sanchitsoftware";

        $pass = "Sanchit@123";

        $receipientno = $request['mobile_no']; 

        $senderID="GAMERZ"; 

        $msgtxt = "Your OTP is: ".$request['otp']." ,GamerzByte India."; 

        $url="http://smsjust.com/blank/sms/user/urlsms.php?username=".urlencode($user)."&pass=".urlencode($pass)."&senderid=".urlencode($senderID)."&dest_mobileno=".urlencode($receipientno)."&message=".urlencode($msgtxt)."&response=Y";
        curl_setopt($ch,CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result= curl_exec($ch);
        curl_close($ch);
        $SmsLogs = new SmsLogs();
        $SmsLogs->mobile_no = $request['mobile_no'];
        $SmsLogs->message = "Your OTP is: ".$request['otp']." ,GamerzByte India."; 
        $SmsLogs->response = $result;
        $SmsLogs->save();
    }

    public function UpdateUserProfile(Request $request)
    {
        \LogActivity::addToLog('User profile update','updateuserprofile',request('device_type'));
        
        $rules = array (
            'user_id' => 'required'
            ,'firstname' => 'required'
            ,'email' => 'required|string|email|max:255|unique:users,email,'.request('user_id')
            ,'gender' => 'required'
        );
        if(!empty(request('password')))
        {   
            $rules['password'] = 'required|min:6';
        }
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);         
        }

        $firstname = request('firstname');
        $lastname = request('lastname');
        $email = request('email');
        $character_name = request('character_name');
        $gender = request('gender');
        $paymentupi = request('paymentupi');
        $country = request('country');
        $state = request('state');
        $city = request('city');
        $area = request('area');
        $password = request('password');

        $user = User::find(request('user_id'));
        if(empty($user))
        {
            return response()->json(['success'=>'0','message'=>'User not found.'], $this->successStatus);
        }
        $user->firstname = $firstname;
        $user->lastname = $lastname;
        $user->email = $email;
        $user->character_name = $character_name;
        $user->gender = $gender;
        $user->paymentupi = $paymentupi;
        $user->country = $country;
        $user->state = $state;
        $user->city = $city;
        $user->area = $area;
        if(!empty(request('password')))
        {   
            $user->password = Hash::make($password);
        }
        $user->save();
        
        return response()->json(['data'=>$user,'success'=>'1','message'=>'Profile update successfully.'], $this->successStatus);
    }

    public function getStates(Request $request)
    {
        \LogActivity::addToLog('Get all states','getstates',request('device_type'));

        $StateList = State::all();
        return response()->json(['data'=>$StateList,'success'=>'1','message'=>'States list successfully.'], $this->successStatus);
    }

    public function getCities(Request $request)
    {
        \LogActivity::addToLog('Get all cities','getcities',request('device_type'));
        $rules = array(
            'state' => 'required'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);         
        }
        else {

            $state = $request['state'];
            $CityList = City::where('state_id',$state)->get();
            
            return response()->json(['data'=>$CityList,'success'=>'1','message'=>'City list successfully.'], $this->successStatus);
        }
    }

    public function ForgotPassword(Request $request)
    {
        \LogActivity::addToLog('Get forgot password','forgot_password',request('device_type'));
        $rules = array(
            'email' => 'required|exists:users,email'
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);         
        }
        else {

            $user = User::where('email',request('email'))->whereNotIn("id",['1'])->first();
            if(!isset($user))
            {
                return response()->json(['success'=>'0','message'=>'Email not exist!'], $this->successStatus);
            }
            $chkTime  = User::find($user->id);
            $now = Carbon::now();
            $created_at = Carbon::parse($chkTime->forgot_pass_send);
            $diffMinutes = $created_at->diffInMinutes($now);
            if($diffMinutes <= 20 && !empty($user->forgot_pass_send))
            {
                return response()->json(['success'=>'0','message'=>'New password already send to your mail.'], $this->successStatus);
            }

            $new_password = rand(10000, 99999).chr(rand(97,122));
            $user_update  = User::find($user->id);
            $user_update->password = bcrypt($new_password);
            $user_update->forgot_pass_send = date('Y-m-d H:i:s');
            $user_update->save();
            
            $data = array(
                'receiver_name' => $user->firstname.' '.$user->lastname,
                'message_content' => 'Did you forget your password??',
                'email' => $user->email,
                'password' => $new_password,
                'sub' => "Did you forget your password??"
            );
            Mail::send('emails.forgot_password', $data, function($m) use ($data) {
                $m->from('support@gamersbyte.in', 'GamerzByte ');
                $m->to($data['email']);
                $m->subject($data['sub']);
            });
            
            return response()->json(['success'=>'1','message'=>'New password send to your mail.'], $this->successStatus);
        }
    }
}