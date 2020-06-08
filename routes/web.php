<?php
use App\Role;
use App\User;
use App\Game;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear-cache', function() {

    $configCache = Artisan::call('config:cache');
    $clearCache = Artisan::call('cache:clear');
    $clearCache = Artisan::call('view:clear');
    // return what you want
});
Route::get('/migrate', function() {

    Artisan::call('migrate');
});
Route::get('/seed', function() {

    // Artisan::call('db:seed');
});
// OtherPages
Route::get('pagename', 'OtherPagesController@PageNameFunction');
// OtherPages End

Route::get('auth/facebook', 'FacebookController@redirectToFacebook');
Route::get('auth/facebook/callback', 'FacebookController@handleFacebookCallback');

Route::get('/product', ['as' => 'login', 'uses' => 'ProductController@index']); 

Route::get('/login', ['as' => 'login', 'uses' => 'MainController@showLoginForm']);
Route::get('/qrcode', ['as' => 'qrcode', 'uses' => 'EventController@QRcode']);
Route::post('/login', ['as' => 'login', 'uses' => 'MainController@Login']); 
Route::post('/prduct/store', ['as' => 'product.store', 'uses' => 'ProductController@store']); 

Route::get('/register', ['as' => 'register', 'uses' => 'MainController@showRegisterForm']);
Route::post('/auth/register', ['as' => 'auth.register', 'uses' => 'MainController@Register']);
Route::post('/auth/validate', ['as' => 'auth.validate', 'uses' => 'MainController@ValidateUser']);
Route::post('/auth/otp_verification', ['as' => 'auth.otp_verification', 'uses' => 'MainController@OtpVerification']);
Route::get('/verifymobile', ['as' => 'verifymobile', 'uses' => 'FacebookController@showVerifyMobileForm']);
Route::post('/verifymobile/send_otp', ['as' => 'verifymobile.send_otp', 'uses' => 'MainController@SendOTPFacebookUser']);
Route::post('/verifymobile/verify_otp', ['as' => 'verifymobile.verify_otp', 'uses' => 'MainController@VerifyOtpFacebookUser']);

Route::get('/logout', ['as' => 'logout', 'uses' => 'MainController@Logout']);
Route::get('/forgot-password', ['as' => 'forgot-password', 'uses' => 'MainController@showForgotPasswordForm']);
Route::post('/auth/forgotpassword', ['as' => 'auth.forgotpassword', 'uses' => 'MainController@ForgotPassword']);
// EMAIL VARIFICATION
Route::get('/email-verification/{email}/{id}/{time}', function ($email,$id,$time) {
    return view('auth.email_verification')->with(array('email'=>$email,'id'=>$id,'time'=>$time) );
});
Route::post('email-verification', ['as' => 'email-verification', 'uses' => 'MainController@EmailVerification']);
// EMAIL VARIFICATION END

Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);
Route::get('/home', ['as' => 'home', 'uses' => 'EventController@showEvents']);

