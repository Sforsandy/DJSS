<?php


namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Game;
use App\Banner;
use App\UserBonusWallet;
use App\EventJoin;
use App\EventWinner;
use App\UserLeaderboardPoint;
use App\LeaderboardLavel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Validator;
use Response;
use Mail;
use Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class GamesController extends Controller
{


    public $successStatus = 200;


    public function getGamesAndBanner(Request $request)
    {
        \LogActivity::addToLog('Get games and banner list','getgamesandbanner',request('device_type'));

        $banner = Banner::all();

       	$games = Game::where('status','1')->get();
       	$StateList = \App\State::all();
        return response()->json(['success'=>'1','message'=>'Games and Banner listed successfully.','data'=>$games,'banner'=>$banner,'states'=>$StateList], $this->successStatus);
    }

    public function getLavel($lavels = array(),$user_point)
    {
        $lavel = 'Beginner';
        foreach ($lavels as $key => $value) {if(($user_point >= $value->start_point) && ($user_point <= $value->end_point))
            {
                $lavel = $value->lavel;
            }
        }
        return $lavel;
    }

    public function getGamersInfo(Request $request)
    {
        \LogActivity::addToLog('Get gamers info','getgamesinfo',request('device_type'));
        $rules = array (
            'user_id' => 'required'
        );
        $validator = Validator::make ( Input::all (), $rules );

        if ($validator->fails ()) {
            return response()->json(['success'=>'0','message'=>$validator->errors()->all()[0]], $this->successStatus);
        }
        $banner = Banner::all();
        $LeaderboardLavels = LeaderboardLavel::all();
        $games = Game::selectRaw("id,game_name")->get();
        $GamePlayed = EventJoin::selectRaw(' count(*) as game_played_count,game_id' )
        ->where('user_id',request('user_id'))
        ->groupBy('game_id')->get();
        $GamePlayedArr = array();
        foreach ($GamePlayed as $PlayedKey => $PlayedValue) {
            $GamePlayedArr[$PlayedValue['game_id']] = $PlayedValue['game_played_count'];
        }

        $GamePoints = UserLeaderboardPoint::selectRaw(' SUM(point) as game_point_count,game_id' )
        ->where('user_id',request('user_id'))
        ->groupBy('game_id')->get();
        $GamePointsArr = array();
        foreach ($GamePoints as $PointKey => $PointValue) {
            $GamePointsArr[$PointValue['game_id']] = $PointValue['game_point_count'];
        }

        $GameWon = EventWinner::selectRaw(' count(*) as game_won_count,game_id' )
        ->where('user_id',request('user_id'))
        ->groupBy('game_id')->get();
        $GameWonArr = array();
        foreach ($GameWon as $WonKey => $WonValue) {
            $GameWonArr[$WonValue['game_id']] = $WonValue['game_won_count'];
        }
        foreach ($games as $key => $value) {
            $games[$key]['played'] = (int) @$GamePlayedArr[$value['id']];
        	$games[$key]['won'] = (int) @$GameWonArr[$value['id']];
        	$games[$key]['points'] = (int) @$GamePointsArr[$value['id']];
        }
        $TotalPoints = UserLeaderboardPoint::selectRaw(' SUM(point) as total_points' )
        ->where('user_id',request('user_id'))->first();
        $lavel = $this->getLavel($LeaderboardLavels,$TotalPoints->total_points);
       	return response()->json(['success'=>'1','message'=>'Gamers data showed successfully.','total_points'=>(int)$TotalPoints->total_points,'level'=>$lavel,'games'=>$games], $this->successStatus);
    }

}