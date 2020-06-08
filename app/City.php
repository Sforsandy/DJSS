<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class City extends Model
{
    protected $table = "cities";
	public $timestamps = true;

	protected $hidden = [
        'created_at', 'updated_at',
    ];
    
    protected $fillable = [
        'state_id','city_name'
    ];
}