<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class EventJoinActivity extends Model
{
    protected $table = "event_join_activities";
	public $timestamps = true;

    protected $fillable = [
        'user_id'
    ];

    public function users()
    {
       return $this->belongsTo('App\User','user_id');
    }
}