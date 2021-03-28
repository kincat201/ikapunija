<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'companies';
    protected $fillable = [
        'id',
        'name',
        'code',
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

    public function alumni(){
        return $this->hasMany(UserAlumni::class,'company','code');
    }
}
