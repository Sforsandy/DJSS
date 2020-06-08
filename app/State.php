<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class State extends Model
{
    protected $table = "states";
	public $timestamps = true;

	protected $hidden = [
        'created_at', 'updated_at',
    ];

    protected $fillable = [
        'state_name'
    ];
}