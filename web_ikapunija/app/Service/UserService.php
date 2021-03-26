<?php

namespace App\Service;

use App\Mail\SendMail;
use App\UserAccess;
use App\UserAdmin;
use App\UserAlumni;
use Illuminate\Support\Facades\Mail;

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

    public static function SendForgotPassword($data,$table='user_alumni') {
        $code = rand(100000,999999);

        \DB::table($table)
            ->where('email', $data->email)
            ->update([
                'active_code'   => md5($code),
                'updated_at'    => date('Y-m-d H:i:s')
            ]);

        $dataUser = array(
            'name'      => $data->nama,
            'code'      => $code,
            'email'     => $data->email,
            'subject'   => 'Change Password',
            'perihal'   => 'untuk penggantian password di Website Ikapunija. Jika memang bukan Anda yang melakukan ini mohon dihiraukan, Terima Kasih !',
        );

        return Mail::to($dataUser['email'])
            ->send(new SendMail($dataUser));
    }

    public static function VerifyForgotPassword($data,$verify_code,$table='user_alumni') {
        $time = time();
        $acumulate_time = $time - strtotime($data->updated_at);
        if(($data->active_code == md5($verify_code)) && ($acumulate_time <= 300))
        {
            UserAccess::where('id_user',$data->id)
                ->update([
                    'status'        => 'pending',
                    'updated_at'    => date("Y-m-d H:i:s"),
                ]);

            \DB::table($table)
                ->where('id', $data->id)
                ->update([
                    'is_active'        => 'B',
                    'updated_at'       => date("Y-m-d H:i:s"),
                ]);

            return ['status'=>true,'message'=>'Berhasil berifikasi'];
        }
        else if($acumulate_time > 300)
        {
            return ['status'=>false,'message'=>'Kode verifikasi expired, mohon lakukan request ulang!'];
        }
        else
        {
            return ['status'=>false,'message'=>'Kode Verifikasi Salah!'];
        }
    }

    public static function UpdateForgotPassword($data,$verify_code,$password,$table='user_alumni') {
        $time = time();
        $acumulate_time = $time - strtotime($data->updated_at);
        if(($data->active_code == md5($verify_code)) && ($acumulate_time <= 1800))
        {
            \DB::table($table)
                ->where('id', $data->id)
                ->update([
                    'password'      => md5($password),
                    'is_active'     => 'Y',
                    'updated_at'    => date("Y-m-d H:i:s"),
                ]);

            return ['status'=>true,'message'=>'Berhasil ganti password'];
        }
        else if($acumulate_time > 1800)
        {
            return ['status'=>false,'message'=>'Kode verifikasi expired, mohon lakukan request ulang!'];
        }
        else
        {
            return ['status'=>false,'message'=>'Kode Verifikasi Salah!'];
        }
    }

    public static function SendVerificationNewAlumni($data){
        $dataUser = array(
            'name'      => $data->nama_alumni,
            'code'      => $data->active_code,
            'email'     => $data->email,
            'subject'   => 'Verifikasi Akun',
            'perihal'   => 'untuk verifikasi akun di Website Ikapunija',
        );

        return Mail::to($data->email)
            ->send(new SendMail($dataUser));
    }


}
