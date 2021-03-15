<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Libraries\UtilityAPI;
use App\Album;
use App\Gallery;

class GalleryController extends Controller
{
    public function getListGallery(Request $request, $id)
    {
        if($request->header('type') == 'web')
        {  $componentAPI['defSort'] = array('priority', 'asc'); }

        else
        {  $componentAPI['defSort'] = array('id', 'desc'); }
        
        $componentAPI += array (
            'apiToken'  => $request->header('apiToken'),
            'successMsg'=> 'Success Get Gallery List',
            'mode'      => 'get',
            'offset'    => intval($request->header('offset')),
            'limit'     => intval($request->header('limit')),
            'sort'      => array($request->header('sort'), $request->header('sortType')),
            'searchData'=> $request->header('search'),
            'searchCol' => ['judul'],
            'type'      => $request->header('type'),
            'sql'       => Album::findOrFail($id)->gallery(),
            'addCase'   => array (
                ['and', 'is_active', '=', 'Y'],
            )
        );

        $response = new UtilityAPI();
        $response = $response->APIProcess($componentAPI);
        return $response;    
    }

    public function getDetailImage(Request $request, $id)
    {
        $componentAPI = array (
            'apiToken'  => $request->header('apiToken'),
            'successMsg'=> 'Success Get Gallery Detail',
            'mode'      => 'detail',
            'type'      => $request->header('type'),
            'sql'       => Gallery::exclude([]),
            'addCase'   => array (
                ['and', 'id', '=', $id],
                ['and', 'is_active', '=', 'Y'],
            )
        );

        $response = new UtilityAPI();
        $response = $response->APIProcess($componentAPI);
        return $response;
        
    }
    
    public function addGallery(Request $request)
    {
        $inputVal = array (
            'foto'          => 'required',
            'id_album'      => 'required',
        );

        $input = array (
            'judul'         => $request->judul,
            'foto'          => $request->foto,
            'id_album'      => $request->id_album,
            'priority'      => $request->priority,
            'is_active'     => 'N',
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
        $function = new GalleryController;
        $name = '_execGallery';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;      
    }

    public function editGallery(Request $request)
    {
        $inputVal = array (
            'id'            => 'required',
            'judul'         => 'required',
            'id_album'      => 'required',
            'priority'      => 'required',
        );

        $input = array (
            'id'            => $request->id,
            'judul'         => $request->judul,
            'foto'          => $request->foto,
            'id_album'      => $request->id_album,
            'priority'      => $request->priority,
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
        $function = new GalleryController;
        $name = '_execGallery';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;       
    }

    public function delGallery(Request $request)
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
        $function = new GalleryController;
        $name = '_execGallery';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;      
    }

    public function _execGallery($input, $parameter)
    {
        $error = false;
        try {            
            if(isset($input['foto']) && $parameter['additional']['mode_exec'] != 'Delete')
            {
                $file = $input['foto'];
                if($file != null)
                {
                    $bin = base64_decode($file);
                    $dt = Carbon::now();
                    $fileName = str_random(16).'-'.$dt->format('Y-m-d').'-gallery-'.md5($input['judul']).'.jpg';
                    $test = Storage::disk('gallery')->put($fileName, $bin);

                    $input['foto'] = $fileName;
                }
            }
            
            if($parameter['additional']['mode_exec'] == 'Add')
            { $data_user = Gallery::create($input); }

            else
            { 
                $data_query = Gallery::where('id', $input['id'])->where('is_active', 'Y');
                $data_image = $data_query->first();

                if(($parameter['additional']['mode_exec'] == 'Delete' || $input['foto'] != null) && isset($data_image['foto']))
                { Storage::disk('gallery')->delete($data_image['foto']); }

                
                else
                { unset ($input['foto']); }

                $data_user = $data_query->update($input); 
            }
            
            $errorCode = 200;
            $msg = $parameter['additional']['mode_exec'].' Gallery Success';   
        }
            
        catch(Exception $e) {
            $errorCode = 500;
            $msg = 'Error Occured on '.$parameter['additional']['mode_exec'].' Gallery';
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


