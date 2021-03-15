<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Libraries\UtilityAPI;
use App\Banner;

class BannerController extends Controller
{
    public function getList(Request $request)
    {
        $exclude = [];
        if($request->header('type') == 'web')
        { 
            $exclude = ['priority'];
            $componentAPI['defSort'] = array('priority', 'asc'); 
        }

        else
        {  $componentAPI['defSort'] = array('id', 'desc');  }
        
        $componentAPI += array (
            'apiToken'  => $request->header('apiToken'),
            'successMsg'=> 'Success Get Banner List',
            'mode'      => 'get',
            'offset'    => intval($request->header('offset')),
            'limit'     => intval($request->header('limit')),
            'sort'      => array($request->header('sort'), $request->header('sortType')),
            'searchData'=> $request->header('search'),
            'searchCol' => ['judul'],
            'type'      => $request->header('type'),
            'sql'       => Banner::exclude($exclude),
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
            'successMsg'=> 'Success Get Banner Detail',
            'mode'      => 'detail',
            'type'      => $request->header('type'),
            'sql'       => Banner::exclude([]),
            'addCase'   => array (
                ['and', 'id', '=', $id],
                ['and', 'is_active', '=', 'Y'],
            )
        );

        $response = new UtilityAPI();
        $response = $response->APIProcess($componentAPI);
        return $response;
        
    }
    
    public function addBanner(Request $request)
    {
        $inputVal = array (
            'banner'        => 'required',
            'priority'      => 'required',
        );

        $input = array (
            'judul'         => $request->judul,
            'banner'        => $request->banner,
            'link'          => $request->link,
            'desc_singkat'  => $request->desc_singkat,
            'priority'      => $request->priority,
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
        $function = new BannerController;
        $name = '_execBanner';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;      
    }

    public function editBanner(Request $request)
    {
        $inputVal = array (
            'id'            => 'required',
            'priority'      => 'required',
        );

        $input = array (
            'id'            => $request->id,
            'judul'         => $request->judul,
            'desc_singkat'  => $request->desc_singkat,
            'banner'        => $request->banner,
            'link'          => $request->link,
            'priority'      => $request->priority,
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
        $function = new BannerController;
        $name = '_execBanner';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;       
    }

    public function delBanner(Request $request)
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
        $function = new BannerController;
        $name = '_execBanner';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;      
    }

    public function _execBanner($input, $parameter)
    {
        $error = false;
        try {
            if(isset($input['banner']) && $parameter['additional']['mode_exec'] != 'Delete')
            {
                $file = $input['banner'];
                if($file != null)
                {
                    $bin = base64_decode($file);
                    $dt = Carbon::now();
                    $fileName = str_random(16).'-'.$dt->format('Y-m-d').'-banner-'.md5($input['judul']).'.jpg';
                    $test = Storage::disk('banner')->put($fileName, $bin);

                    $input['banner'] = $fileName;
                }
            }
            
            if($parameter['additional']['mode_exec'] == 'Add')
            { $data_user = Banner::create($input); }

            else
            { 
                $data_query = Banner::where('id', $input['id'])->where('is_active', 'Y');
                $data_image = $data_query->first();

                if(($parameter['additional']['mode_exec'] == 'Delete' || $input['banner'] != null) && isset($data_image['banner']))
                { Storage::disk('banner')->delete($data_image['banner']); }

                else
                { unset ($input['banner']); }

                $data_user = $data_query->update($input); 
            }
            
            $errorCode = 200;
            $msg = $parameter['additional']['mode_exec'].' Banner Success';   
        }
            
        catch(Exception $e) {
            $errorCode = 500;
            $msg = 'Error Occured on '.$parameter['additional']['mode_exec'].' Banner';
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