// ADMIN
Route::group(['middleware' => ['auth','role:admin']], function() {
	// ,'middleware' => ['role:admin']s
Route::get('/event-type', ['as' => 'event-type', 'uses' => 'EventTypeController@index']);
Route::get('/event-type/data', ['as' => 'event-type.data', 'uses' => 'EventTypeController@data']);
Route::get('/event-type/create', ['as' => 'event-type.create', 'uses' => 'EventTypeController@create']);
Route::get('/event-type/edit/{id}', ['as' => 'event-type.edit', 'uses' => 'EventTypeController@edit']);
Route::post('/event-type/store', ['as' => 'event-type.store', 'uses' => 'EventTypeController@store']);
Route::post('/event-type/update', ['as' => 'event-type.update', 'uses' => 'EventTypeController@update']);
Route::post('/event-type/destroy', ['as' => 'event-type.destroy', 'uses' => 'EventTypeController@destroy']);


Route::get('/event-format', ['as' => 'event-format', 'uses' => 'EventFormatController@index']);
Route::get('/event-format/data', ['as' => 'event-format.data', 'uses' => 'EventFormatController@data']);
Route::get('/event-format/create', ['as' => 'event-format.create', 'uses' => 'EventFormatController@create']);
Route::get('/event-format/edit/{id}', ['as' => 'event-format.edit', 'uses' => 'EventFormatController@edit']);
Route::post('/event-format/store', ['as' => 'event-format.store', 'uses' => 'EventFormatController@store']);
Route::post('/event-format/update', ['as' => 'event-format.update', 'uses' => 'EventFormatController@update']);
Route::post('/event-format/destroy', ['as' => 'event-format.destroy', 'uses' => 'EventFormatController@destroy']);

Route::get('/game', ['as' => 'game', 'uses' => 'GameController@index']);
Route::get('/game/data', ['as' => 'game.data', 'uses' => 'GameController@data']);
Route::get('/game/create', ['as' => 'game.create', 'uses' => 'GameController@create']);
Route::get('/game/edit/{id}', ['as' => 'game.edit', 'uses' => 'GameController@edit']);
Route::post('/game/store', ['as' => 'game.store', 'uses' => 'GameController@store']);
Route::post('/game/update', ['as' => 'game.update', 'uses' => 'GameController@update']);
Route::post('/game/destroy', ['as' => 'game.destroy', 'uses' => 'GameController@destroy']);

Route::get('/users', ['as' => 'users', 'uses' => 'UserController@index']);
Route::post('/user/data', ['as' => 'user.data', 'uses' => 'UserController@data']);
Route::get('/user/create', ['as' => 'user.create', 'uses' => 'UserController@create']);
Route::get('/user/edit/{id}', ['as' => 'user.edit', 'uses' => 'UserController@edit']);
Route::post('/user/store', ['as' => 'user.store', 'uses' => 'UserController@store']);
Route::post('/user/update', ['as' => 'user.update', 'uses' => 'UserController@update']);
Route::post('/user/destroy', ['as' => 'user.destroy', 'uses' => 'UserController@destroy']);
Route::post('/user/changerole', ['as' => 'user.changerole', 'uses' => 'UserController@ChangeRole']);
Route::post('/user/changepassword', ['as' => 'user.changepassword', 'uses' => 'UserController@ChangePassword']);
Route::post('/user/transaction', ['as' => 'user.transaction', 'uses' => 'UserController@UserTransaction']);
Route::post('/event/adduser', ['as' => 'event.adduser', 'uses' => 'ManageEventController@AddUserToEvent']);

Route::get('/messages', ['as' => 'messages', 'uses' => 'MessageController@index']);
Route::post('/messages/send', ['as' => 'messages.send', 'uses' => 'MessageController@SendMessages']);
Route::post('/messages/getreport', ['as' => 'messages.getreport', 'uses' => 'MessageController@getMessagesReport']);

Route::get('/winner-position', ['as' => 'winner-position', 'uses' => 'WinnerPositionController@index']);
Route::get('/winner-position/data', ['as' => 'winner-position.data', 'uses' => 'WinnerPositionController@data']);
Route::get('/winner-position/create', ['as' => 'winner-position.create', 'uses' => 'WinnerPositionController@create']);
Route::get('/winner-position/edit/{id}', ['as' => 'winner-position.edit', 'uses' => 'WinnerPositionController@edit']);
Route::post('/winner-position/store', ['as' => 'winner-position.store', 'uses' => 'WinnerPositionController@store']);
Route::post('/winner-position/update', ['as' => 'winner-position.update', 'uses' => 'WinnerPositionController@update']);
Route::post('/winner-position/destroy', ['as' => 'winner-position.destroy', 'uses' => 'WinnerPositionController@destroy']);

Route::get('/leaderboard-point', ['as' => 'leaderboard-point', 'uses' => 'LeaderboardPointController@index']);
Route::get('/leaderboard-point/data', ['as' => 'leaderboard-point.data', 'uses' => 'LeaderboardPointController@data']);
Route::get('/leaderboard-point/create', ['as' => 'leaderboard-point.create', 'uses' => 'LeaderboardPointController@create']);
Route::get('/leaderboard-point/edit/{id}', ['as' => 'leaderboard-point.edit', 'uses' => 'LeaderboardPointController@edit']);
Route::post('/leaderboard-point/store', ['as' => 'leaderboard-point.store', 'uses' => 'LeaderboardPointController@store']);
Route::post('/leaderboard-point/update', ['as' => 'leaderboard-point.update', 'uses' => 'LeaderboardPointController@update']);
Route::post('/leaderboard-point/destroy', ['as' => 'leaderboard-point.destroy', 'uses' => 'LeaderboardPointController@destroy']);

Route::get('/banner', ['as' => 'banner', 'uses' => 'BannerController@index']);
Route::get('/banner/data', ['as' => 'banner.data', 'uses' => 'BannerController@data']);
Route::get('/banner/create', ['as' => 'banner.create', 'uses' => 'BannerController@create']);
Route::get('/banner/edit/{id}', ['as' => 'banner.edit', 'uses' => 'BannerController@edit']);
Route::post('/banner/store', ['as' => 'banner.store', 'uses' => 'BannerController@store']);
Route::post('/banner/update', ['as' => 'banner.update', 'uses' => 'BannerController@update']);
Route::post('/banner/destroy', ['as' => 'banner.destroy', 'uses' => 'BannerController@destroy']);

Route::get('/promo-code', ['as' => 'promo-code', 'uses' => 'PromoCodeController@index']);
Route::get('/promo-code/data', ['as' => 'promo-code.data', 'uses' => 'PromoCodeController@data']);
Route::get('/promo-code/create', ['as' => 'promo-code.create', 'uses' => 'PromoCodeController@create']);
Route::get('/promo-code/edit/{id}', ['as' => 'promo-code.edit', 'uses' => 'PromoCodeController@edit']);
Route::post('/promo-code/store', ['as' => 'promo-code.store', 'uses' => 'PromoCodeController@store']);
Route::post('/promo-code/update', ['as' => 'promo-code.update', 'uses' => 'PromoCodeController@update']);
Route::post('/promo-code/destroy', ['as' => 'promo-code.destroy', 'uses' => 'PromoCodeController@destroy']);


Route::get('/bonus-rule', ['as' => 'bonus-rule', 'uses' => 'BonusRuleController@index']);
Route::get('/bonus-rule/data', ['as' => 'bonus-rule.data', 'uses' => 'BonusRuleController@data']);
Route::get('/bonus-rule/create', ['as' => 'bonus-rule.create', 'uses' => 'BonusRuleController@create']);
Route::get('/bonus-rule/edit/{id}', ['as' => 'bonus-rule.edit', 'uses' => 'BonusRuleController@edit']);
Route::post('/bonus-rule/store', ['as' => 'bonus-rule.store', 'uses' => 'BonusRuleController@store']);
Route::post('/bonus-rule/update', ['as' => 'bonus-rule.update', 'uses' => 'BonusRuleController@update']);
Route::post('/bonus-rule/destroy', ['as' => 'bonus-rule.destroy', 'uses' => 'BonusRuleController@destroy']);

Route::get('/leaderboard-lavel', ['as' => 'leaderboard-lavel', 'uses' => 'LeaderboardLavelController@index']);
Route::get('/leaderboard-lavel/data', ['as' => 'leaderboard-lavel.data', 'uses' => 'LeaderboardLavelController@data']);
Route::get('/leaderboard-lavel/create', ['as' => 'leaderboard-lavel.create', 'uses' => 'LeaderboardLavelController@create']);
Route::get('/leaderboard-lavel/edit/{id}', ['as' => 'leaderboard-lavel.edit', 'uses' => 'LeaderboardLavelController@edit']);
Route::post('/leaderboard-lavel/store', ['as' => 'leaderboard-lavel.store', 'uses' => 'LeaderboardLavelController@store']);
Route::post('/leaderboard-lavel/update', ['as' => 'leaderboard-lavel.update', 'uses' => 'LeaderboardLavelController@update']);
Route::post('/leaderboard-lavel/destroy', ['as' => 'leaderboard-lavel.destroy', 'uses' => 'LeaderboardLavelController@destroy']);

// REPORT
Route::get('/user-report/{startdate}/{enddate}', ['as' => 'user-report', 'uses' => 'ReportController@UserReport']);
Route::get('/event-report/{startdate}/{enddate}', ['as' => 'user-report', 'uses' => 'ReportController@EventReport']);
// REPORT END

// ACCOUNT
Route::get('id-verification', ['as' => 'id-verification', 'uses' => 'AccountController@IdVerification']);
Route::get('account/getidproof', ['as' => 'account.getidproof', 'uses' => 'AccountController@getIdProof']);
Route::post('account/idproofapproval', ['as' => 'account.idproofapproval', 'uses' => 'AccountController@IdProofApproval']);
Route::get('withdrawal-requests', ['as' => 'withdrawal-requests', 'uses' => 'AccountController@WithdrawalRequests']);
Route::get('account/getwithdrawalrequests', ['as' => 'account.getwithdrawalrequests', 'uses' => 'AccountController@getWithdrawalRequests']);
Route::post('account/changeRequestStatus', ['as' => 'account.changerequeststatus', 'uses' => 'AccountController@changeRequestStatus']);
// ACCOUNT END

Route::get('send-notification', ['as' => 'send-notification', 'uses' => 'NotificationController@index']);
Route::post('notification/send', ['as' => 'notification.send', 'uses' => 'NotificationController@sendNotification']);

});


