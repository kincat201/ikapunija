<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\UtilityAPI;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Carbon\Carbon;
use DB;

class KontakController extends Controller
{
    public function sendKontak(Request $request)
    {
        $inputVal = array (
            'nama'          => 'required',
            'email_from'    => 'required',
            'judul'         => 'required',
            'pesan'         => 'required',
        );

        $input = array (
            'nama'          => $request->nama,
            'email_from'    => $request->email,
            'email'         => env('EMAIL_ADMIN'),
            'judul'         => $request->judul,
            'subject'       => 'Web Ikapunija',
            'pesan'         => $request->pesan,
        );

        $parameter['additional']['mode_exec'] = 'Kontak';
        $parameter['apiToken']= $request->header('apiToken');
        $parameter['type']= 'web';
        
        $response = new UtilityAPI();
        $function = new KontakController;
        $name = '_execKontak';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;
    }

    public function _execKontak($input, $parameter)
    {
        $error = false;
        try {
            Mail::to($input['email'])
            ->send(new SendMail($input));

            $errorCode = 200;
            $msg = 'Send Message Success';   
        }
            
        catch(Exception $e) {
            $errorCode = 500;
            $msg = 'Error Occured on Send Message';
        }

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
}


