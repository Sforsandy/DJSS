<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class Game extends Model
{
    protected $table = "games";
	public $timestamps = true;

	protected $hidden = [
        'created_at', 'updated_at',
    ];

    protected $fillable = [
        'game_name','status'
    ];

    // public function events()
    // {
    //     return $this->hasMany(Event::class);
    // }
}