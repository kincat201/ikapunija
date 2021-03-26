<?php

namespace App;

use App\Util\Constant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlumniPost extends Model
{

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'alumni_posts';
    protected $fillable = [
        'id',
        'alumni_id',
        'types',
        'content',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at'
    ];

    protected $dates = [
        'deleted_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        //'created_at' => 'datetime',
    ];

    public function alumni(){
        return $this->belongsTo(UserAlumni::class,'alumni_id','id');
    }

    public function likes(){
        return $this->hasMany(AlumniPostLikes::class,'alumni_post_id','id');
    }

    public function comments(){
        return $this->hasMany(AlumniPostComments::class,'alumni_post_id','id');
    }

    public function reactions(){
        return $this->hasMany(AlumniPostReactions::class,'alumni_post_id','id');
    }

    public function reactionResult(){
        $datas = $this->reactions();
        $result = [];
        foreach (Constant::POST_REACTION_LIST as $key => $val) {
            $result[$key] = 0;
            foreach ($datas->get() as $data){
                if($key == $data->reaction) $result[$key]++;
            }
        }
        return $result;
    }
}
