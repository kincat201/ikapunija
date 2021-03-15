<?php

namespace App\Libraries;

use App\UserAccess;
use App\Libraries\UtilityAPI;

class CekAuth {
    public function checkKey($api_key)
    {
        if($api_key == env('APPS_KEY'))
        { return 'True Key'; }

        else
        { return null; }
    }

    public function checkLogin($input)
    {
        $response = new UtilityAPI();

        if($input['apiToken'] != env('APPS_KEY'))
        { return $response = $response->response_denied('admin'); }

        else
        {            
            $verify = UserAccess::where('email',$input['email'])
            ->where('token',$input['token'])
            ->where('status', 'active')
            ->where('mode', $input['type'])
            ->first();

            if($verify != null)
            { 
                $time_differ = strtotime('now') - strtotime($verify->last_login); 

                if($time_differ < 604800)
                { return $verify; }

                else
                { return null; }
            }
        }
    }
}