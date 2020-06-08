<?php


namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\PromoCode;
use App\PromoCodeUsedHistory;
use Illuminate\Support\Facades\Auth;
use Validator;
use Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class ConfigController extends Controller
{


    public $successStatus = 200;


    public function getConfigData(Request $request)
    {
        \LogActivity::addToLog('get config data','get_config_data',request('device_type'));

        $data['vesrion'] = "1.3";
        $data['apk_url'] = "https://events.gamerzbyte.com/public/uploads/app.apk";
        $data['force_update'] = "1";
        $data['available_on_play_store'] = "0";

       	return response()->json(['success'=>'1','data'=>$data,'message'=>'success.'], $this->successStatus);
    }


}