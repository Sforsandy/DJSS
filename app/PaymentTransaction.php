<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class PaymentTransaction extends Model
{
    protected $table = "payment_transactions";
	public $timestamps = true;

    protected $fillable = [
    ];
    protected $hidden = [
        'gateway_name', 'bank_txn_id','bank_name','check_sum_hash','full_response','created_at','updated_at','payment_mode'
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