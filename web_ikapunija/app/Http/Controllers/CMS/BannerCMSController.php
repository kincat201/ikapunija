<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Libraries\UtilityAPI;
use Illuminate\Http\Request;
use App\Banner;
use DataTables;

class BannerCMSController extends Controller
{
    public function index(Request $request)
    {
        if($request->session()->has('nama'))       
        {
            $username = session('nama');
            if ($request->ajax()) {
                
                $exclude = ['isi'];
                $componentAPI['defSort'] = array('id', 'desc');  
                $componentAPI += array (
                    'apiToken'  => env('APPS_KEY'),
                    'email'     => session('email'),
                    'token'     => session('token'),
                    'successMsg'=> 'Success Get Banner List',
                    'mode'      => 'get',
                    'offset'    => '',
                    'limit'     => '',
                    'sort'      => array('', ''),
                    'searchData'=> '',
                    'searchCol' => [],
                    'type'      => 'admin',
                    'sql'       => Banner::exclude($exclude),
                    'addCase'   => array (
                        ['and', 'is_active', '=', 'Y'], 
                    )
                );
        
                $response = new UtilityAPI();
                $response = $response->APIProcess($componentAPI);
                $res = json_decode($response);

                if($res == null)
                {  $tableData = []; }

                else
                {  $tableData = $res; }
                
                return Datatables::of($tableData)
                        ->addIndexColumn()
                        ->make(true);
            }

            return view('cms/banner', compact('username'));
        }

        else
        { return redirect('/sign_out'); }
    }
}
