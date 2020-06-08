<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\User;
use App\PayTm;
use App\PaymentTransaction;
use App\UserTransaction;
use App\EventJoinActivity;
use App\EventJoin;
use App\Event;
use Validator;
use Response;
use Illuminate\Support\Facades\Input;
class PaymentController extends Controller {


    public function paytmPaymentRedirect(Request $request)
    {
        $transaction_id = request('id');
        $transactionData = PaymentTransaction::find($transaction_id);
        $userData = User::find($transactionData->user_id);
        $checkSum = "";
        $paramList = array();
        $paramList["MID"] = config('app.PAYTM_MERCHANT_MID');
        $paramList["ORDER_ID"] = $transactionData->order_id;
        $paramList["CUST_ID"] = $userData->id;
        $paramList["INDUSTRY_TYPE_ID"] = 'Retail';
        $paramList["CHANNEL_ID"] = 'WEB';
        $paramList["TXN_AMOUNT"] = $transactionData->txn_amount;
        $paramList["WEBSITE"] = config('app.PAYTM_MERCHANT_WEBSITE');

        $paramList["CALLBACK_URL"] = config('app.url')."app/payresponse/".$transaction_id;
        $paramList["MSISDN"] = $userData->mobile_no;
        $paramList["EMAIL"] = $userData->email;
        $paramList["VERIFIED_BY"] = "EMAIL";
        $paramList["IS_USER_VERIFIED"] = "YES";
        $PayTm = new PayTm();
        $paramList["checkSum"] = $PayTm->getChecksumFromArray($paramList,config('app.PAYTM_MERCHANT_KEY'));
        $paramList = (object)$paramList;

        return view('paytm/redirect',compact('paramList'));
    }

