<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Libraries\UtilityAPI;
use App\Jurusan;

class JurusanController extends Controller
{
    public function getList(Request $request)
    {
        $exclude = [];
        $componentAPI['defSort'] = array('nama_jurusan', 'asc'); 
        
        $componentAPI += array (
            'apiToken'  => $request->header('apiToken'),
            'successMsg'=> 'Success Get Jurusan List',
            'mode'      => 'get',
            'offset'    => intval($request->header('offset')),
            'limit'     => intval($request->header('limit')),
            'sort'      => array($request->header('sort'), $request->header('sortType')),
            'searchData'=> $request->header('search'),
            'searchCol' => ['nama_jurusan'],
            'type'      => $request->header('type'),
            'sql'       => Jurusan::exclude($exclude),
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
            'successMsg'=> 'Success Get Jurusan Detail',
            'mode'      => 'detail',
            'type'      => $request->header('type'),
            'sql'       => Jurusan::exclude([]),
            'addCase'   => array (
                ['and', 'id', '=', $id],
                ['and', 'is_active', '=', 'Y'],
            )
        );

        $response = new UtilityAPI();
        $response = $response->APIProcess($componentAPI);
        return $response;
    }
    
    public function addJurusan(Request $request)
    {
        $inputVal = array (
            'nama_jurusan'  => 'required'
        );

        $input = array (
            'nama_jurusan'  => $request->nama_jurusan,
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
        $function = new JurusanController;
        $name = '_execJurusan';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;      
    }

    public function editJurusan(Request $request)
    {
        $inputVal = array (
            'id'            => 'required',
            'nama_jurusan'  => 'required',
        );

        $input = array (
            'id'            => $request->id,
            'nama_jurusan'  => $request->nama_jurusan,
            'is_active'     => 'Y',
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
        $function = new JurusanController;
        $name = '_execJurusan';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;       
    }

    public function delJurusan(Request $request)
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
        $function = new JurusanController;
        $name = '_execJurusan';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;      
    }

    public function _execJurusan($input, $parameter)
    {
        $error = false;
        try {            
            if($parameter['additional']['mode_exec'] == 'Add')
            { $data_user = Jurusan::create($input); }

            else
            { $data_user = Jurusan::where('id', $input['id'])->where('is_active', 'Y')->update($input); }
            
            $errorCode = 200;
            $msg = $parameter['additional']['mode_exec'].' Jurusan Success';   
        }
            
        catch(Exception $e) {
            $errorCode = 500;
            $msg = 'Error Occured on '.$parameter['additional']['mode_exec'].' Jurusan';
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


