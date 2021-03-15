<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\UtilityDB;

class UserAccess extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'user_logs';
    protected $fillable = [
        'id_user', 
        'email', 
        'token',
        'mode',
        'user_type',
    ];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'status', 
        'active_code', 
        'mode', 
        'created_at',
        'updated_at', 
        'id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function admin()
    { return $this->belongsTo(UserAdmin::class, 'id_user'); }

    public function alumni()
    { return $this->belongsTo(UserAlumni::class, 'id_user'); }

    public function scopeExclude($query, $columns = []) 
    {
        $arrTable = $this->fillable;
        $utility = new UtilityDB();
        $utility = $utility->excludeTable($query, $arrTable, $columns);
        return $utility;
    }
}
