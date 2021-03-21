<?php

namespace App;

use App\Util\Constant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\UtilityDB;

class Jurusan extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'jurusan';
    protected $fillable = [
        'id',
        'nama_jurusan',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at', 'is_active'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function prodi()
    { return $this->hasMany(Prodi::class, 'id_jurusan'); }

    public function userAlumni()
    { return $this->hasMany(UserAlumni::class, 'jurusan_id'); }

    public function scopeExclude($query, $columns = []) 
    {
        $arrTable = $this->fillable;
        $utility = new UtilityDB();
        $utility = $utility->excludeTable($query, $arrTable, $columns);
        return $utility;
    }

    public function scopeActive($query)
    {
        return $query->where('jurusan.is_active', Constant::COMMON_YES);
    }
}
