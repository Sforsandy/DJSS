<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class Event extends Model
{
    protected $table = "events";
	public $timestamps = true;

    protected $fillable = [
        'event_name'
        ,'event_description'
        ,'event_type'
        ,'event_format'
        ,'game'
        ,'capacity'
        ,'fee'
        ,'schedule_date'
        ,'schedule_time'
        ,'created_by'
        ,'access_details'
        ,'stream_url'
        ,'status'
        ,'total_prize'
        ,'winner_prize'
        ,'runner_up1_prize'
        ,'runner_up2_prize'
    ];

    // public function EventType()
    // {
    //     // return $this->belongsToMany(EventType::class,'event_type');
    //     // return $this->hasManyThrough(EventType::class, EventFormat::class,Game::class);
    // }
// 
    public function event_types()
    {
       return $this->belongsTo('App\EventType','event_type');
       // return $this->hasMany('App\EventType','id');
    }
    public function event_formats()
    {
       return $this->belongsTo('App\EventFormat','event_format');
    }
    public function creaters()
    {
       return $this->belongsTo('App\User','created_by');
    }

    // public function event_type()
    // {
    //     return $this->belongsTo(EventType::class);
    // }

    // public function event_format()
    // {
    //     return $this->belongsTo(EventFormat::class);
    // }

    public function games()
    {
        return $this->belongsTo(Game::class,'game');
    }
}