    public function PaymentResponse(Request $request)
    {
        $paytmChecksum = "";
        $paramList = array();
        $isValidChecksum = "FALSE";
        $paramList = $_POST;
        $uri_path = $_SERVER['REQUEST_URI']; 
        $uri_parts = explode('/', $uri_path);
        $transaction_id = end($uri_parts);
        $paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; 
        $PayTm = new PayTm();
        $isValidChecksum = $PayTm->verifychecksum_e($paramList,config('app.PAYTM_MERCHANT_KEY'), $paytmChecksum);
        $return_array["IS_CHECKSUM_VALID"] = $isValidChecksum ? "Y" : "N";

        if($isValidChecksum)
        {   
            $transactionData = PaymentTransaction::find($transaction_id);
            $transactionData->txn_id           =   isset($paramList['TXNID']) ? $paramList['TXNID'] : '';
            $transactionData->payment_mode     =   isset($paramList['PAYMENTMODE']) ? $paramList['PAYMENTMODE'] : '';
            $transactionData->txn_amount       =   isset($paramList['TXNAMOUNT']) ? $paramList['TXNAMOUNT'] : '';
            $transactionData->currency         =   isset($paramList['CURRENCY']) ? $paramList['CURRENCY'] : '';
            $transactionData->txn_date         =   isset($paramList['TXNDATE']) ? $paramList['TXNDATE'] : date('Y-m-d H:i:s');
            $transactionData->status           =   isset($paramList['STATUS']) ? $paramList['STATUS'] : '';
            $transactionData->resp_code        =   isset($paramList['RESPCODE']) ? $paramList['RESPCODE'] : '';
            $transactionData->resp_msg         =   isset($paramList['RESPMSG']) ? $paramList['RESPMSG'] : '';
            $transactionData->gateway_name     =   isset($paramList['GATEWAYNAME']) ? $paramList['GATEWAYNAME'] : '';
            $transactionData->bank_txn_id      =   isset($paramList['BANKTXNID']) ? $paramList['BANKTXNID'] : '';
            $transactionData->bank_name        =   isset($paramList['BANKNAME']) ? $paramList['BANKNAME'] : '';
            $transactionData->check_sum_hash   =   isset($paramList['CHECKSUMHASH']) ? $paramList['CHECKSUMHASH'] : '';
            $transactionData->full_response    =   json_encode($paramList);
            $transactionData->save();
            if($paramList['STATUS'] == 'TXN_SUCCESS')
            {
                if($transactionData->event_id > 0){
                    $eventData = Event::find($transactionData->event_id);
                    $eventjoin = new EventJoin();
                    $eventjoin->user_id = $transactionData->user_id;
                    $eventjoin->event_id = $transactionData->event_id;
                    $eventjoin->game_id = $eventData->game;
                    $eventjoin->joined_date = date("Y-m-d H:i:s");
                    $eventjoin->save(); 
                    $EventJoinPoint = \CommonHelper::updateUserLeaderboardPoint($transactionData->user_id,'join_event',$transactionData->event_id,$eventData->game);
                    $DepositedWallet = \CommonHelper::updateDepositedWallet($transactionData->user_id, $transactionData->txn_amount,'0',$transaction_id,$transactionData->event_id,$eventData->game);

                    $DepositedWallet = \CommonHelper::updateDepositedWallet($transactionData->user_id, $transactionData->txn_amount,'1',$transaction_id,$transactionData->event_id,$eventData->game);
                    
                    $getEventJoinActivity = EventJoinActivity::where('event_id',$transactionData->event_id)->where('user_id',$transactionData->user_id)->first();
                    $total_amt = $getEventJoinActivity->used_bonus_amount + $getEventJoinActivity->used_deposited_amount + $getEventJoinActivity->pay_via_payment_gateway;

                    \CommonHelper::addUserTransactions($transactionData->user_id, $transactionData->txn_amount, '0' ,'balance_credit' ,'success', 'WBC');
                    if($getEventJoinActivity->used_bonus_amount > 0)
                    {
                        $bonusWallet = \CommonHelper::updateBonusWallet($transactionData->user_id, $getEventJoinActivity->used_bonus_amount, '1','event_joined', $transactionData->event_id,$eventData->game);
                    }
                    
                    $userTransaction = new UserTransaction();
                    $userTransaction->order_id      = $paramList['ORDERID'];
                    $userTransaction->user_id       = $transactionData->user_id;
                    $userTransaction->event_id       = $transactionData->event_id;
                    $userTransaction->game_id       = $eventData->game;
                    $userTransaction->txn_amount    = $total_amt;
                    $userTransaction->txn_date      = date("Y-m-d H:i:s");;
                    $userTransaction->txn_title     = 'joined_event';
                    $userTransaction->txn_type      = '1';
                    $userTransaction->status        = 'success';
                    $userTransaction->save();

                    
                    $NotificationData['notification_title'] = 'New event joined.';
                    $NotificationData['notification_desc']  = 'You have joined event.';
                    $NotificationData['notification_type']  = 'event_joined';
                    $NotificationData['event_id'] = $transactionData->event_id;
                    $NotificationData['user_id']  = $transactionData->user_id;
                    $NotificationData['is_redirect'] = '1';
                    \CommonHelper::addUserNotification($NotificationData);
                    if($eventData->fee >= '5')
                    {
                        $EventJoinBonus = \CommonHelper::addEventJoinBonus($transactionData->user_id,$transactionData->event_id);
                    }
                    // \CommonHelper::addUserTransactions($transactionData->user_id, $total_amt, '1' ,'joined_event' ,'success', 'JGE',$transactionData->event_id,$eventData->game);
                }
                else{
                    $DepositedWallet = \CommonHelper::updateDepositedWallet($transactionData->user_id, $transactionData->txn_amount,'0',$transaction_id);
                    $userTransaction = new UserTransaction();
                    $userTransaction->order_id      = $paramList['ORDERID'];
                    $userTransaction->user_id       = $transactionData->user_id;
                    $userTransaction->txn_amount    = $paramList['TXNAMOUNT'];
                    $userTransaction->txn_date      = date("Y-m-d H:i:s");;
                    $userTransaction->txn_title     = 'balance_credit';
                    $userTransaction->txn_type      = '0';
                    $userTransaction->status        = 'success';
                    $userTransaction->save();

                    $NotificationData['notification_title'] = 'Balance credited.';
                    $NotificationData['notification_desc']  = 'Balance credited in your wallet.';
                    $NotificationData['notification_type']  = 'wallet_balance_updated';
                    $NotificationData['user_id']  = $transactionData->user_id;
                    $NotificationData['event_id']  = NULL;
                    $NotificationData['is_redirect'] = '0';
                    \CommonHelper::addUserNotification($NotificationData);
                    // \CommonHelper::addUserTransactions($transactionData->user_id, $transactionData->txn_amount, '0' ,'balance_credit' ,'success', 'WBC');
                }
            }
            ?>
            <script type="text/javascript">   
                Android.redirectTransactionSummary("<?=(int)$transaction_id?>");
            </script>
            <?php
            
        }else
        {
            echo 'Something went wrong.';
        }
    }


}