<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'provinces';
    protected $fillable = [
        'id',
        'country_id',
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
}
