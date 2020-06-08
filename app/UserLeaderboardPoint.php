<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLeaderboardPoint extends Model
{
    protected $table = "user_leaderboard_points";
	public $timestamps = true;

    protected $fillable = [
        'user_id',
        'event_id',
        'game_id',
        'point',
        'point_added_date'
    ];
    public function user()
    {
       return $this->belongsTo('App\User','user_id');
    }
    public function game()
    {
       return $this->belongsTo('App\Game','game_id');
    }
    public function event()
    {
       return $this->belongsTo('App\Event','event_id');
    }
}