Route::group(['middleware' => ['auth','role:admin|moderator']], function() {
	Route::get('manage-events', ['as' => 'manage-events', 'uses' => 'ManageEventController@showManageEvents']);
	Route::post('/event/data', ['as' => 'event.data', 'uses' => 'ManageEventController@data']);
	Route::get('/event/create', ['as' => 'event.create', 'uses' => 'ManageEventController@create']);
	Route::post('/event/store', ['as' => 'event.store', 'uses' => 'ManageEventController@store']);
	Route::get('/manage-event/edit/{id}', ['as' => 'manage-event.edit', 'uses' => 'ManageEventController@edit']);
	Route::post('/manage-event/update', ['as' => 'manage-event.update', 'uses' => 'ManageEventController@update']);
	Route::post('/manage-event/destroy', ['as' => 'manage-event.destroy', 'uses' => 'ManageEventController@destroy','middleware' => ['auth','role:admin']]);
	Route::post('/manage-event/getjoineduser', ['as' => 'manage-event.getjoineduser', 'uses' => 'ManageEventController@getEventJoinedUser']);
	Route::post('/eventmessages/send', ['as' => 'eventmessages.send', 'uses' => 'MessageController@SendEventMessages']);


	Route::get('/event-rule', ['as' => 'event-rule', 'uses' => 'EventRuleController@index']);
	Route::get('/event-rule/data', ['as' => 'event-rule.data', 'uses' => 'EventRuleController@data']);
	Route::get('/event-rule/create', ['as' => 'event-rule.create', 'uses' => 'EventRuleController@create']);
	Route::get('/event-rule/edit/{id}', ['as' => 'event-rule.edit', 'uses' => 'EventRuleController@edit']);
	Route::post('/event-rule/store', ['as' => 'event-rule.store', 'uses' => 'EventRuleController@store']);
	Route::post('/event-rule/update', ['as' => 'event-rule.update', 'uses' => 'EventRuleController@update']);
	Route::post('/event-rule/destroy', ['as' => 'event-rule.destroy', 'uses' => 'EventRuleController@destroy']);

	Route::get('/event-winner', ['as' => 'event-winner', 'uses' => 'EventWinnerController@index']);
	Route::get('/event-winner/data', ['as' => 'event-winner.data', 'uses' => 'EventWinnerController@data']);
	Route::get('/event-winner/create', ['as' => 'event-winner.create', 'uses' => 'EventWinnerController@create']);
	Route::get('/event-winner/edit/{id}', ['as' => 'event-winner.edit', 'uses' => 'EventWinnerController@edit']);
	Route::post('/event-winner/store', ['as' => 'event-winner.store', 'uses' => 'EventWinnerController@store']);
	Route::post('/event-winner/update', ['as' => 'event-winner.update', 'uses' => 'EventWinnerController@update']);
	Route::post('/event-winner/destroy', ['as' => 'event-winner.destroy', 'uses' => 'EventWinnerController@destroy']);
	Route::post('/event-winner/geteventuser', ['as' => 'event-winner.geteventuser', 'uses' => 'EventWinnerController@getEventUser']);
	Route::post('/game-events', ['as' => 'game-events', 'uses' => 'EventRuleController@getGameEvents']);
	Route::get('/event-winner/winner-request', ['as' => 'winner-request', 'uses' => 'EventWinnerController@winnerRequestShow']);
	Route::get('/event-winner/getwinnerrequests', ['as' => 'event-winner.getwinnerrequests', 'uses' => 'EventWinnerController@getWinnerRequests']);
	Route::post('/event-winner/winner-request-change-status', ['as' => 'event-winner.winner-request-change-status', 'uses' => 'EventWinnerController@winnerRequestChangeStatus']);
});

