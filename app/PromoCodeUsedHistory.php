<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromoCodeUsedHistory extends Model
{
    protected $table = "promo_code_used_history";
	public $timestamps = true;

    protected $fillable = [
        'promocode_id',
        'user_id',
        'amount'
    ];
    public function promocode()
    {
       return $this->belongsTo('App\PromoCode','promocode_id');
    }
    public function user()
    {
       return $this->belongsTo('App\User','user_id');
    }
}
