<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class EventType extends Model
{
    protected $table = "event_types";
	public $timestamps = true;

    protected $fillable = [
        'event_type_name'
    ];

    // public function events()
    // {
    //     return $this->hasMany(Event::class);
    // }
}