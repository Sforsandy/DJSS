<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class UserTransaction extends Model
{
    protected $table = "user_transactions";
	public $timestamps = true;

    protected $fillable = [
    ];
    protected $hidden = [
        'created_at','updated_at'
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