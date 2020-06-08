<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserWonWallet extends Model
{
    protected $table = "user_won_wallet";
	public $timestamps = true;

    protected $fillable = [
        'user_id',
        'event_id',
        'game_id',
        'tnx_id',
        'amount',
        'txn_date',
        'txn_type '
    ];
    public function user()
    {
       return $this->belongsTo('App\User','user_id');
    }
    public function game()
    {
       return $this->belongsTo('App\Game','game_id');
    }
    public function event()
    {
       return $this->belongsTo('App\Event','event_id');
    }
}
