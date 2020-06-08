<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class Notification extends Model
{
    protected $table = "user_notifications";
	public $timestamps = true;

    protected $fillable = [
    ];
    protected $hidden = [
        'is_redirect','updated_at','created_at','notification_text'
    ];
    public function events()
    {
       return $this->belongsTo('App\Event','event_id');
    }
    public function games()
    {
       return $this->belongsTo('App\Game','game_id');
    }
}