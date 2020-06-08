<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class EventRule extends Model
{
    protected $table = "event_rules";
	public $timestamps = true;

    protected $fillable = [
        'rules','event_id'
    ];
    protected $hidden = [
        'created_at', 'updated_at',
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