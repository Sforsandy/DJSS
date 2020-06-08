<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class EventFormat extends Model
{
    protected $table = "event_formats";
	public $timestamps = true;

    protected $fillable = [
        'event_format_name'
    ];

    // public function events()
    // {
    //     return $this->hasMany(Event::class);
    // }
}