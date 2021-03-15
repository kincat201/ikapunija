<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Libraries\UtilityAPI;
use App\Struktur;

class StrukturController extends Controller
{
    public function getList(Request $request)
    {
        $exclude = [];
        $type = $request->header('typeLevel');
        
        if($request->header('type') == 'web')
        { 
            $exclude = [];
            $componentAPI['defSort'] = array('struktur_organisasi.level', 'asc'); 
        }

        else
        {  
            $exclude = [];
            $componentAPI['defSort'] = array('id', 'desc');  
        }
        
        $componentAPI += array (
            'apiToken'  => $request->header('apiToken'),
            'successMsg'=> 'Success Get Struktur List',
            'mode'      => 'get',
            'offset'    => intval($request->header('offset')),
            'limit'     => intval($request->header('limit')),
            'sort'      => array($request->header('sort'), $request->header('sortType')),
            'searchData'=> $request->header('search'),
            'searchCol' => [],
            'type'      => $request->header('type'),
            'sql'       => Struktur::exclude($exclude)->select('struktur_organisasi.*','struktur_level.level', 'struktur_level.nama_level')->join('struktur_level', 'struktur_level.id', 'struktur_organisasi.level'),
            'addCase'   => array (
                ['and', 'struktur_organisasi.is_active', '=', 'Y'],
                ['and', 'struktur_level.type_level', '=', $type],
            )
        );

        $response = new UtilityAPI();
        $response = $response->APIProcess($componentAPI);

        if($componentAPI['type'] == 'web')
        {
            $res = substr( $response, 129 ) ;
            $res = json_decode($res);
            $data = $res->Data;
            $arData = [];
            foreach($data as $datas)
            { 
                $level = $datas->level;
                unset($datas->level);

                if(!isset($arData[$level-1]))
                { $temp = Array(
                        "id" => $level,
                        "list" => [$datas],
                        "namaLevel" => $datas->nama_level
                    );
                    array_push($arData, $temp);
                }
                else
                { array_push($arData[$level-1]['list'], $datas); }   
                //array_push($arData, $datas->level->level);
            }

            $res->Data = $arData;
            return response()->json($res);
        }

        return $response;
    }

    public function getDetail(Request $request, $id)
    {
        $componentAPI = array (
            'apiToken'  => $request->header('apiToken'),
            'successMsg'=> 'Success Get Struktur Detail',
            'mode'      => 'detail',
            'type'      => $request->header('type'),
            'sql'       => Struktur::exclude([])->with('level'),
            'addCase'   => array (
                ['and', 'id', '=', $id],
                ['and', 'is_active', '=', 'Y'],
            )
        );

        $response = new UtilityAPI();
        $response = $response->APIProcess($componentAPI);
        
        return $response;
    }
    
    public function addStruktur(Request $request)
    {
        $inputVal = array (
            'nama'          => 'required',
            'foto'          => 'required',
            'nama_jabatan'  => 'required',
            'level'         => 'required',
        );

        $input = array (
            'nama'          => $request->nama,
            'foto'          => $request->foto,
            'nama_jabatan'  => $request->nama_jabatan,
            'level'         => $request->level,
            'email'         => $request->email,
            'ig'            => $request->ig,
            'fb'            => $request->fb,
            'twitter'       => $request->twitter,
            'linkedin'      => $request->linkedin,
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
        $function = new StrukturController;
        $name = '_execStruktur';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;      
    }

    public function editStruktur(Request $request)
    {
        $inputVal = array (
            'id'            => 'required',
            'nama'          => 'required',
            'nama_jabatan'  => 'required',
            'level'         => 'required',
        );

        $input = array (
            'id'            => $request->id,
            'nama'          => $request->nama,
            'foto'          => $request->foto,
            'nama_jabatan'  => $request->nama_jabatan,
            'level'         => $request->level,
            'email'         => $request->email,
            'ig'            => $request->ig,
            'fb'            => $request->fb,
            'twitter'       => $request->twitter,
            'linkedin'      => $request->linkedin,
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
        $function = new StrukturController;
        $name = '_execStruktur';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);        
        return $response;       
    }

    public function delStruktur(Request $request)
    {
        $inputVal = array (
            'id'            => 'required',
        );

        $input = array (
            'id'            => $request->id,
            'foto'          => '',
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
        $function = new StrukturController;
        $name = '_execStruktur';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;      
    }

    public function _execStruktur($input, $parameter)
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
                    $fileName = str_random(16).'-'.$dt->format('Y-m-d').'-foto_struktur-'.md5($input['nama']).'.jpg';
                    $test = Storage::disk('foto_struktur')->put($fileName, $bin);

                    $input['foto'] = $fileName;
                }
            }
            
            if($parameter['additional']['mode_exec'] == 'Add')
            { $data_user = Struktur::create($input); }

            else
            { 
                $data_query = Struktur::where('id', $input['id'])->where('is_active', 'Y');
                $data_image = $data_query->first();

                if(($parameter['additional']['mode_exec'] == 'Delete' || $input['foto'] != null) && isset($data_image['foto']))
                { Storage::disk('foto_struktur')->delete($data_image['foto']); }
               
                else
                { unset ($input['foto']); }

                $data_user = $data_query->update($input); 
            }
            
            $errorCode = 200;
            $msg = $parameter['additional']['mode_exec'].' Struktur Success';   
        }
            
        catch(Exception $e) {
            $errorCode = 500;
            $msg = 'Error Occured on '.$parameter['additional']['mode_exec'].' Struktur';
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


