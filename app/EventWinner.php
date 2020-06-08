<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class EventWinner extends Model
{
    protected $table = "event_winners";
	public $timestamps = true;

    
    public function events()
    {
       return $this->belongsTo('App\Event','event_id');
    }
    public function users()
    {
       return $this->belongsTo('App\User','user_id');
    }
    public function winnerpositions()
    {
       return $this->belongsTo('App\WinnerPosition','winner_position');
    }
}