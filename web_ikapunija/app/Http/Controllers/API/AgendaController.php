<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Libraries\UtilityAPI;
use App\Agenda;

class AgendaController extends Controller
{
    public function getList(Request $request)
    {
        $exclude = [];
        if($request->header('type') == 'web')
        { 
            $exclude = ['isi', 'priority'];
            $componentAPI['defSort'] = array('priority', 'asc'); 
        }

        else
        {  
            $exclude = ['isi'];
            $componentAPI['defSort'] = array('id', 'desc');  
            $componentAPI['type'] = $request->type;  
            $componentAPI['apiToken'] = $request->apiToken;  
        }
        
        $componentAPI += array (
            'apiToken'  => $request->header('apiToken'),
            'successMsg'=> 'Success Get Agenda List',
            'mode'      => 'get',
            'offset'    => intval($request->header('offset')),
            'limit'     => intval($request->header('limit')),
            'sort'      => array($request->header('sort'), $request->header('sortType')),
            'searchData'=> $request->header('search'),
            'searchCol' => ['judul', 'review_singkat'],
            'type'      => $request->header('type'),
            'sql'       => Agenda::exclude($exclude)->with('category'),
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
            'successMsg'=> 'Success Get Agenda Detail',
            'mode'      => 'detail',
            'type'      => $request->header('type'),
            'sql'       => Agenda::exclude([]),
            'addCase'   => array (
                ['and', 'id', '=', $id],
                ['and', 'is_active', '=', 'Y'],
            )
        );

        $response = new UtilityAPI();
        $response = $response->APIProcess($componentAPI);
        return $response;
    }
    
    public function addAgenda(Request $request)
    {
        $_SESSION['apiToken'] = env('APPS_KEY');

        $inputVal = array (
            'judul'         => 'required',
            'review_singkat'=> 'required',
            'foto'          => 'required',
            'isi'           => 'required',
            'category'      => 'required',
            'priority'      => 'required',
        );

        $input = array (
            'judul'         => $request->judul,
            'review_singkat'=> $request->review_singkat,
            'foto'          => $request->foto,
            'isi'           => $request->isi,
            'priority'      => $request->priority,
            'category'      => $request->category,
            'tanggal'       => date('Y-m-d H:i:s'),
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
        $function = new AgendaController;
        $name = '_execAgenda';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;      
    }

    public function editAgenda(Request $request)
    {
        $inputVal = array (
            'judul'         => 'required',
            'review_singkat'=> 'required',
            'isi'           => 'required',
            'category'      => 'required',
            'priority'      => 'required',
            'id'            => 'required',
        );

        $input = array (
            'judul'         => $request->judul,
            'id'            => $request->id,
            'review_singkat'=> $request->review_singkat,
            'foto'          => $request->foto,
            'isi'           => $request->isi,
            'priority'      => $request->priority,
            'category'      => $request->category,
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
        $function = new AgendaController;
        $name = '_execAgenda';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;      
    }

    public function delAgenda(Request $request)
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
        $function = new AgendaController;
        $name = '_execAgenda';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;      
    }

    public function _execAgenda($input, $parameter)
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
                    $fileName = str_random(16).'-'.$dt->format('Y-m-d').'-agenda-'.md5($input['judul']).'.jpg';
                    $test = Storage::disk('agenda')->put($fileName, $bin);

                    $input['foto'] = $fileName;
                }
            }
            
            if($parameter['additional']['mode_exec'] == 'Add')
            { $data_user = Agenda::create($input); }

            else
            { 
                $data_query = Agenda::where('id', $input['id'])->where('is_active', 'Y');
                $data_image = $data_query->first();

                if(($parameter['additional']['mode_exec'] == 'Delete' || $input['foto'] != null) && isset($data_image['foto']))
                { Storage::disk('agenda')->delete($data_image['foto']); }

                else
                { unset ($input['foto']); }

                $data_user = $data_query->update($input); 
            }
            
            $errorCode = 200;
            $msg = $parameter['additional']['mode_exec'].' Agenda Success';   
        }
            
        catch(Exception $e) {
            $errorCode = 500;
            $msg = 'Error Occured on '.$parameter['additional']['mode_exec'].' Agenda';
        }

        $error = false;
        
        if($errorCode != 200)
        {  $error = true; }

        $resp = array (
            'code'  => $errorCode,
            'error' => $error,
            'msg'   => $msg,
            'data'  => $data_user
        );

        return $resp;
    }
}



