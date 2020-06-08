<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class WithdrawalRequest extends Model
{
    protected $table = "user_withdrawal_requests";
	public $timestamps = true;

    protected $fillable = [
        'user_id'
    ];

    public function users()
    {
       return $this->belongsTo('App\User','user_id');
    }
}