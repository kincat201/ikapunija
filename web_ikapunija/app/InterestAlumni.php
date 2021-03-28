<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InterestAlumni extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'interest_alumni';
    protected $fillable = [
        'id',
        'interest_id',
        'alumni_id',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

    ];

    public function alumni(){
        return $this->belongsTo(UserAlumni::class,'alumni_id','id');
    }

    public function interest(){
        return $this->belongsTo(Interest::class,'interest_id','id');
    }
}
