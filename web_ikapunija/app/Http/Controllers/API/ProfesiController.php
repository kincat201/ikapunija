<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Libraries\UtilityAPI;
use App\Profesi;

class ProfesiController extends Controller
{
    public function getList(Request $request)
    {       
        $exclude = [];
        $componentAPI['defSort'] = array('id', 'asc'); 
                
        $componentAPI += array (
            'apiToken'  => $request->header('apiToken'),
            'successMsg'=> 'Success Get Profesi List',
            'mode'      => 'get',
            'offset'    => intval($request->header('offset')),
            'limit'     => intval($request->header('limit')),
            'sort'      => array($request->header('sort'), $request->header('sortType')),
            'searchData'=> $request->header('search'),
            'searchCol' => ['nama_profesi'],
            'type'      => $request->header('type'),
            'sql'       => Profesi::exclude($exclude),
            'addCase'   => array (
                ['and', 'is_active', '=', 'Y'],
            )
        );

        $response = new UtilityAPI();
        $response = $response->APIProcess($componentAPI);
        return $response;
    }
}


