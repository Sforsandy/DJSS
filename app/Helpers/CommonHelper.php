<?php


namespace App\Helpers;
use Request;
use App\UserBonusWallet;
use App\UserLeaderboardPoint;
use App\LeaderboardPoint;
use App\PromoCodeUsedHistory;
use App\PaymentTransaction;
use App\User;
use App\BonusRule;
use App\UserDepositedWallet;
use App\UserTransaction;
use App\EventJoinActivity;
use App\UserWonWallet;
use App\Event;
use App\EventJoin;
use App\EventJoinedBonusLog;
use App\Notification;
use Carbon\Carbon;

class CommonHelper
{


    public static function getUserBonusBalance($user_id)
    {
        $UserBonusCredit = UserBonusWallet::where('user_id',$user_id)->where('txn_type','0')->selectRaw("sum(point) as total_points ")->first()->total_points;

        $UserBonusDebit = UserBonusWallet::where('user_id',$user_id)->where('txn_type','1')->selectRaw("sum(point) as total_points ")->first()->total_points;

        return ($UserBonusCredit - $UserBonusDebit);
    }
    public static function generateORDNumber($prifix = 'GMB',$user_id)
    {
        return $prifix.$user_id.rand(100,900).time();
    }
    public static function addReferUserBonus($user_id,$refer_code)
    {   
        $ReferralUserData = User::where('refer_code',$refer_code)->first();
        $addRefereBalance = false;
        if(!empty($ReferralUserData))
        {

            $referby_user_id = $ReferralUserData->id;
            $sign_with_refer_code_amt = @\App\BonusRule::where('rule','sign_with_refer_code')->first()->amount;
            $referrer_earn_amt = @\App\BonusRule::where('rule','referrer_earn')->first()->amount;
            if($sign_with_refer_code_amt)
            {
                \CommonHelper::updateBonusWallet($user_id, $sign_with_refer_code_amt, '0','sign_with_refer_code', NULL, NULL ,$referby_user_id);

                \CommonHelper::addUserTransactions($user_id, $sign_with_refer_code_amt, '0' ,'referrel_bonus' ,'success', 'FRB');
                $addRefereBalance = true;
            }
            if($referrer_earn_amt)
            {
                \CommonHelper::updateBonusWallet($referby_user_id, $referrer_earn_amt, '0','referrer_earn', NULL, NULL ,NULL ,$user_id);
                \CommonHelper::addUserTransactions($referby_user_id, $referrer_earn_amt, '0' ,'referrel_bonus' ,'success', 'FRB');

                $addRefereBalance = true;

            }
            
        }
        //  MODERATOR WON BALANCE
        $user = User::find($ReferralUserData->id);
        if($user->hasRole('moderator'))
        {
            $amount  = '1';
            \CommonHelper::updateWonWallet($ReferralUserData->id, $amount, '0');

            \CommonHelper::addUserTransactions($ReferralUserData->id, $amount, '0' ,'referrel_bonus' ,'success', 'MRB');
        }
        //  MODERATOR WON BALANCE END
        return ($addRefereBalance==true) ? '1' : '0';
    }

