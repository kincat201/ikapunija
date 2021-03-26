<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlumniPostComments extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'alumni_post_comments';
    protected $fillable = [
        'id',
        'alumni_post_id',
        'alumni_id',
        'alumni_comment_id',
        'content',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

    ];

    public function post(){
        return $this->belongsTo(AlumniPost::class,'alumni_post_id','id');
    }

    public function alumni(){
        return $this->belongsTo(UserAlumni::class,'alumni_id','id');
    }

    public function alumni_comment(){
        return $this->belongsTo(UserAlumni::class,'alumni_comment_id','id');
    }
}
