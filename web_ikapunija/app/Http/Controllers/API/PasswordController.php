<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\UserAlumni;
use App\UserAdmin;
use App\UserAccess;
use DB;

class PasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $email = $request->email;
        $mode = $request->mode;
        if($email != null && $mode != null) {
            if ($request->header('apiToken') == env('APPS_KEY'))
            {   
                $mode = $request->mode;
                if($mode == 'web')
                { 
                    $data = UserAlumni::where('email',$email)->where('is_active','Y')->first(); 
                    $table = 'user_alumni'; 
                }

                else if($mode == 'admin')
                { 
                    $data = UserAdmin::where('email',$email)->where('is_active','Y')->first();
                    $table = 'users_admin'; 
                }

                if($data != null)
                {
                    $code = rand(100000,999999);

                    DB::table($table)
                    ->where('email', $email)
                    ->update([
                        'active_code'   => md5($code),
                        'updated_at'    => date('Y-m-d H:i:s')
                    ]);
                    
                    $dataUser = array(
                        'name'      => $data->nama, 
                        'code'      => $code, 
                        'email'     => $email,
                        'subject'   => 'Change Password', 
                        'perihal'   => 'untuk penggantian password di Website Ikapunija. Jika memang bukan Anda yang melakukan ini mohon dihiraukan, Terima Kasih !', 
                    );

                    Mail::to($dataUser['email'])
                    ->send(new SendMail($dataUser));

                    return response()->json([
                        'StatusCode' =>200,
                        'Error'=>false,
                        'Message'=>'Silahkan Cek Email Anda',
                    ]);
                }

                else
                {
                    return response()->json([
                        'StatusCode' =>400,
                        'Error'=>true,
                        'Message'=>'Email Yang Anda Masukkan Tidak Terdaftar',
                    ]);
                }
            }

            else
            {
                return response()->json([
                    'StatusCode' =>400,
                    'Error'=>true,
                    'Message'=>'Access Denied',
                ]);
            }
        }

        else {
            return response()->json([
                'StatusCode' =>400,
                'Error'=>true,
                'Message'=>'Silahkan Masukkan Email']);
        }
        
    }

    public function verifyForgotPassword(Request $request)
    {
        $email = $request->email;
        $verify_code = $request->verify_code;
        $mode = $request->mode;
        $time = time();

        if($mode == 'web')
        { $data = UserAlumni::where('email',$email)->where('is_active','Y')->first(); }

        else if($mode == 'admin')
        { $data = UserAdmin::where('email',$email)->where('is_active','Y')->first(); }

        else
        { $data = null; }

        if ($request->header('apiToken') == env('APPS_KEY'))
        {
            
            if($data !== null && $mode !== null)
            {
                $acumulate_time = $time - strtotime($data->updated_at);
                if(($data->active_code == md5($verify_code)) && ($acumulate_time <= 300))
                {
                    $verify = UserAccess::where('id_user',$data->id)
                    ->update([
                        'status'        => 'pending',
                        'updated_at'    => date("Y-m-d H:i:s"),
                    ]);

                    if($mode == 'web')
                    {
                        $verifyUser = UserAlumni::where('id',$data->id)
                        ->update([
                            'is_active'        => 'B',
                            'updated_at'       => date("Y-m-d H:i:s"),
                        ]);
                    }

                    else
                    {
                        $verifyUser = UserAdmin::where('id',$data->id)
                        ->update([
                            'active'        => 'B',
                            'updated_at'    => date("Y-m-d H:i:s"),
                        ]);
                    }

                    return response()->json([
                        'StatusCode' =>200,
                        'Error'=>false, 
                        'Message'=>'Verification Success',
                    ]);
                }

                else if($acumulate_time > 300)
                {
                    return response()->json([
                        'StatusCode' =>402,
                        'Error'=>true, 
                        'Message'=>'Please Request Verification Code Again !'
                    ]);
                }

                else
                {
                    return response()->json([
                        'StatusCode' =>403,
                        'Error'=>true, 
                        'Message'=>'Kode Verifikasi Salah',
                    ]);
                }
            }

            else
            {
                return response()->json([
                    'StatusCode' =>401,
                    'Error'=>true, 
                    'Message'=>'Akun Tidak Tersedia',
                    ]); 
            }
        }

        else {
            return response()->json([
                'StatusCode' =>400,
                'Error'=>true, 
                'Message'=>'Access Denied']); 
        }

    }

    public function updatePassword(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        $cPassword = $request->cPassword;
        $verify_code = $request->verify_code;
        $mode = $request->mode;
        $time = time();

        if($password != $cPassword)
        {
            return response()->json([
                'StatusCode' =>401,
                'Error'=>true, 
                'Message'=>'Password dan Konfirmasi Password Tidak Sama',
            ]); 
        }

        else if($mode)
        {
            if ($request->header('apiToken') == env('APPS_KEY') && $mode != null)
            {
                if($mode == 'web')
                { $data = UserAlumni::where('email',$email)->where('is_active','B')->first(); }

                else
                { $data = UserAdmin::where('email',$email)->where('is_active','B')->first(); }

                if($data !== null)
                {
                    $acumulate_time = $time - strtotime($data->updated_at);
                    if(($data->active_code == md5($verify_code)) && ($acumulate_time <= 1800))
                    {
                        if($mode == 'web')
                        { 
                            $verifyUser = UserAlumni::where('id',$data->id)
                            ->update([
                                'password'      => md5($password),
                                'is_active'     => 'Y',
                                'updated_at'    => date("Y-m-d H:i:s"),
                            ]); 
                        }

                        else
                        {
                            $verifyUser = UserAdmin::where('id',$data->id)
                            ->update([
                                'password'      => md5($password),
                                'is_active'     => 'Y',
                                'updated_at'    => date("Y-m-d H:i:s"),
                            ]); 
                        }
                            
                        return response()->json([
                            'StatusCode' =>200,
                            'Error'=>false, 
                            'Message'=>'Change Password Success',
                        ]);
                    }

                    elseif($acumulate_time > 1800)
                    {
                        return response()->json([
                            'StatusCode' =>401,
                            'Error'=>true, 
                            'Message'=>'Please Request Verification Code Again !'
                        ]);
                    }

                    else
                    {
                        return response()->json([
                            'StatusCode' =>400,
                            'Error'=>true, 
                            'Message'=>'Access Denied',
                        ]);
                    }
                }

                else
                {
                    return response()->json([
                        'StatusCode' =>400,
                        'Error'=>true, 
                        'Message'=>'Akun Tidak Terdaftar',
                    ]); 
                }
            }

            else {
                return response()->json([
                    'StatusCode' =>400,
                    'Error'=>true, 
                    'Message'=>'Access Denied']); 
            }
        }

    }

}