    public static function addEventJoinBonus($user_id,$event_id)
    {   
        $userData = User::find($user_id);
        $eventData = User::find($event_id);
        $EventJoinBonus = array();
        $getBonusAmount = @\App\BonusRule::whereIn('rule',['3_paid_event_per_day','1paid_event_consecutive_3day','5paid_event_per_week'])->get();
        $getAllPaidEvents = Event::whereNotNull('fee')->pluck('id')->toArray();
        $three_paid_event_per_day_is_done = UserBonusWallet::where('user_id',$user_id)
        ->where('bonus_type','3_paid_event_per_day')
        ->whereDate('txn_date',Carbon::Now())->count();

        // 1paid_event_consecutive_3day
        $startDate = Carbon::Now()->subDays(2)->toDateString();
        $one_paid_event_consecutive_3day_is_done = UserBonusWallet::where('user_id',$user_id)
        ->where('bonus_type','1paid_event_consecutive_3day')
        ->whereBetween('txn_date',[$startDate,Carbon::Now()])->count();
        
        $getEventsForConsecutive = EventJoin::selectRaw('*,count(*) as total_join ,(CASE WHEN count(*) >= 1 THEN 1 ELSE 0 END) AS is_join,DATE(joined_date) as date')
        ->where('user_id',$user_id)
        ->whereBetween('joined_date',[$startDate,Carbon::Now()])
        ->groupBy('date')->pluck('is_join');
        $getEventsForConsecutiveCount = array_sum($getEventsForConsecutive->toArray());
        // 1paid_event_consecutive_3day
        foreach ($getBonusAmount as $key => $value) {

            if($value->rule == '3_paid_event_per_day')
            {
                $CountJoinedEvent = EventJoin::whereIn('event_id',$getAllPaidEvents)
                ->where('user_id',$user_id)
                ->whereDate('joined_date',Carbon::Now())->pluck('event_id')->toArray();
                if($three_paid_event_per_day_is_done == 0 && count($CountJoinedEvent) == 3)
                {
                    $EventJoinBonus = \CommonHelper::updateBonusWallet($user_id, $value->amount, '0','3_paid_event_per_day');
                    \CommonHelper::addUserTransactions($user_id, $value->amount, '0' ,'event_join_bonus' ,'success', 'EJB');
                }
            }
            if($value->rule == '1paid_event_consecutive_3day')
            {
                if($one_paid_event_consecutive_3day_is_done == 0 && $getEventsForConsecutiveCount == 3 )
                {
                    $EventJoinBonus = \CommonHelper::updateBonusWallet($user_id, $value->amount, '0','1paid_event_consecutive_3day');
                    \CommonHelper::addUserTransactions($user_id, $value->amount, '0' ,'event_join_bonus' ,'success', 'EJB');
                }
            }
        }

        return $EventJoinBonus;

    }

    public static function updateUserLeaderboardPoint($user_id, $point_condition, $event_id = NULL, $game_id = NULL)
    {
        if($user_id == 1)
        {
            return '1'; 
        }
        $point = @\App\LeaderboardPoint::where('point_condition',$point_condition)->first()->point;
        $UserPoints = false;
        if($point)
        {
            if($point_condition == 'daily_login')
            {
                $LeaderboardPoint = UserLeaderboardPoint::where('user_id',$user_id)->whereDate('point_added_date',Carbon::today())->count();
                if($LeaderboardPoint >= 1)
                {
                    return ($UserPoints==true) ? '1' : '0';
                }
            }
            $UserLeaderboardPointAdd = new UserLeaderboardPoint();
            $UserLeaderboardPointAdd->user_id = $user_id;
            $UserLeaderboardPointAdd->point = $point;
            $UserLeaderboardPointAdd->event_id = $event_id;
            $UserLeaderboardPointAdd->game_id = $game_id;
            $UserLeaderboardPointAdd->point_added_date = date("Y-m-d H:i:s");
            $UserPoints = $UserLeaderboardPointAdd->save();
        }
        return ($UserPoints==true) ? '1' : '0';
    }

    public static function addPromoCodeBalance($user_id,$wallet_type,$amount,$promocode_id)
    {   
        $addPromoCodeBalance = false;
        if(!empty($wallet_type) && !empty($amount) )
        {
            if($wallet_type == 3)
            {
                \CommonHelper::updateBonusWallet($user_id, $amount, '0','promo_code');

                \CommonHelper::addUserTransactions($user_id, $amount, '0' ,'promotional_bonus' ,'success', 'BNS');
                $addPromoCodeBalance = true;
            }
            if($wallet_type == 2)
            {
                \CommonHelper::updateWonWallet($user_id, $amount, '0');

                \CommonHelper::addUserTransactions($user_id, $amount, '0' ,'promotional_bonus' ,'success', 'BNS');
                $addPromoCodeBalance = true;
            }
            if($wallet_type == 1)
            {
                \CommonHelper::updateDepositedWallet($user_id, $amount, '0','promo_code');

                \CommonHelper::addUserTransactions($user_id, $amount, '0' ,'promotional_bonus' ,'success', 'BNS');
                $addPromoCodeBalance = true;
            }

            if($addPromoCodeBalance == true)
            {
               $PromoCodeUsedHistory = new PromoCodeUsedHistory();
               $PromoCodeUsedHistory->user_id = $user_id;
               $PromoCodeUsedHistory->amount = $amount;
               $PromoCodeUsedHistory->promocode_id = $promocode_id;
               $PromoCodeUsedHistory->save();
            }
            
        }
        return $addPromoCodeBalance;
    }

