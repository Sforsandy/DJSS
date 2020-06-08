<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\SmsLogs;
use App\Event;
use App\EventJoin;
use App\Game;
use Auth;
use Session;
use Validator;
use Toastr;
use Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
class MessageController extends Controller {
    

    public function index()
    {
        $games = Game::all();
        return view('messages/index',compact('games'));
    }
    public function SendMessages(Request $request)
    {
        $rules = array(
            'message' => 'required|string|max:160'
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {
          $game_id = $request->game;
          if($game_id > 0)
          {
            $getUserFromGame = EventJoin::where('game_id',$game_id)->get()->pluck('user_id');
            $getAllUsers = User::whereIn('id',$getUserFromGame)->get();
          }
          else
          {
            $getAllUsers = User::all();
          }
          foreach ($getAllUsers as $key => $value) {
              $result = '';
              $ch = curl_init();

              $user = "sanchitsoftware";

              $pass = "Sanchit@123";

              $receipientno = $value->mobile_no; 

              $senderID="GAMERZ"; 

              $msgtxt = $request->message; 

              $url="http://smsjust.com/blank/sms/user/urlsms.php?username=".urlencode($user)."&pass=".urlencode($pass)."&senderid=".urlencode($senderID)."&dest_mobileno=".urlencode($receipientno)."&message=".urlencode($msgtxt)."&response=Y";
              curl_setopt($ch,CURLOPT_URL, $url);

              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

              $result= curl_exec($ch);
              curl_close($ch);
              $SmsLogs = new SmsLogs();
              $SmsLogs->mobile_no = $receipientno;
              $SmsLogs->message = $msgtxt; 
              $SmsLogs->response = $result;
              $SmsLogs->save();   
          }
         return Response::json(array('success' => 1,'message'=>'Message send successfully.','data' => []));
        }
    }

    public function SendEventMessages(Request $request)
    {
        $rules = array(
            'message' => 'required|string|max:160'
            ,'event_id' => 'required|numeric'
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {
          $getAllUsers = EventJoin::with('users')->where('event_id',$request->event_id)->get();

          foreach ($getAllUsers as $key => $value) {

                $result = '';
                $ch = curl_init();

                $user = "sanchitsoftware";

                $pass = "Sanchit@123";

                $receipientno = $value->users->mobile_no;

                $senderID="GAMERZ"; 

                $msgtxt = $request->message; 

                $url="http://smsjust.com/blank/sms/user/urlsms.php?username=".urlencode($user)."&pass=".urlencode($pass)."&senderid=".urlencode($senderID)."&dest_mobileno=".urlencode($receipientno)."&message=".urlencode($msgtxt)."&response=Y";
                curl_setopt($ch,CURLOPT_URL, $url);

                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                $result= curl_exec($ch);
                curl_close($ch);
                $SmsLogs = new SmsLogs();
                $SmsLogs->mobile_no = $receipientno;
                $SmsLogs->message = $msgtxt; 
                $SmsLogs->response = $result;
                $SmsLogs->save();

          }
         return Response::json(array('success' => 1,'message'=>'Message send successfully.','data' => []));
        }
    }

    public function getMessagesReport(Request $request)
    {
        $rules = array(
            'startdate' => 'required|date'
            ,'enddate' => 'required|date'
        );
        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {
          $startdate = $request['startdate'];
          $enddate = $request['enddate'];
          $EndDate =  Carbon::createFromFormat('Y-m-d', $enddate)->addDays(1)->toDateString();
            $SmsLogs = SmsLogs::whereBetween('created_at',[$startdate,$EndDate])->get()->count();
        }

        return Response::json(array('success' => 1,'message'=>'SmsLogs list successfully.','data' => $SmsLogs));
    }



}