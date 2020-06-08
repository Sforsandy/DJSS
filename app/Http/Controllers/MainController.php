<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\User;
use App\SmsVerification;
use App\SmsLogs;
use App\Role;
use App\UserBonusWallet;
use Hash;
use Auth;
use Redirect;
use Session;
use Validator;
use Toastr;
use Response;
use Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
class MainController extends Controller {

	// show login view
	public function showLoginForm()
    {
        return view('auth.login');
	}
	// show registration view
	public function showRegisterForm()
    {
        return view('auth.register');
	}
	// show forgot_password view
	public function showForgotPasswordForm()
    {
        return view('auth.forgot_password');
	}
	
	// login using mobile_no and password
	public function login(Request $request) {
		\LogActivity::addToLog('User Login','login','2');
		$rules = array (
			'mobile_no' => 'required|numeric|digits:10',
			'password' => 'required' 
		);
		$validator = Validator::make ( Input::all (), $rules );

		if ($validator->fails ()) {
			Toastr::error('Missing required fields.', $title = null, $options = []);
			return Redirect::back ()->withErrors ( $validator, 'login' )->withInput ();
		} else {
				Auth::logout();
			if (Auth::attempt(['mobile_no' => $request->mobile_no, 'password' => $request->password ])) {
				Toastr::success('Login Successfully.', $title = null, $options = []);
				$reqUrl = session('req-url');
				$user = Auth::user();
				$DailyLoginPoint = \CommonHelper::updateUserLeaderboardPoint($user->id,'daily_login');

				if(!empty($reqUrl))
				{
					session()->forget('req-url');
					return redirect($reqUrl);
				}
				// Session::flush();
				if(Auth::user()->hasRole('admin') == '1')
				{
					return redirect()->intended('product');
				}
				else
				{
					return redirect()->intended('product');
				}
				
			} else {
				Toastr::error('Invalid Credentials', $title = null, $options = []);
					// Session::flash ( 'message', "Invalid Credentials , Please try again." );
				return Redirect::back ();
			}

		}

	}

	// validate user and then send otp user mobile number
	public function ValidateUser(Request $request) {
		$rules = array (
			'firstname' => 'required',
			'email' => 'required|string|email|max:255|unique:users',
			'password' => 'required|min:6|confirmed',
			'mobile_no' => 'required|numeric|digits:10|unique:users',
			'gender' => 'required',
			'refer_code' => 'sometimes|exists:users,refer_code',
		);
		$validator = Validator::make ( Input::all (), $rules );
		if ($validator->fails ()) {
			return Response::json(array('success'=>0 ,'message' => $validator->getMessageBag()->toArray()));
		} else {
			$otp = rand(1000, 9999);
			$token = Str::random(25);
			$SmsVerification = new SmsVerification();
			$SmsVerification->code = $otp;
			$SmsVerification->token = $token;
			$SmsVerification->mobile_no = $request->mobile_no;
			$SmsVerification->save();
			$this->SendOtp(array('mobile_no'=>$request->mobile_no,'otp'=>$otp));
			return Response::json(array('success'=>1,'token'=>$token));
		}
	}

