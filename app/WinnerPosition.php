<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class WinnerPosition extends Model
{
    protected $table = "winner_positions";
	public $timestamps = true;

    protected $fillable = [
        'position'
    ];
}