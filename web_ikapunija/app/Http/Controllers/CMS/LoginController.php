<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index(Request $request)
    { return view('cms/login'); }

    public function login(Request $request)
    { 
        $cURLConnection = curl_init();
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            'apiToken:'.env('APPS_KEY'),
        ));

        curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, array(
            "email"     =>$request->email,
            "password"  =>$request->password,
            "mode"      =>'userAdmin',
        ));
        
        curl_setopt($cURLConnection, CURLOPT_URL, env('APP_URL').'api/login');
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($cURLConnection);
        curl_close($cURLConnection);
        $resp = json_decode($output);

        if($resp->Error == false)
        {
            session([
                'email' => $resp->Data->email,
                'token' => $resp->Data->token,
                'nama'  => $resp->Data->admin->nama,
            ]);

            return redirect('/user_manager');
        }

        else
        { return redirect('/login'); }
    }

    public function logout(Request $request)
    { 
        $cURLConnection = curl_init();
        
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            'apiToken:'.env('APPS_KEY'),
        ));

        curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, array(
            "email"     =>session('email'),
            "token"     =>session('token'),
            "apiToken"  =>env('APPS_KEY'),
            "type"      =>'admin',
        ));

        curl_setopt($cURLConnection, CURLOPT_URL, env('APP_URL').'api/logout');
        curl_setopt($cURLConnection, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($cURLConnection);

        curl_close($cURLConnection);
        $resp = json_decode($output);
        
        $request->session()->flush();
        return redirect('/login');
        
    }
}