    public static function updateDepositedWallet($user_id, $amount, $txn_type, $transaction_id = NULL,$event_id = NULL, $game_id = NULL)
    {   
        $depositedWallet = new UserDepositedWallet();
        $depositedWallet->user_id = $user_id;
        $depositedWallet->amount = $amount;
        $depositedWallet->txn_type = $txn_type;
        $depositedWallet->txn_date = date("Y-m-d H:i:s");;
        $depositedWallet->event_id = $event_id;
        $depositedWallet->game_id = $game_id;
        // $depositedWallet->tnx_id = $transaction_id;
        $depositedWallet->save();

        $UserDepositedCredit = UserDepositedWallet::where('user_id',$user_id)->where('txn_type','0')->selectRaw("sum(amount) as total_amounts ")->first()->total_amounts;

        $UserDepositedDebit = UserDepositedWallet::where('user_id',$user_id)->where('txn_type','1')->selectRaw("sum(amount) as total_amounts ")->first()->total_amounts;

        $updateDepositedWallet = User::find($user_id);
        $updateDepositedWallet->deposited_balance = ($UserDepositedCredit - $UserDepositedDebit);
        $updateDepositedWallet->save();

        return $depositedWallet;
    }

    public static function updateWonWallet($user_id, $amount, $txn_type, $event_id = NULL, $game_id = NULL)
    {   
        $wonWallet = new UserWonWallet();
        $wonWallet->user_id = $user_id;
        $wonWallet->amount = $amount;
        $wonWallet->txn_type = $txn_type;
        $wonWallet->txn_date = date("Y-m-d H:i:s");
        $wonWallet->event_id = $event_id;
        $wonWallet->game_id = $game_id;
        $wonWallet->save();

        $UserWonCredit = UserWonWallet::where('user_id',$user_id)->where('txn_type','0')->selectRaw("sum(amount) as total_amounts ")->first()->total_amounts;

        $UserWonDebit = UserWonWallet::where('user_id',$user_id)->where('txn_type','1')->selectRaw("sum(amount) as total_amounts ")->first()->total_amounts;

        $updateWonWallet = User::find($user_id);
        $updateWonWallet->winnings_balance = ($UserWonCredit - $UserWonDebit);
        $updateWonWallet->save();

        return $wonWallet;
    }

    public static function updateBonusWallet($user_id, $amount, $txn_type, $bonus_type, $event_id = NULL, $game_id = NULL ,$referby_user_id = NULL ,$refer_user_id = NULL)
    {   
        $userBonusWallet = new UserBonusWallet();
        $userBonusWallet->user_id = $user_id;
        $userBonusWallet->event_id = $event_id;
        $userBonusWallet->game_id = $game_id;
        $userBonusWallet->referby_user_id = $referby_user_id;
        $userBonusWallet->refer_user_id = $refer_user_id;
        $userBonusWallet->amount = $amount;
        $userBonusWallet->txn_date = date("Y-m-d H:i:s");
        $userBonusWallet->txn_type = $txn_type;
        $userBonusWallet->bonus_type = $bonus_type;
        $userBonusWallet->save();

        $StartDate =  Carbon::now()->subDays(30)->toDateString();
        $EndDate =  Carbon::now()->toDateTimeString();

        $UserBonusCredit = UserBonusWallet::where('user_id',$user_id)->where('txn_type','0')->whereBetween('txn_date',[$StartDate,$EndDate])->selectRaw("sum(amount) as total_amounts ")->first()->total_amounts;

        $UserBonusDebit = UserBonusWallet::where('user_id',$user_id)->where('txn_type','1')->whereBetween('txn_date',[$StartDate,$EndDate])->selectRaw("sum(amount) as total_amounts ")->first()->total_amounts;

        $updateUserBalance = User::find($user_id);
        $updateUserBalance->bonus_balance = ($UserBonusCredit - $UserBonusDebit);
        $updateUserBalance->save();

        return $userBonusWallet;
    }

