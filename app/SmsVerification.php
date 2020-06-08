<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class SmsVerification extends Model
{
    protected $table = "sms_verification";
	public $timestamps = true;

    protected $fillable = [
        'mobile_no', 'code','token'
    ];
}