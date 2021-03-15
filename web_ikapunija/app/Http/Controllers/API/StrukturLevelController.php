<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\UtilityAPI;
use App\StrukturLevel;

class StrukturLevelController extends Controller
{
    public function getList(Request $request)
    {
        $componentAPI['defSort'] = array('level', 'desc');
        
        $componentAPI += array (
            'apiToken'  => $request->header('apiToken'),
            'successMsg'=> 'Success Get StrukturLevel List',
            'mode'      => 'get',
            'offset'    => intval($request->header('offset')),
            'limit'     => intval($request->header('limit')),
            'sort'      => array($request->header('sort'), $request->header('sortType')),
            'searchData'=> $request->header('search'),
            'searchCol' => ['nama_strukturLevel'],
            'type'      => $request->header('type'),
            'sql'       => StrukturLevel::exclude([]),
            'addCase'   => array (
                ['and', 'is_active', '=', 'Y'],
            )
        );

        $response = new UtilityAPI();
        $response = $response->APIProcess($componentAPI);
        return $response;
    }

    public function getDetail(Request $request, $id)
    {
        $componentAPI = array (
            'apiToken'  => $request->header('apiToken'),
            'successMsg'=> 'Success Get Struktur Level Detail',
            'mode'      => 'detail',
            'type'      => $request->header('type'),
            'sql'       => StrukturLevel::exclude([]),
            'addCase'   => array (
                ['and', 'is_active', '=', 'Y'],
            )
        );

        $response = new UtilityAPI();
        $response = $response->APIProcess($componentAPI);
        return $response;
    }
    
    public function addStrukturLevel(Request $request)
    {
        $inputVal = array (
            'nama_level'    => 'required',
            'level'         => 'required',
            'type_level'    => 'required',
        );

        $input = array (
            'nama_level'    => $request->nama_level,
            'type_level'    => $request->type_level,
            'level'         => $request->level,
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
        $function = new StrukturLevelController;
        $name = '_execStrukturLevel';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;      
    }

    public function editStrukturLevel(Request $request)
    {
        $inputVal = array (
            'id'            => 'required',
            'nama_level'    => 'required',
            'level'         => 'required',
            'type_level'    => 'required',
        );

        $input = array (
            'id'            => $request->id,
            'nama_level'    => $request->nama_level,
            'type_level'    => $request->type_level,
            'level'         => $request->level,
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
        $function = new StrukturLevelController;
        $name = '_execStrukturLevel';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;       
    }

    public function delStrukturLevel(Request $request)
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
        $function = new StrukturLevelController;
        $name = '_execStrukturLevel';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;      
    }

    public function _execStrukturLevel($input, $parameter)
    {
        $error = false;
        try {            
            if($parameter['additional']['mode_exec'] == 'Add')
            { $data_user = StrukturLevel::create($input); }

            else
            { $data_user = StrukturLevel::where('id', $input['id'])->where('is_active', 'Y')->update($input); }
            
            $errorCode = 200;
            $msg = $parameter['additional']['mode_exec'].' Struktur Level Success';   
        }
            
        catch(Exception $e) {
            $errorCode = 500;
            $msg = 'Error Occured on '.$parameter['additional']['mode_exec'].' Struktur Level';
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


