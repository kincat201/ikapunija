<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\UtilityAPI;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Carbon\Carbon;
use App\UserAlumni;
use App\UserAccess;
use App\UserAdmin;
use Illuminate\Support\Facades\Storage;
use DB;

class UserController extends Controller
{
    public function login(Request $request){
        
        $inputVal = array (
            'email'         => 'required|email',
            'password'      => 'required',
        );

        $input = array (
            'email'     => $request->email,
            'password'  => $request->password,
        );

        $parameter = array (
            'type'          => $request->mode,
            'mode'          => 'login',
            'apiToken'      => $request->header('apiToken'),
        );

        $response = new UtilityAPI();
        $name = '_login';
        $function = new UserController;
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;
    }

    public function _login($input, $parameter)
    {
        $msg = '';
        $dataResp = '';
        $data = null;
        
        if($parameter['type'] == 'userWeb')    
        { $data = UserAlumni::where('email',$input['email'])->first(); }

        else if($parameter['type'] == 'userAdmin') 
        { $data = UserAdmin::where('email',$input['email'])->first(); }

        if($data != null && $parameter['type'] != 'web')
        {
            if($parameter['type'] == 'userWeb' || $parameter['type'] == 'userAdmin')
            {
                if(($input['email'] == $data->email) && ($data->password == md5($input['password'])))
                { 
                    $errorCode = 200;
                    $msg = 'Success Login';
                    $type = $parameter['type'] == 'userWeb' ? 'user' : 'admin';
                }

                else
                { 
                    $errorCode = 401;
                    $msg = 'Email atau Password yang anda masukan salah';  
                }
            }

            else
            { 
                $errorCode = 400;
                $msg = 'Access Denied';  
            }
        }

        else
        { 
            $errorCode = 403;
            $msg = 'Akun Tidak Terdaftar, Silahkan Registrasi Terlebih Dahulu';   
        }
        
        if( $errorCode == 200 )
        {
            $res['email']        = $input['email'];
            $res['user_type']    = $type;
            $res['token']        = str_random(32);

            $dataLog = UserAccess::where('email',$input['email'])
            ->where('mode', $type)
            ->first();
            
            $code = rand(100000,999999);
            if($dataLog !== null) {
                DB::table('user_logs')
                    ->where('email', $input['email'])
                    ->update([
                        'token'         => $res['token'],
                        'mode'          => $res['user_type'],
                        'updated_at'    => date("Y-m-d H:i:s"),
                        'last_login'    => date("Y-m-d H:i:s"),
                        'status'        => 'active',
                        'active_code'   => md5($code)
                    ]);
            }

            else {
                DB::table('user_logs')
                    ->insertOrIgnore([
                        'email'       => $res['email'],
                        'token'       => $res['token'],
                        'id_user'     => $data->id,
                        'mode'        => $res['user_type'],
                        'created_at'  => date("Y-m-d H:i:s"),
                        'updated_at'  => date("Y-m-d H:i:s"),
                        'last_login'  => date("Y-m-d H:i:s"),
                        'status'      => 'active',
                        'active_code' => md5($code)
                    ]);
            }

            $error      = false;
            $dataResp   = UserAccess::where('email',$input['email']);

            if($parameter['type'] == 'userWeb')
            { $dataResp = $dataResp->with('alumni'); }

            else
            { $dataResp = $dataResp->with('admin'); }

            $dataResp = $dataResp->where('mode', $type)->where('status', 'active') ->first();
        }

        else
        { $error = true; }

        $resp = array (
            'code'  => $errorCode,
            'error' => $error,
            'msg'   => $msg,
            'data'  => $dataResp,
        );

        return $resp;
    }

    public function logout(Request $request)
    {
        $inputVal = array ();

        $input = array (
            'email'         => $request->header('email'),
            'status'        => 'off',
            'updated_at'    => date('Y-m-d H:i:s'),
        );

        $parameter = array (
            'type'          => $request->header('type'),
            'apiToken'      => $request->header('apiToken'),
            'email'         => $request->header('email'),
            'token'         => $request->header('token'),
        );

        $response = new UtilityAPI();
        $function = new UserController;
        $name = '_logout';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;      
    }

    public function _logout($input, $parameter)
    {
        $error = false;
        try {
            $data_user = UserAccess::where('email', $input['email'])->where('status', 'active')->update($input);
            
            $errorCode = 200;
            $msg = 'Logout Success';   
        }
            
        catch(Exception $e) {
            $errorCode = 500;
            $msg = 'Error Occured on Logout';
        }

        $error = false;
        
        if($errorCode != 200)
        {  $error = true; }

        $resp = array (
            'code'  => $errorCode,
            'error' => $error,
            'msg'   => $msg,
            'data'  => ''
        );

        return $resp;
    }

    public function verify_account(Request $request, $email, $token)
    {
        $status = 'failed';

        if($email != null && $token != null)
        {
            $data_query = UserAlumni::where('email',$email)->where('active_code',$token)->where('is_active', 'V');
            $data = $data_query->first();
            
            if($data != null)
            { 
                $data_query_exist = UserAlumni::where('email',$email)->where('is_active', '!=', 'V')->where('is_active', '!=', 'N')->get();
                if(sizeof($data_query_exist) <= 0)
                {
                    $status = 'success';
                    $data_user = $data_query->update([
                        'is_active' => 'P'
                    ]);  
                }
            }
        }

        return redirect('https://ikapunija.com/login?status='.$status);

    }
}


