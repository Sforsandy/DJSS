<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Eloquent;

class LeaderboardPoint extends Model
{
    protected $table = "leaderboard_points";
	public $timestamps = true;
	protected $hidden = [
        'created_at', 'updated_at',
    ];
    protected $fillable = [
        'point_condition',
        'point',
        'title'
    ];
}
