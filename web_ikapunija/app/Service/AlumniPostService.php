<?php

namespace App\Service;

use App\AlumniPost;
use App\AlumniPostComments;
use App\AlumniPostLikes;
use App\AlumniPostReactions;
use App\Notification;
use App\UserAlumni;
use App\Util\Constant;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AlumniPostService {

    public static function MappingRowPost($data){
        if($data->types == Constant::ALUMNI_POST_TYPES_OPPORTUNITY) $data->content = json_decode($data->content);
        if(!empty($data->media)){
            $media = json_decode($data->media);
            if(!empty($media->name)){
                $data->media = $media;
                $data->media->name = env('APP_ASSET').'alumni_post_media/'.$media->name;
            }else{
                $data->media = null;
            }
        }
        if(!empty($data->foto_profil)) $data->foto_profil = env('APP_ASSET').'user_alumni/profil/'.(!empty($data->foto_profil) ? $data->foto_profil : 'default.png');
        return $data;
    }

    public static function SaveAlumiPost($request) {
        $data = AlumniPost::findOrNew($request->id);
        $data->fill((array)$request->all());
        if(empty($request->id)) $data->alumni_id = \Auth::user()->id;

        if($request->has('media')){
            if(!empty($data->media) && file_exists('asset_image/alumni_post_media/'.json_decode($data->media)->name) && (json_decode($data->media)->name != 'default.png')){
                unlink('asset_image/alumni_post_media/'.json_decode($data->media)->name);
            }
            $dt = Carbon::now();
            $extension = $request->file('media')->getClientOriginalExtension();
            $fileName = str_random(16).'-'.$dt->format('Y-m-d').'-alumni-post-'.md5(date("Y-m-d H:i:s").$data->alumni_id).'.'.$extension;
            Storage::disk('alumni_post_media')->put($fileName, file_get_contents($request->file('media')));
            $data->media = json_encode([
                'name'=>$fileName,
                'types'=> !empty($request->media_types) ? $request->media_types : Constant::ALUMNI_POST_MEDIA_TYPES_PHOTO
            ]);
        }else{
            $data->media = null;
        }

        $data->save();

        self::UpdateCommentPost($data->id);
        self::UpdateLikePost($data->id);

        if(empty($request->id) && $data->types == Constant::ALUMNI_POST_TYPES_OPPORTUNITY) self::SetAlumniPostNotificationBulk($data,Constant::NOTIFICATION_TYPE_ALUMNI_POST_OPPORTUNITY,true);

        return self::MappingRowPost($data);
    }

    public static function UpdateCommentPost($alumniPostId) {
        $data = AlumniPost::find($alumniPostId);
        $data->comments = $data->comment_list()->count();
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
            self::SetAlumniPostNotificationSingle($alumniPost, Constant::NOTIFICATION_TYPE_ALUMNI_POST_LIKE);
        }else{
            $field['alumni_id'] = $alumniPost->alumni_id;
            $data = new AlumniPostLikes();
            $data->fill((array) $field);
            $data->save();
            self::SetAlumniPostNotificationSingle($alumniPost, Constant::NOTIFICATION_TYPE_ALUMNI_POST_LIKE, true, true);
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

        self::SetAlumniPostNotificationSingle($alumniPost, Constant::NOTIFICATION_TYPE_ALUMNI_POST_COMMENT, true,true);

        return self::UpdateCommentPost($alumniPost->id);
    }

    public static function SetReactionPost($alumniPost,$request){
        $field = [
            'alumni_reaction_id'=> \Auth::user()->id,
            'alumni_post_id'=>$alumniPost->id
        ];

        $data = AlumniPostReactions::where($field)->first();
        $field['reaction'] = $request->content;

        if(!empty($data)){
            $data->delete();
            self::SetAlumniPostNotificationSingle($alumniPost, Constant::NOTIFICATION_TYPE_ALUMNI_POST_INTERACTION);
        }else{
            $field['alumni_id'] = $alumniPost->alumni_id;
            $data = new AlumniPostReactions();
            $data->fill((array) $field);
            $data->save();
            self::SetAlumniPostNotificationSingle($alumniPost, Constant::NOTIFICATION_TYPE_ALUMNI_POST_INTERACTION, true,true);
        }

        return self::MappingRowPost($alumniPost);
    }

    public static function SetAlumniPostNotificationSingle($alumniPost, $types,$create = false, $push_notification = false){
        if($alumniPost->alumni_id != \Auth::user()->id){
            $notification_data = [
                'senderId'=>\Auth::user()->id,
                'receiverId'=> $alumniPost->alumni_id,
                'type'=> $types,
                'referenceId'=>$alumniPost->id
            ];
            $notification = Notification::where($notification_data);

            if(in_array($types, [
                Constant::NOTIFICATION_TYPE_ALUMNI_POST_LIKE,
                Constant::NOTIFICATION_TYPE_ALUMNI_POST_INTERACTION,
            ])){
                if($notification->exists()) $notification->delete();
            }

            if($create){
                $notification_data['subject'] = Constant::NOTIFICATION_TYPE_ALUMNI_SUBJECT_LIST[$types];
                $notification_data['description'] = \Auth::user()->nama_alumni. ' '.Constant::NOTIFICATION_TYPE_ALUMNI_MESSAGE_LIST[$types];
                NotificationService::SendNotification($notification_data);

                if($push_notification){
                    NotificationService::SendPushNotification($notification_data,[$alumniPost->alumni_id]);
                }
            }
        }
    }

    public static function SetAlumniPostNotificationBulk($alumniPost, $types, $push_notification = false){
        $user_alumni = UserAlumni::where('is_active',Constant::ACTIVE_STATUS_YES)->get();

        $receiverId = [];

        $notification_data = [
            'senderId'=> $alumniPost->alumni_id,
            'type'=> $types,
            'referenceId'=> $alumniPost->id,
            'subject'=> Constant::NOTIFICATION_TYPE_ALUMNI_SUBJECT_LIST[$types],
            'description'=> \Auth::user()->nama_alumni. ' '.Constant::NOTIFICATION_TYPE_ALUMNI_MESSAGE_LIST[$types],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        $notifications = [];

        foreach ($user_alumni as $alumni){
            if($alumni->id == $alumniPost->alumni_id) continue;
            $notif = $notification_data;
            $notif['receiverId'] = $alumni->id;
            $notifications[] = $notif;
            if(!empty($alumni->device_token)) $receiverId[] = $alumni->id;
        }

        NotificationService::SendNotification($notifications, true);

        if($push_notification){
            NotificationService::SendPushNotification($notification_data,$receiverId);
        }
    }

}
