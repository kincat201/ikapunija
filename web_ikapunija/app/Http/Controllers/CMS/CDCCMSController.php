<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Libraries\UtilityAPI;
use Illuminate\Http\Request;
use App\CDC;
use DataTables;

class CDCCMSController extends Controller
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
                    'successMsg'=> 'Success Get Pengumuman List',
                    'mode'      => 'get',
                    'offset'    => '',
                    'limit'     => '',
                    'sort'      => array('', ''),
                    'searchData'=> '',
                    'searchCol' => [],
                    'type'      => 'admin',
                    'sql'       => CDC::exclude($exclude)->with('category'),
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
                        ->addColumn('category', function($row){
                            $category = $row->category == null ? '-' : $row->category->nama;
                            return $category;
                        })
                        ->make(true);
            }

            return view('cms/loker', compact('username'));
        }

        else
        { return redirect('/sign_out'); }
    }
}
