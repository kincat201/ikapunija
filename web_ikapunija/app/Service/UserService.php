<?php

namespace App\Service;

use App\UserAccess;

class UserService {
    public static function GenerateUserLog($id = '', $email = '',$type ='' , $token = '') {
        $dataLog = UserAccess::where('email',$email)
            ->where('mode', $type)
            ->first();

        $code = rand(100000,999999);
        if($dataLog !== null) {
            \DB::table('user_logs')
                ->where('email', $email)
                ->where('mode',$type)
                ->update([
                    'token'         => $token,
                    'mode'          => $type,
                    'updated_at'    => date("Y-m-d H:i:s"),
                    'last_login'    => date("Y-m-d H:i:s"),
                    'status'        => 'active',
                    'active_code'   => md5($code)
                ]);
        }

        else {
            \DB::table('user_logs')
                ->insertOrIgnore([
                    'email'       => $email,
                    'token'       => $token,
                    'id_user'     => $id,
                    'mode'        => $type,
                    'created_at'  => date("Y-m-d H:i:s"),
                    'updated_at'  => date("Y-m-d H:i:s"),
                    'last_login'  => date("Y-m-d H:i:s"),
                    'status'      => 'active',
                    'active_code' => md5($code)
                ]);
        }
    }
}
