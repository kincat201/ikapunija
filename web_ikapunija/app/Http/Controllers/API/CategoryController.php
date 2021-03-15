<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Libraries\UtilityAPI;
use App\Category;

class CategoryController extends Controller
{
    public function getList(Request $request)
    {       
        $exclude = [];
        $jenis = $request->header('jenis');
        $componentAPI['defSort'] = array('nama', 'asc'); 

        if($request->header('type') == 'web')
        {  $exclude = ['type']; }
                
        $componentAPI += array (
            'apiToken'  => $request->header('apiToken'),
            'successMsg'=> 'Success Get Category List',
            'mode'      => 'get',
            'offset'    => intval($request->header('offset')),
            'limit'     => intval($request->header('limit')),
            'sort'      => array($request->header('sort'), $request->header('sortType')),
            'searchData'=> $request->header('search'),
            'searchCol' => ['nama'],
            'type'      => $request->header('type'),
            'sql'       => Category::exclude($exclude),
            'addCase'   => array (
                ['and', 'is_active', '=', 'Y'],
                ['and', 'type', '=', $jenis],
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
            'successMsg'=> 'Success Get Category Detail',
            'mode'      => 'detail',
            'type'      => $request->header('type'),
            'sql'       => Category::exclude([]),
            'addCase'   => array (
                ['and', 'id', '=', $id],
                ['and', 'is_active', '=', 'Y'],
            )
        );

        $response = new UtilityAPI();
        $response = $response->APIProcess($componentAPI);
        return $response;
    }
    
    public function addCategory(Request $request)
    {
        $inputVal = array (
            'nama'          => 'required',
            'type'          => 'required',
        );

        $input = array (
            'nama'          => $request->nama,
            'type'          => $request->type,
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
        $function = new CategoryController;
        $name = '_execCategory';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;      
    }

    public function editCategory(Request $request)
    {
        $inputVal = array (
            'id'            => 'required',
            'nama'          => 'required',
            'type'          => 'required',
        );

        $input = array (
            'id'            => $request->id,
            'nama'          => $request->nama,
            'type'          => $request->type,
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
        $function = new CategoryController;
        $name = '_execCategory';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;       
    }

    public function delCategory(Request $request)
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
        $function = new CategoryController;
        $name = '_execCategory';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;      
    }

    public function _execCategory($input, $parameter)
    {
        $error = false;
        try {
            if(isset($input['foto']))
            {
                $file = $input['foto'];
                if($file != null)
                {
                    $bin = base64_decode($file);
                    $dt = Carbon::now();
                    $fileName = str_random(16).'-'.$dt->format('Y-m-d').'-berita_alumni-'.md5($input['judul']).'.jpg';
                    $test = Storage::disk('berita_alumni')->put($fileName, $bin);

                    $input['foto'] = $fileName;
                }

                else
                { unset ($input['foto']); }
            }
            
            if($parameter['additional']['mode_exec'] == 'Add')
            { $data_user = Category::create($input); }

            else
            { $data_user = Category::where('id', $input['id'])->where('is_active', 'Y')->update($input); }
            
            $errorCode = 200;
            $msg = $parameter['additional']['mode_exec'].' Category Success';   
        }
            
        catch(Exception $e) {
            $errorCode = 500;
            $msg = 'Error Occured on '.$parameter['additional']['mode_exec'].' Category';
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


