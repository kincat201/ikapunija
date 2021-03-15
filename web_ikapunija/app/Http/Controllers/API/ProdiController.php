<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\UtilityAPI;
use App\Prodi;

class ProdiController extends Controller
{
    public function getList(Request $request, $jurusan)
    {
        $componentAPI['defSort'] = array('nama_prodi', 'desc');
        
        $componentAPI += array (
            'apiToken'  => $request->header('apiToken'),
            'successMsg'=> 'Success Get Prodi List',
            'mode'      => 'get',
            'offset'    => intval($request->header('offset')),
            'limit'     => intval($request->header('limit')),
            'sort'      => array($request->header('sort'), $request->header('sortType')),
            'searchData'=> $request->header('search'),
            'searchCol' => ['nama_prodi'],
            'type'      => $request->header('type'),
            'sql'       => Prodi::with('jurusan'),
            'addCase'   => array (
                ['and', 'id_jurusan', '=', $jurusan],
                ['and', 'is_active', '=', 'Y'],
            )
        );

        $response = new UtilityAPI();
        $response = $response->APIProcess($componentAPI);
        return $response;
    }

    public function getDetail(Request $request, $jurusan, $id)
    {
        $componentAPI = array (
            'apiToken'  => $request->header('apiToken'),
            'successMsg'=> 'Success Get Prodi Detail',
            'mode'      => 'detail',
            'type'      => $request->header('type'),
            'sql'       => Prodi::with('jurusan'),
            'addCase'   => array (
                ['and', 'id', '=', $id],
                ['and', 'id_jurusan', '=', $jurusan],
                ['and', 'is_active', '=', 'Y'],
            )
        );

        $response = new UtilityAPI();
        $response = $response->APIProcess($componentAPI);
        return $response;
    }
    
    public function addProdi(Request $request)
    {
        $inputVal = array (
            'nama_prodi'    => 'required',
            'id_jurusan'    => 'required',
        );

        $input = array (
            'nama_prodi'    => $request->nama_prodi,
            'id_jurusan'    => $request->id_jurusan,
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
        $function = new ProdiController;
        $name = '_execProdi';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;      
    }

    public function editProdi(Request $request)
    {
        $inputVal = array (
            'id'            => 'required',
            'nama_prodi'    => 'required',
            'id_jurusan'    => 'required',
        );

        $input = array (
            'id'            => $request->id,
            'nama_prodi'    => $request->nama_prodi,
            'id_jurusan'    => $request->id_jurusan,
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
        $function = new ProdiController;
        $name = '_execProdi';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;       
    }

    public function delProdi(Request $request)
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
        $function = new ProdiController;
        $name = '_execProdi';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;      
    }

    public function _execProdi($input, $parameter)
    {
        $error = false;
        try {            
            if($parameter['additional']['mode_exec'] == 'Add')
            { $data_user = Prodi::create($input); }

            else
            { $data_user = Prodi::where('id', $input['id'])->where('is_active', 'Y')->update($input); }
            
            $errorCode = 200;
            $msg = $parameter['additional']['mode_exec'].' Prodi Success';   
        }
            
        catch(Exception $e) {
            $errorCode = 500;
            $msg = 'Error Occured on '.$parameter['additional']['mode_exec'].' Prodi';
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


