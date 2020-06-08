<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class Banner extends Model
{
    protected $table = "banners";
	public $timestamps = true;

	protected $hidden = [
        'created_at', 'updated_at',
    ];

    protected $fillable = [
        'banner_url','banner_image'
    ];
}
