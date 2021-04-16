<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Libraries\UtilityDB;
use App\Jurusan;
use App\Prodi;
use Tymon\JWTAuth\Contracts\JWTSubject;


class UserAlumni extends Authenticatable implements JWTSubject
{
    use Notifiable;
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
        'province_id',
        'city_id',
        'city_other',
        'company',
        'last_education',
        'device_token',
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

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function interests(){
        return $this->hasMany(InterestAlumni::class,'alumni_id','id');
    }

    public function profession(){
        return $this->belongsTo(Profesi::class,'profesi_id','id');
    }

    public function country(){
        return $this->belongsTo(Country::class,'negara_id','id');
    }

    public function province(){
        return $this->belongsTo(Province::class,'province_id','id');
    }

    public function city(){
        return $this->belongsTo(City::class,'city_id','id');
    }

    public function occupation(){
        return $this->belongsTo(Company::class,'company','code');
    }
}
