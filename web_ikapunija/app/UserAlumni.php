<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\UtilityDB;
use App\Jurusan;
use App\Prodi;

class UserAlumni extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'user_alumni';
    protected $fillable = [
        'id',
        'password',
        'nama_alumni',
        'email',
        'contact',
        'alamat',
        'angkatan',
        'jurusan_id',
        'prodi_id',
        'negara_id',
        'hobi',
        'profesi_id',
        'nama_profesi',
        'jumlah_pegawai',
        'pendapatan',
        'foto_ktp',
        'foto_profil',
        'nik',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at', 'password', 'is_active'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function jurusan()
    { return $this->belongsTo(Jurusan::class, 'jurusan_id'); }

    public function prodi()
    { return $this->belongsTo(Prodi::class, 'prodi_id'); }

    public function scopeExclude($query, $columns = []) 
    {
        $arrTable = $this->fillable;
        $utility = new UtilityDB();
        $utility = $utility->excludeTable($query, $arrTable, $columns);
        return $utility;
    }
}
