<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Libraries\UtilityDB;

class Banner extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'banner';
    protected $fillable = [
        'id',
        'judul',
        'banner',
        'link',
        'priority',
        'desc_singkat',
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
        'judul' => '-',
        'link' => '-',
        'desc_singkat' => '-',
    ];

    public function scopeExclude($query, $columns = []) 
    {
        $arrTable = $this->fillable;
        $utility = new UtilityDB();
        $utility = $utility->excludeTable($query, $arrTable, $columns);
        return $utility;
    }
}