	public function SendOTPFacebookUser(Request $request)
	{
		$rules = array (
			'mobile_no' => 'required|numeric|digits:10|unique:users',
		);
		$validator = Validator::make ( Input::all (), $rules );
		if ($validator->fails ()) {
			return Response::json(array('success'=>0 ,'message' => $validator->getMessageBag()->toArray()));
		} else {
			$otp = rand(1000, 9999);
			$token = Str::random(25);
			$SmsVerification = new SmsVerification();
			$SmsVerification->code = $otp;
			$SmsVerification->token = $token;
			$SmsVerification->mobile_no = $request->mobile_no;
			$SmsVerification->save();
			$this->SendOtp(array('mobile_no'=>$request->mobile_no,'otp'=>$otp));
			return Response::json(array('message' =>'OTP send successfully.','success'=>1,'token'=>$token));
		}
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
				return Response::json(array('success'=>0,'message'=>array("wrong_otp"=>array("0"=>'OTP not matched.'))));
			}
		}
	}

	public function VerifyOtpFacebookUser(Request $request) {
		$rules = array (
			'mobile_no' => 'required|numeric|digits:10|unique:users',
			'otp_number' => 'required|numeric|digits:4',
			'token' => 'required',
			'id' => 'required',
		);
		$validator = Validator::make ( Input::all (), $rules );
		if ($validator->fails ()) {
			return Response::json(array('success'=>0 ,'message' => $validator->getMessageBag()->toArray()));
		} else {
			// $verifycode = 1;
			$verifycode = SmsVerification::where('mobile_no', $request['mobile_no'])->where('code',$request['otp_number'])->where('token',$request['token'])->delete();
			if($verifycode == 1){
				$user = User::find($request['id']);
				$user->mobile_no = $request['mobile_no'];
				$user->status = 1;
				$user->save();
				Auth::loginUsingId($user->id);
				$reqUrl = session('req-url');
				session()->forget('req-url');
				if(empty($reqUrl))
				{
					$reqUrl = '';
				}
				$user_id = $user->id;
				$user->attachRole(3);
				return Response::json(array('success'=>1,'message'=>'OTP verify successfully','reqUrl'=>$reqUrl));
			}
			else
			{
				return Response::json(array('success'=>0,'message'=>array("wrong_otp"=>array("0"=>'OTP not matched.'))));
			}
		}
	}

	//  register new users
	public function Register(Request $request) {
		$rules = array (
			'firstname' => 'required',
			'email' => 'required|string|email|max:255|unique:users',
			'password' => 'required|min:6|confirmed',
			'mobile_no' => 'required|numeric|digits:10|unique:users',
			'gender' => 'required',
			'otp_number' => 'required|numeric|digits:4',
			'token' => 'required',
			'refer_code' => 'sometimes|exists:users,refer_code',
		);
		$validator = Validator::make ( Input::all (), $rules );
		if ($validator->fails ()) {
			return Response::json(array('message' => $validator->getMessageBag()->toArray()));
		} else {
			$OtpData = new \Illuminate\Http\Request();
			$OtpData->setMethod('POST');
			$OtpData->request->add(['otp_number' => $request->otp_number]);
			$OtpData->request->add(['token' => $request->token]);
			$OtpData->request->add(['mobile_no' => $request->mobile_no]);
			$OtpVerification =	$this->OtpVerification($OtpData);
			// print_r($OtpVerification->getData()->message->wrong_otp);
			if($OtpVerification->getData()->success == 0){
				return Response::json(array('success'=>0,'message' => @$OtpVerification->getData()->message->wrong_otp));
			}

			$firstname = $request['firstname'];
			$lastname = $request['lastname'];
			$email = $request['email'];
			$password = $request['password'];
			$mobile_no = $request['mobile_no'];
			$character_name = $request['character_name'];
			$gender = $request['gender'];
			$refer_code = $request['refer_code'];

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
			$user->refer_code = strtoupper(substr($firstname, 0, 3).bin2hex(random_bytes(1)).rand(100,999));;
			$user->reg_date = date('Y-m-d H:i:s');
			$user->save();
			$user_id = $user->id;
			Auth::loginUsingId($user->id);

			$user->attachRole(3);
			\CommonHelper::addReferUserBonus($user_id,$refer_code);
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

			// ADD IN USERS TABLE END
			$reqUrl = session('req-url');
			session()->forget('req-url');
			if(empty($reqUrl))
			{
				$reqUrl = '';
			}
			return Response::json(array('success'=>1,'message'=>'Registration successful.','reqUrl'=>$reqUrl));

		}
	}

	//  register new users
	public function ForgotPassword(Request $request) {
		$rules = array (
			'email' => 'required|string|email|max:255|'
		);
		$validator = Validator::make ( Input::all (), $rules );
		if ($validator->fails ()) {
			return Response::json(array('success'=>0,'errors' => $validator->getMessageBag()->toArray()));
		} else {
			$user = User::where('email',$request->email)->whereNotIn("id",['1'])->first();
			if(!isset($user))
			{
				return Response::json(array('success'=>0,'errors'=>array("Email not exist"=>array("0"=>'Email not matched.'))));
			}
			$chkTime  = User::find($user->id);
			$now = Carbon::now();
			$created_at = Carbon::parse($chkTime->forgot_pass_send);
			$diffMinutes = $created_at->diffInMinutes($now);
			if($diffMinutes <= 20)
			{
				return Response::json(array('success'=>0,'errors'=>array("Email send"=>array("0"=>'New password already send to your mail.'))));
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

			// ADD IN USERS TABLE END

			return Response::json(array('success'=>1,'data' => []));

		}
	}
	// logout to the system
	public function Logout(Request $request) {
		Auth::logout();
		Session::flush();
		return Redirect::back ();
	}

	public function EmailVerification(Request $request) {
		$rules = array (
			'email' => 'required|string',
			'id' => 'required|string',
			'time' => 'required|string'
		);
		$validator = Validator::make ( Input::all (), $rules );
		if ($validator->fails ()) {
			return Response::json(array('success'=>0,'errors' => $validator->getMessageBag()->toArray()));
		} else {
			$email = base64_decode($request->email);
			$id = base64_decode($request->id);
			$time = base64_decode($request->time);
			$user = User::where('email',$email)->where('id',$id)->whereNotIn("id",['1'])->first();
			if(!isset($user))
			{
				// return Response::json(array('success'=>0,'errors'=>array("Email not exist"=>array("0"=>'Email not matched.'))));
				Toastr::error('Something went wrong.', $title = null, $options = []);
				return redirect('login');
			}
			$chkTime  = User::find($id);
			$now = Carbon::now();
			$created_at = Carbon::parse($time);
			$diffMinutes = $created_at->diffInMinutes($now);
			if($diffMinutes >= 20)
			{
				// return 'Verification mail expire.please send again!!!';
				Toastr::error('Verification mail expire.please send again!!!.', $title = null, $options = []);
				return redirect('login');
			}

			$user_update  = User::find($id);
            $user_update->email = $email;
            $user_update->email_verified = '1';
            $user_update->email_verified_at = date('Y-m-d H:i:s');
            $user_update->save();
			
			Toastr::success('Email verification successfully.', $title = null, $options = []);
			return redirect('login');

		}
	}
}