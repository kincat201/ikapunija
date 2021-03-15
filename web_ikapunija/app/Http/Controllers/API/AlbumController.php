<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Libraries\UtilityAPI;
use App\Album;
use App\Gallery;

class AlbumController extends Controller
{
    public function getList(Request $request)
    {
        $exclude = [];
        if($request->header('type') == 'web')
        {  $componentAPI['defSort'] = array('nama', 'asc'); }

        else
        {  $componentAPI['defSort'] = array('id', 'desc'); }
        
        $componentAPI += array (
            'apiToken'  => $request->header('apiToken'),
            'successMsg'=> 'Success Get Album List',
            'mode'      => 'get',
            'offset'    => intval($request->header('offset')),
            'limit'     => intval($request->header('limit')),
            'sort'      => array($request->header('sort'), $request->header('sortType')),
            'searchData'=> $request->header('search'),
            'searchCol' => ['nama'],
            'type'      => $request->header('type'),
            'sql'       => Album::exclude($exclude),
            'addCase'   => array (
                ['and', 'is_active', '=', 'Y'],
            )
        );

        $response = new UtilityAPI();
        $response = $response->APIProcess($componentAPI);
        return $response;
        
    }

    public function getDetailAlbum(Request $request, $id)
    {
        $exclude = [];
        $componentAPI = array (
            'apiToken'  => $request->header('apiToken'),
            'successMsg'=> 'Success Get Album Detail',
            'mode'      => 'detail',
            'type'      => $request->header('type'),
            'sql'       => Album::exclude($exclude),
            'addCase'   => array (
                ['and', 'is_active', '=', 'Y'],
                ['and', 'id', '=', $id],
            )
        );

        $response = new UtilityAPI();
        $response = $response->APIProcess($componentAPI);
        return $response;   
        
    }
    
    public function addAlbum(Request $request)
    {
        $inputVal = array (
            'nama'          => 'required',
            'logo'          => 'required'
        );

        $input = array (
            'nama'          => $request->nama,
            'logo'          => $request->logo,
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
        $function = new AlbumController;
        $name = '_execAlbum';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;      
    }

    public function editAlbum(Request $request)
    {
        $inputVal = array (
            'id'            => 'required',
            'nama'          => 'required'
        );

        $input = array (
            'id'            => $request->id,
            'nama'          => $request->nama,
            'logo'          => $request->logo,
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
        $function = new AlbumController;
        $name = '_execAlbum';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;       
    }

    public function delAlbum(Request $request)
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
        $function = new AlbumController;
        $name = '_execAlbum';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;      
    }

    public function _execAlbum($input, $parameter)
    {
        try {            
            if(isset($input['logo']) && $parameter['additional']['mode_exec'] != 'Delete')
            {
                $file = $input['logo'];
                if($file != null)
                {
                    $bin = base64_decode($file);
                    $dt = Carbon::now();
                    $fileName = str_random(16).'-'.$dt->format('Y-m-d').'-album-'.md5($input['nama']).'.jpg';
                    $test = Storage::disk('album')->put($fileName, $bin);

                    $input['logo'] = $fileName;
                }
            }
            
            if($parameter['additional']['mode_exec'] == 'Add')
            { $data_user = Album::create($input); }

            else
            { 
                $data_query = Album::where('id', $input['id'])->where('is_active', 'Y'); 
                $data_image = $data_query->first();

                if(($parameter['additional']['mode_exec'] == 'Delete' || $input['logo'] != null) && isset($data_image['logo']))
                { Storage::disk('album')->delete($data_image['logo']); }

                
                else
                { unset ($input['logo']); }

                $data_user = $data_query->update($input); 
            }
            
            $errorCode = 200;
            $msg = $parameter['additional']['mode_exec'].' Album Success';   
        }
            
        catch(Exception $e) {
            $errorCode = 500;
            $msg = 'Error Occured on '.$parameter['additional']['mode_exec'].' Album';
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


