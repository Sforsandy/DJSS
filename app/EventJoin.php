<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class EventJoin extends Model
{
    protected $table = "event_joined_users";
	public $timestamps = true;

    protected $fillable = [
        'user_id'
    ];
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function users()
    {
       return $this->belongsTo('App\User','user_id');
    }
    public function events()
    {
       return $this->belongsTo('App\Event','event_id');
    }
}