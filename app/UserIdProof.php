<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Eloquent;

class UserIdProof extends Model
{
    protected $table = "user_id_proofs";
	public $timestamps = true;

	protected $hidden = [
        'created_at', 'updated_at',
    ];

    protected $fillable = [
        'user_id','proof_type','id_proof_image'
    ];

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
}