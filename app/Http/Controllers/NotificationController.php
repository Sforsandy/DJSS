<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\Event;
use App\EventJoin;
use App\Notification;
use Auth;
use Session;
use Validator;
use Toastr;
use Response;
use DataTables;
use Illuminate\Support\Facades\Input;
class NotificationController extends Controller {
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
        $events = Event::all();
    	return view('notifications/index',compact('events'));
    }
    public function sendNotification(Request $request)
    {
        $rules = array(
            'notification_title' => 'required',
            'message' => 'required'
        );


        $validator = Validator::make(Input::all(), $rules);

        if($validator->fails()) 
            return Response::json(array('success' => 0,'message' => $validator->getMessageBag()->toArray()));
        else {
            $event_id  = request('event_id');
            $notification_title  = request('notification_title');
            $notification_desc  = request('message');

            $registration_ids = array();
            if(empty($event_id))
            {
                $Users = User::whereNotNull('fcm_token')->get();
                $registration_ids = @array_column($Users->toArray(), 'fcm_token');
            }else
            {
                $getUserids = EventJoin::where('event_id',$event_id)->plunk('user_id');
                $Users = User::whereNotNull('fcm_token')->whereIn('id',$getUserids)->get();
                $registration_ids = @array_column($Users->toArray(), 'fcm_token');
            }
            $notificationSave = array();
            foreach ($Users as $key => $value) {
                $notificationSave[$key]['user_id']              = $value->id;
                $notificationSave[$key]['event_id']             = '';
                $notificationSave[$key]['is_read']              = '0';
                $notificationSave[$key]['is_redirect']          = '0';
                $notificationSave[$key]['send_date']            = date('Y-m-d H:i:s');
                $notificationSave[$key]['notification_title']   = $notification_title;
                $notificationSave[$key]['notification_desc']    = $notification_desc;
                $notificationSave[$key]['notification_type']    = 'admin';
            }
            Notification::insert($notificationSave);
            $NotificationData = array();
            $NotificationData['notification_title'] = $notification_title;
            $NotificationData['notification_desc']  = $notification_desc;
            $NotificationData['notification_type']  = 'admin';
            $NotificationData['event_id'] = '';
            $NotificationData['user_id']  = '';
            $NotificationData['is_redirect'] = '1';
            if(!empty($registration_ids))
            {
                $url = 'https://fcm.googleapis.com/fcm/send';
                $fields = array(
                    'registration_ids' => $registration_ids,
                    'data' => $NotificationData,
                );
                $headers = array(
                    'Authorization:key=' . config('app.FCM_SERVER_KEY'),
                    'Content-Type: application/json'
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // add
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
                $result = curl_exec($ch);
                curl_close($ch);
            }

            return Response::json(array('success' => 1,'message'=>'Notification send successfully.'));
        }
    }
}