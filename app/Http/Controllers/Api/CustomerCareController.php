<?php


namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\CustomerCareMailLog;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Validator;
use Response;
use Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class CustomerCareController extends Controller
{


    public $successStatus = 200;

    public function sendMessagetoCare(Request $request)
    {
        \LogActivity::addToLog('Send message to customer care','send_message_to_care',request('device_type'));
        $rules = array(
            'user_id' => 'required',
            'subject' => 'required',
            'message' => 'required'
        );


        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails ()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        }
        $userData = User::find(request('user_id'));

        $chkIsAlready = CustomerCareMailLog::where('user_id',request('user_id'))->orderBy('user_id','DESC')->first();
        if(!empty($chkIsAlready)){

            $now = Carbon::now();
            $send_time = Carbon::parse($chkIsAlready->send_time);
            $diffMinutes = $send_time->diffInMinutes($now);
            if($diffMinutes <= 60)
            {
                return response()->json(['success'=>'1','message'=>'Message already send successfully.'], $this->successStatus);
            }

        }
        $customerCareMailLog = new CustomerCareMailLog();
        $customerCareMailLog->user_id = request('user_id');
        $customerCareMailLog->send_time = Carbon::now();
        $customerCareMailLog->save();
        $data = array(
            'receiver_name' => $userData->firstname.' '.$userData->lastname,
            'message_content' => 'Thanks for contact our team.',
            'email' => $userData->email,
            'sub' => "GamerzByte india customer care"
        );
        Mail::send('emails.support', $data, function($m) use ($data) {
            $m->from('support@gamersbyte.in', 'GamerzByte ');
            $m->to($data['email']);
            $m->subject($data['sub']);
        });
        return response()->json(['success'=>'1','message'=>'Message send successfully.'], $this->successStatus);
    }


}