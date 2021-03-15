<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\UtilityAPI;
use App\UserAdmin;

class UserManagerController extends Controller
{
    public function getDetail(Request $request, $id)
    {
        $componentAPI = array (
            'apiToken'  => $request->header('apiToken'),
            'successMsg'=> 'Success Get User Admin Detail',
            'mode'      => 'detail',
            'type'      => $request->header('type'),
            'sql'       => UserAdmin::exclude([]),
            'addCase'   => array (
                ['and', 'id', '=', $id],
                ['and', 'is_active', '=', 'Y'],
            )
        );

        $response = new UtilityAPI();
        $response = $response->APIProcess($componentAPI);
        return $response;
    }
    
    public function addUserAdmin(Request $request)
    {
        $inputVal = array (
            'type'          => 'required',
            'nama'          => 'required',
            'email'         => 'required',
            'password'      => 'required',
        );

        $input = array (
            'type'          => 'superadmin',
            'nama'          => $request->nama,
            'email'         => $request->email,
            'password'      => md5($request->password),
            'is_active'     => 'Y',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'), 
        );

        $parameter = array (
            'type'          => 'admin',
            'apiToken'      => $request->header('apiToken'),
            'email'         => $request->header('email'),
            'token'         => $request->header('token'),
            'additional'    => array (
                'mode_exec' => 'Add',
            )
        );

        $response = new UtilityAPI();
        $function = new UserManagerController;
        $name = '_execUserAdmin';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;      
    }

    public function editUserAdmin(Request $request)
    {
        $inputVal = array (
            'id'            => 'required',
            'type'          => 'required',
            'nama'          => 'required',
        );

        $input = array (
            'id'            => $request->id,
            'type'          => 'superadmin',
            'nama'          => $request->nama,
            'password'      => md5($request->password),
            'updated_at'    => date('Y-m-d H:i:s'), 
        );

        $parameter = array (
            'type'          => 'admin',
            'apiToken'      => $request->header('apiToken'),
            'email'         => $request->header('email'),
            'token'         => $request->header('token'),
            'additional'    => array (
                'mode_exec' => 'Edit',
            )
        );

        $response = new UtilityAPI();
        $function = new UserManagerController;
        $name = '_execUserAdmin';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;       
    }

    public function delUserAdmin(Request $request)
    {
        $inputVal = array (
            'id'            => 'required',
        );

        $input = array (
            'id'            => $request->id,
            'is_active'     => 'N',
            'updated_at'    => date('Y-m-d H:i:s'),
        );

        $parameter = array (
            'type'          => 'admin',
            'apiToken'      => $request->header('apiToken'),
            'email'         => $request->header('email'),
            'token'         => $request->header('token'),
            'additional'    => array (
                'mode_exec' => 'Delete',
            )
        );

        $response = new UtilityAPI();
        $function = new UserManagerController;
        $name = '_execUserAdmin';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;      
    }

    public function _execUserAdmin($input, $parameter)
    {
        $error = false;
        try {            
            $resp = '';
            if($parameter['additional']['mode_exec'] == 'Add')
            { 
                $data_user = UserAdmin::where('email', $input['email'])->where('is_active', 'Y')->first();
                
                if($data_user == null)
                { $data_user = UserAdmin::insert($input); }

                else
                { 
                    $resp = 'Error';
                    $errorCode = 401;
                    $msg = 'User Email Telah Digunakan'; 
                }
            }

            else
            { $data_user = UserAdmin::where('id', $input['id'])->where('is_active', 'Y')->update($input); }
            
            if($resp == '')
            {
                $errorCode = 200;
                $msg = $parameter['additional']['mode_exec'].' UserAdmin Success';
            } 
        }
            
        catch(Exception $e) {
            $errorCode = 500;
            $msg = 'Error Occured on '.$parameter['additional']['mode_exec'].' UserAdmin';
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
}