Route::get('/myprofile', ['as' => 'user.myprofile', 'uses' => 'UserController@MyProfileView']);
Route::get('/events', ['as' => 'events', 'uses' => 'EventController@showAllEvents']);
Route::get('/event-details', ['as' => 'event-details', 'uses' => 'EventController@showEventDetails']);
Route::post('/getevents', ['as' => 'getevents', 'uses' => 'EventController@getEvents']);
Route::post('/joinevent', ['as' => 'joinevent', 'uses' => 'EventController@JoinEvent']);
Route::post('/joinpaidevent', ['as' => 'joinpaidevent', 'uses' => 'EventController@JoinPaidEvent']);
Route::post('/payresponse/{id}', ['as' => 'payresponse', 'uses' => 'PaymentTransactionController@PaymentResponse']);
Route::get('/paymentstatus/{id}', ['as' => 'paymentstatus', 'uses' => 'PaymentTransactionController@viewPaymentStatus','middleware' => ['auth']]);
Route::post('/user/update_user_profile', ['as' => 'user.update_user_profile', 'uses' => 'UserController@updateUserProfile']);

Route::get('transactions', ['as' => 'transactions', 'uses' => 'PaymentTransactionController@showTransactions']);
Route::post('transaction/data', ['as' => 'transaction.data', 'uses' => 'PaymentTransactionController@data']);

