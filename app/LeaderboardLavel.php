<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaderboardLavel extends Model
{
    protected $table = "leaderboard_lavels";
	public $timestamps = true;

    protected $fillable = [
        'start_point',
        'end_point',
        'lavel'    ];
}
