<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class EventJoinedBonusLog extends Model
{
    protected $table = "event_joined_bonus_log";
	public $timestamps = true;

    
    public function events()
    {
       return $this->belongsTo('App\Event','event_id');
    }
    public function users()
    {
       return $this->belongsTo('App\User','user_id');
    }
    
}