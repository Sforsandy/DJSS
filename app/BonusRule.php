<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class BonusRule extends Model
{
    protected $table = "bonus_rules";
	public $timestamps = true;

    protected $fillable = [
        'name','rule','amount'
    ];
    
}