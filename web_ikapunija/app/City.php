<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'cities';
    protected $fillable = [
        'id',
        'country_id',
        'province_id',
        'name',
    ];
    public $incrementing = false;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function country(){
        return $this->belongsTo(Country::class,'country_id','id');
    }

    public function province(){
        return $this->belongsTo(Province::class,'province_id','id');
    }

    public function alumni(){
        return $this->hasMany(UserAlumni::class,'city_id','id');
    }
}
