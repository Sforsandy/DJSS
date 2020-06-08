<?php


namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Notification;
use Illuminate\Support\Facades\Auth;
use Validator;
use Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class NotificationController extends Controller
{


    public $successStatus = 200;


    public function getNotifications(Request $request)
    {
        \LogActivity::addToLog('Get Notifications','get_notifications',request('device_type'));
        $rules = array(
        	'user_id' => 'required|exists:users,id',
        );


        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails ()) {
        	return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        }
        else {
        	$pageno = (request('page') == '') ? 0 : request('page');
        	$no_of_records_per_page = 10;
        	$offset = ($pageno-1) * $no_of_records_per_page;
        	$notificationQry = Notification::where('user_id',request('user_id'));
        	$notificationQry->orderBy('send_date', 'DESC');
        	if(request('page') > 0)
        	{
        		$notificationQry->skip($offset)->take($no_of_records_per_page);
        	}
        	$NotificationData  = $notificationQry->get();
        }
        return response()->json(['success'=>'1','message'=>'Notification listed successfully.','data'=>$NotificationData,'total_record'=>$NotificationData->count(),'current_page'=>(int) $pageno], $this->successStatus);
    }


}