<?php


namespace App\Http\Controllers;

use Auth;
use Mail;
use Session;
use App\User;
use Socialite;
use Exception;


class FacebookController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleFacebookCallback()
    {
        try {
            $FbUserData = Socialite::driver('facebook')->user();
            
            
            // ADD IN USERS TABLE
            $user = new User();
            $user->firstname = $FbUserData->getName();
            $user->email = $FbUserData->getEmail();
            $user->facebook_id = $FbUserData->getId();
            $user->gender = '-';
            $user->status = 0;
            $user->reg_date = date('Y-m-d H:i:s');
            
            $data = User::where('facebook_id',$FbUserData->getId())->get();
            
            if($data->count() > 0)
            {
                if($data[0]->status == 0)
                {
                    return redirect()->route('verifymobile')->with('user',$data[0]->id);
                    // , ['user' => $data[0]]);
                }
                
                Auth::loginUsingId($data[0]->id);
                $reqUrl = session('req-url');
                $DailyLoginPoint = \CommonHelper::updateUserLeaderboardPoint($data[0]->id,'daily_login');
                if(!empty($reqUrl))
                {
                    return redirect($reqUrl);
                }
                $user = Auth::user();
                
                return redirect()->route('events');
            }else
            {
                $user->refer_code = strtoupper(substr($FbUserData->getName(), 0, 3).bin2hex(random_bytes(1)).rand(100,999));
                $user->save();
                $user->attachRole(3);

                $user_id = $user->id;
                $user->attachRole(3);

                $data = array(
                'receiver_name' => $FbUserData->getName(),
                'message_content' => 'Welcome to GamerzByte India',
                'email' => $FbUserData->getEmail(),
                'sub' => "Welcome to GamerzByte India"
                );
                Mail::send('emails.welcome', $data, function($m) use ($data) {
                    $m->from('support@gamersbyte.in', 'GamerzByte ');
                    $m->to($data['email']);
                    $m->subject($data['sub']);
                });
                return redirect()->route('verifymobile')->with('user',$user->id);
                // return view('verifymobile', compact('user'));
                // Auth::loginUsingId($user->id);
            }


        } catch (Exception $e) {


            return redirect('login');
        }
    }

    public function showVerifyMobileForm()
    {
        $value = Session::get('user');
        if(empty($value))
        {
            return redirect('login');
        }
        return view('auth.verification');
    }
}