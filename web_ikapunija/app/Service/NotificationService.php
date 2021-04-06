<?php

namespace App\Service;

use App\Notification;
use App\UserAlumni;
use App\Util\Constant;

class NotificationService {
    public static function SendNotification($data, $bulk = false){
        if(!$bulk){
            $notification = new Notification();
            $notification->senderId = $data['senderId'];
            $notification->receiverId = $data['receiverId'];
            $notification->subject = $data['subject'];
            $notification->description = $data['description'];
            $notification->type = $data['type'];
            $notification->referenceId = $data['referenceId'];
            $notification->status = Constant::NOTIFICATION_STATUS_UNREAD;
            $notification->save();
            return $notification;
        }else{
            $firstId = Notification::orderBy('id','desc')->first()->id;
            Notification::insert($data);
            $lastId = Notification::orderBy('id','desc')->first()->id;
            return Notification::select('notifications.id','notifications.receiverId')
                ->join('user_alumni','user_alumni.id','=','notifications.receiverId')
                ->where('notifications.type',$data[0]['type'])
                ->where('notifications.id','>=',$firstId)
                ->where('notifications.id','<=',$lastId)
                ->whereNotNull('user_alumni.device_token')
                ->get();
        }
    }

    public static function SetNotificationRead($notificationList=[]){
        Notification::whereIn('id',$notificationList)->update(['status'=>Constant::NOTIFICATION_STATUS_READ]);
    }

    public static function SendPushNotification($payload,$receivers=[])
    {
        $response = new \stdClass();
        $datas = UserAlumni::whereIn('id',$receivers)->get();

        $registration_ids = [];

        foreach ($datas as $receiver){
            if(!empty($receiver->device_token)) $registration_ids[] = $receiver->device_token;
        }

        if(count($registration_ids) > 0){
            $data = [
                "registration_ids" => $registration_ids,
                "notification" => [
                    "title" => $payload['subject'],
                    "body" => $payload['description'],
                ],
                "data"=>[
                    "title" => $payload['subject'],
                    "body" => $payload['description'],
                    "type"=> $payload['type'],
                    "referenceId"=> $payload['referenceId'],
                    "notification_list"=>$payload['notification_list']
                ]
            ];
            $dataString = json_encode($data);

            $headers = [
                'Authorization: key=' . env('FCM_KEY'),
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response->status = true;
            $response->result = json_decode(curl_exec($ch));
            $response->data = $registration_ids;
        }else{
            $response->status = false;
        }

        //\Log::info(json_encode($response));

        return $response;
    }
}
