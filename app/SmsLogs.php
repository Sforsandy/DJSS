<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class SmsLogs extends Model
{
    protected $table = "sms_logs";
	public $timestamps = true;

    protected $fillable = [
        'mobile_no', 'message','response'
    ];
}