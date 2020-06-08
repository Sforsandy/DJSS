<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'Api\UserController@login');
Route::post('login_via_facebook', 'Api\UserController@login_via_facebook');
Route::post('token_refresh', 'Api\UserController@tokenRefresh');
Route::post('register', 'Api\UserController@Register');
Route::post('validateuser', 'Api\UserController@ValidateUser');
Route::post('forgot_password', 'Api\UserController@ForgotPassword');
Route::post('resend_otp', 'Api\UserController@ResendOTP');
Route::post('get_config_data', 'Api\ConfigController@getConfigData');


Route::group(['middleware' => 'auth:api'], function(){
	Route::post('send_otp_facebook_user', 'Api\UserController@SendOTPFacebookUser');
	Route::post('otp_verification_facebook_user', 'Api\UserController@OtpVerificationFacebookUser');
	
	Route::post('details', 'Api\UserController@details');
	Route::post('getgamesandbanner', 'Api\GamesController@getGamesAndBanner');
	Route::post('getgamersinfo', 'Api\GamesController@getGamersInfo');
	Route::post('updateuserprofile', 'Api\UserController@UpdateUserProfile');
	Route::post('getstates', 'Api\UserController@getStates');
	Route::post('getcities', 'Api\UserController@getCities');
	Route::post('add_dailylogin_point', 'Api\UserController@AddDailyLoginPoint');
	Route::post('upload_idproof', 'Api\AccountController@UploadIdProof');
	Route::post('email_verification', 'Api\AccountController@EmailVerification');
	Route::post('update_upiid', 'Api\AccountController@UpdateUpiID');
	
	// ACCOUNT TAB
	Route::post('add_balance', 'Api\AccountController@addBalance');
	Route::post('check_payment_status', 'Api\AccountController@checkPaymentStatus');
	Route::post('getaccountinfo', 'Api\AccountController@getAccountInfo');
	Route::post('get_transactions_list', 'Api\AccountController@getTransactionsList');
	Route::post('add_withdrawal_request', 'Api\AccountController@addWithdrawalRequest');
	Route::post('get_document_image', 'Api\AccountController@getDocumentImage');
	// ACCOUNT TAB END

	// EVENT
	Route::post('getevents', 'Api\EventController@getEvents');
	Route::post('join_event', 'Api\EventController@JoinEvent');
	Route::post('get_event_calculation', 'Api\EventController@getEventAmtCalculation');
	Route::post('get_event_players', 'Api\EventController@getEventPlayers');
	Route::post('get_event_details', 'Api\EventController@getEventDetails');
	Route::post('upload_user_game_screenshot', 'Api\EventController@uploadUserGameScreenShot');
	Route::post('get_event_winnerusers', 'Api\EventController@getEventWinnerUsers');
	// EVENT END
	
	// MY EVENT
	Route::post('get_upcoming_events', 'Api\MyEventController@getUpcomingEvents');
	Route::post('get_past_events', 'Api\MyEventController@getPastEvents');
	// MY EVENT END

	// MORE TAB
	Route::post('get_event_id', 'Api\EventController@getEventId');
	Route::post('get_user_leaderboard', 'Api\UserLeaderboardPointController@getUserLeaderboard');
	Route::post('get_leaderboard_point', 'Api\LeaderboardPointController@getLeaderboardPoint');
	Route::post('apply_promocode', 'Api\PromoCodeController@applyPromocode');
	Route::post('send_message_to_care', 'Api\CustomerCareController@sendMessagetoCare');
	// MORE TAB END

	// NOTIFICATION
	Route::post('get_notifications', 'Api\NotificationController@getNotifications');
	// NOTIFICATION END
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});