    public static function addUserTransactions($user_id, $amount, $txn_type ,$txn_title ,$status, $order_prifix = 'GBI',$event_id = NULL, $game_id = NULL)
    {   
        $userTransaction = new UserTransaction();
        $userTransaction->order_id      = $order_prifix.$user_id.rand(100,900).time();;
        $userTransaction->user_id       = $user_id;
        $userTransaction->event_id      = $event_id;
        $userTransaction->game_id       = $game_id;
        $userTransaction->txn_amount    = $amount;
        $userTransaction->txn_date      = date("Y-m-d H:i:s");;
        $userTransaction->txn_title     = $txn_title;
        $userTransaction->txn_type      = $txn_type;
        $userTransaction->status        = $status;
        $userTransaction->save();
        return $userTransaction;
    }

    public static function addEventJoinActivity($user_id, $event_id, $used_bonus_amount ,$used_deposited_amount ,$pay_via_payment_gateway)
    {   

        $eventJoinActivity = new EventJoinActivity();
        $eventJoinActivityUpdate = EventJoinActivity::where('user_id',$user_id)->where('event_id',$event_id)->first();
        if(!empty($eventJoinActivityUpdate))
        {
            $eventJoinActivity = EventJoinActivity::find($eventJoinActivityUpdate->id);
        }
        $eventJoinActivity->user_id      = $user_id;
        $eventJoinActivity->event_id      = $event_id;
        $eventJoinActivity->used_bonus_amount       = $used_bonus_amount;
        $eventJoinActivity->used_deposited_amount    = $used_deposited_amount;
        $eventJoinActivity->pay_via_payment_gateway      = $pay_via_payment_gateway;
        $eventJoinActivity->save();
        return $eventJoinActivity;
    }

    public static function addUserNotification($NotificationData)
    {   
        $user_id = $NotificationData['user_id'];
        $userData = User::find($user_id);
        $registration_ids = array($userData->fcm_token);
        if(!empty($userData->fcm_token))
        {
            $url = 'https://fcm.googleapis.com/fcm/send';
            $fields = array(
                'registration_ids' => array($userData->fcm_token),
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
            // curl_exec($ch);
            $result = curl_exec($ch);
            // print_r($result);
            // die;
            // if ($result === FALSE) {
            //     die('Oops! FCM Send Error: ' . curl_error($ch));
            // }
            curl_close($ch);
        }
        // ucfirst($userData->firstname).' '.
        $userNotification = new Notification();
        $userNotification->user_id              = $user_id;
        $userNotification->event_id             = $NotificationData['event_id'];
        $userNotification->is_read              = '0';
        $userNotification->is_redirect          = $NotificationData['is_redirect'];
        $userNotification->send_date            = date('Y-m-d H:i:s');
        $userNotification->notification_title   = $NotificationData['notification_title'];
        $userNotification->notification_desc    = $NotificationData['notification_desc'];
        $userNotification->notification_type    = $NotificationData['notification_type'];
        $userNotification->save();
        return $userNotification;
    }
    // public static function addUserNotificationTest($NotificationData)
    // {   
    //     $registration_ids = array($NotificationData['fcm_token']);

    //     if(!empty($NotificationData['fcm_token'])
    //     {
    //         $url = 'https://fcm.googleapis.com/fcm/send';
    //         $fields = array(
    //             'registration_ids' => $registration_ids,
    //             'data' => $NotificationData,
    //         );
    //         $headers = array(
    //             'Authorization:key=' . config('app.FCM_SERVER_KEY'),
    //             'Content-Type: application/json'
    //         );
    //         $ch = curl_init();
    //         curl_setopt($ch, CURLOPT_URL, $url);
    //         curl_setopt($ch, CURLOPT_POST, true);
    //         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // add
    //         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    //         $result = curl_exec($ch);
    //         curl_close($ch);
    //         return $result;
    //     }
    // }
}