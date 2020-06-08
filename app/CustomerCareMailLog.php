<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class CustomerCareMailLog extends Model
{
    protected $table = "customer_care_mail_log";
	public $timestamps = true;

    protected $fillable = [
        'user_id'
    ];
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    public function users()
    {
       return $this->belongsTo('App\User','user_id');
    }
}