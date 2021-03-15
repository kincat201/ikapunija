<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\UtilityDB;

class Struktur extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'struktur_organisasi';
    protected $fillable = [
        'id',
        'nama',
        'nama_jabatan',
        'foto',
        'level',
        'fb',
        'ig',
        'twitter',
        'linkedin',
        'email',
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
        'created_at' => 'datetime',
    ];

    public function level()
    { return $this->belongsTo(StrukturLevel::class, 'level'); }

    public function scopeExclude($query, $columns = []) 
    {
        $arrTable = $this->fillable;
        $utility = new UtilityDB();
        $utility = $utility->excludeTable($query, $arrTable, $columns);
        return $utility;
    }
}