Route::get('user-leaderboard', ['as' => 'user-leaderboard', 'uses' => 'UserLeaderboardPointController@index']);
Route::post('user-leaderboard/data', ['as' => 'user-leaderboard.data', 'uses' => 'UserLeaderboardPointController@data']);

// ACCOUNT
Route::get('myaccount', ['as' => 'myaccount', 'uses' => 'AccountController@index']);
Route::post('account/upload_id_proof', ['as' => 'account.upload_id_proof', 'uses' => 'AccountController@UploadIdProof']);
Route::post('account/update_upi_data', ['as' => 'account.update_upi_data', 'uses' => 'AccountController@UpdateUpiID']);
Route::post('account/email_verification', ['as' => 'account.email_verification', 'uses' => 'AccountController@EmailVerification']);
// ACCOUNT END

// ADMIN END
Route::post('/cities', ['as' => 'cities', 'uses' => 'UserController@getCities','middleware' => ['auth']]);
// CRON JOB 
Route::get('cron_1m_change_event_status', ['as' => 'cron_1m_change_event_status', 'uses' => 'CronController@Cron1mChangeEventStatus']);
// CRON JOB END


Route::get('/', function () {
    return view('frontend/home');
});

Route::get('/index', function () {
    return view('frontend/home');
})->name('index');

Route::get('/about-us', function () {
    return view('frontend/about');
})->name('about-us');

Route::get('/ourteam', function () {
    return view('frontend/team');
})->name('ourteam');

Route::get('/career', function () {
    return view('frontend/career');
})->name('career');

Route::get('/events', function () {
    return view('frontend/events');
})->name('events');

Route::get('/contactus', function () {
    return view('frontend/contactus');
})->name('contactus');

// PAYMENT
Route::get('app/payment/paytm/{id}', ['as' => 'app.payment.paytm', 'uses' => 'PaymentController@paytmPaymentRedirect']);
Route::post('app/payresponse/{id}', ['as' => 'payresponse', 'uses' => 'PaymentController@PaymentResponse']);
// PAYMENT END
