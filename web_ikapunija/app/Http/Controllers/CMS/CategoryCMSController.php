<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Libraries\UtilityAPI;
use Illuminate\Http\Request;
use App\Category;
use DataTables;

class CategoryCMSController extends Controller
{
    public function index(Request $request)
    {
        if($request->session()->has('nama'))       
        {
            $username = session('nama');

            if ($request->ajax()) {
                $componentAPI['defSort'] = array('id', 'desc');  
                $componentAPI += array (
                    'apiToken'  => env('APPS_KEY'),
                    'email'     => session('email'),
                    'token'     => session('token'),
                    'successMsg'=> 'Success Get Category List',
                    'mode'      => 'get',
                    'offset'    => '',
                    'limit'     => '',
                    'sort'      => array('', ''),
                    'searchData'=> '',
                    'searchCol' => [],
                    'type'      => 'admin',
                    'sql'       => Category::exclude([]),
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
                        ->addColumn('name_type', function($row){
                            switch($row->type){
                                case 'loker' : 
                                    $type = 'CDC';
                                    break;
                                case 'berita' : 
                                    $type = 'Berita';
                                    break;
                                case 'announce' : 
                                    $type = 'Pengumuman';
                                    break;
                                case 'agenda' : 
                                    $type = 'Agenda';
                                    break;
                                default : 
                                    $type = 'Semua';
                                    break;
                            }
                            return $type;
                        })
                        ->make(true);
            }

            return view('cms/category', compact('username'));
        }

        else
        { return redirect('/sign_out'); }
    }
}
