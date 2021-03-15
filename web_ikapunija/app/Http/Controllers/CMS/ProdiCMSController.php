<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Libraries\UtilityAPI;
use Illuminate\Http\Request;
use App\Prodi;
use DataTables;

class ProdiCMSController extends Controller
{
    public function index(Request $request)
    {
        if($request->session()->has('nama'))       
        {
            $username = session('nama');

            if ($request->ajax()) {
                if($request->jurusan == '' || $request->jurusan == null) {
                    $caseJurusan = [];
                }
                else
                { $caseJurusan = ['and', 'id_jurusan', '=', $request->jurusan]; }

                $exclude = ['isi'];
                $componentAPI['defSort'] = array('id', 'desc');  
                $componentAPI += array (
                    'apiToken'  => env('APPS_KEY'),
                    'email'     => session('email'),
                    'token'     => session('token'),
                    'successMsg'=> 'Success Get Prodi List',
                    'mode'      => 'get',
                    'offset'    => '',
                    'limit'     => '',
                    'sort'      => array('', ''),
                    'searchData'=> '',
                    'searchCol' => [],
                    'type'      => 'admin',
                    'sql'       => Prodi::with('jurusan'),
                    'addCase'   => array (
                        $caseJurusan,
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
                        ->addColumn('jurusan', function($row){
                            $jurusan = $row->jurusan == null ? '-' : $row->jurusan->nama_jurusan;
                            return $jurusan;
                        })
                        ->addColumn('id_jurusan', function($row){
                            $id_jurusan = $row->jurusan == null ? '-' : $row->jurusan->id;
                            return $id_jurusan;
                        })
                        ->rawColumns(['jurusan', 'id_jurusan'])
                        ->make(true);
            }

            return view('cms/prodi', compact('username'));
        }

        else
        { return redirect('/sign_out'); }
    }
}
