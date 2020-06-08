<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
	protected $fillable = [
		'api_name','device_type','post_string','subject', 'url', 'method', 'ip', 'agent', 'user_id'
	];
}
