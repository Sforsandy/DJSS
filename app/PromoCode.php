<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $table = "promo_codes";
	public $timestamps = true;

    protected $fillable = [
        'promocode',
        'used_per_user',
        'credit_wallat_type',
        'expire_date'
    ];
}
