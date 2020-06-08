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

class PromoCodeController extends Controller
{


    public $successStatus = 200;


    public function applyPromocode(Request $request)
    {
        \LogActivity::addToLog('apply promocode','applyPromocode',request('device_type'));

        $rules = array (
            'user_id' => 'required',
            'promocode' => 'required|exists:promo_codes,promocode',
        );
        $validator = Validator::make ( Input::all (), $rules );

        if ($validator->fails ()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        }
        $PromoCodeData = PromoCode::where('promocode',request('promocode'))
        ->whereDate('expire_date', '>=', Carbon::now())
        ->first();
        if(!empty($PromoCodeData->user_id))
        {
            if($PromoCodeData->user_id != request('user_id'))
            {
                return response()->json(['success'=>'0','message'=>'The selected promocode is invalid.'], $this->successStatus);
            }
        }
        if(empty($PromoCodeData))
        {
        	return response()->json(['success'=>'0','message'=>'Promocode is expired.'], $this->successStatus);
        }
        $PromoCodeIsUsed = PromoCodeUsedHistory::where('promocode_id',$PromoCodeData->id)
        ->where('user_id',request('user_id'))
        ->count();
        if($PromoCodeIsUsed >= $PromoCodeData->used_per_user)
        {
            return response()->json(['success'=>'0','message'=>'Promocode apply limit exceeded.'], $this->successStatus);
        }
        $PromoCodeApplyData = \CommonHelper::addPromoCodeBalance(request('user_id'),$PromoCodeData->credit_wallat_type,$PromoCodeData->amount,$PromoCodeData->id);
       	return response()->json(['success'=>'1','message'=>'Promocode apply successfully.'], $this->successStatus);
    }


}