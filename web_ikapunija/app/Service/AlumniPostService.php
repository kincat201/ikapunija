<?php

namespace App\Service;

use App\AlumniPost;
use App\AlumniPostComments;
use App\AlumniPostLikes;
use App\AlumniPostReactions;
use App\Util\Constant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AlumniPostService {

    public static function MappingRowPost($data){
        if($data->types == Constant::ALUMNI_POST_TYPES_OPPORTUNITY) $data->content = json_decode($data->content);
        if(!empty($data->media)) $data->media = env('APP_ASSET').'alumni_post_media/'.$data->media;
        if(!empty($data->foto_profil)) $data->foto_profil = env('APP_ASSET').'user_alumni/profil/'.$data->foto_profil;
        return $data;
    }

    public static function SaveAlumiPost($request) {
        $data = AlumniPost::findOrNew($request->id);
        $data->fill((array)$request->all());
        if(empty($request->id)) $data->alumni_id = \Auth::user()->id;

        if($request->has('media')){
            if(!empty($data->media) && file_exists('asset_image/alumni_post_media/'.$data->media) && ($data->current_file != 'default.png')){
                unlink('asset_image/alumni_post_media/'.$data->media);
            }
            $dt = Carbon::now();
            $extension = $request->file('media')->getClientOriginalExtension();
            $fileName = str_random(16).'-'.$dt->format('Y-m-d').'-alumni-post-'.md5(date("Y-m-d H:i:s").$data->alumni_id).'.'.$extension;
            Storage::disk('alumni_post_media')->put($fileName, file_get_contents($request->file('media')));
            $data->media = $fileName;
        }else{
            $data->media = null;
        }

        $data->save();

        self::UpdateCommentPost($data->id);
        self::UpdateLikePost($data->id);

        return self::MappingRowPost($data);
    }

    public static function UpdateCommentPost($alumniPostId) {
        $data = AlumniPost::find($alumniPostId);
        $data->comments = $data->comments()->count();
        $data->save();
        return self::MappingRowPost($data);
    }

    public static function UpdateLikePost($alumniPostId) {
        $data = AlumniPost::find($alumniPostId);
        $data->likes = $data->likes()->count();
        $data->save();
        return self::MappingRowPost($data);
    }

    public static function SetLikePost($alumniPost){
        $field = [
            'alumni_like_id'=> \Auth::user()->id,
            'alumni_post_id'=>$alumniPost->id
        ];

        $data = AlumniPostLikes::where($field)->first();

        if(!empty($data)){
            $data->delete();
        }else{
            $field['alumni_id'] = $alumniPost->alumni_id;
            $data = new AlumniPostLikes();
            $data->fill((array) $field);
            $data->save();
        }

        return self::UpdateLikePost($alumniPost->id);
    }

    public static function SetCommentPost($alumniPost,$request){
        $data = new AlumniPostComments();
        $data->alumni_post_id = $alumniPost->id;
        $data->alumni_id = $alumniPost->alumni_id;
        $data->alumni_comment_id = \Auth::user()->id;
        $data->content = $request->content;
        $data->save();

        return self::UpdateCommentPost($alumniPost->id);
    }

    public static function SetReactionPost($alumniPost,$request){
        $field = [
            'alumni_reaction_id'=> \Auth::user()->id,
            'alumni_post_id'=>$alumniPost->id
        ];

        $data = AlumniPostReactions::where($field)->first();

        if(!empty($data)) $data->delete();

        $field['alumni_id'] = $alumniPost->alumni_id;
        $field['reaction'] = $request->content;
        $data = new AlumniPostReactions();
        $data->fill((array) $field);
        $data->save();

        return self::MappingRowPost($alumniPost);
    }

}
