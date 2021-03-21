<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Service\UserService;
use Illuminate\Http\Request;
use App\Libraries\UtilityAPI;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Carbon\Carbon;
use App\UserAlumni;
use App\UserAccess;
use App\UserAdmin;
use Illuminate\Support\Facades\Storage;
use DB;

class UserAlumniController extends Controller
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
        }
        
        $componentAPI += array (
            'apiToken'  => $request->header('apiToken'),
            'email'     => $request->header('email'),
            'token'     => $request->header('token'),
            'successMsg'=> 'Success Get Agenda List',
            'mode'      => 'get',
            'offset'    => intval($request->header('offset')),
            'limit'     => intval($request->header('limit')),
            'sort'      => array($request->header('sort'), $request->header('sortType')),
            'searchData'=> $request->header('search'),
            'searchCol' => ['judul', 'review_singkat'],
            'type'      => 'admin',
            'sql'       => UserAlumni::exclude($exclude)->with('category'),
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
            'email'     => $request->header('email'),
            'token'     => $request->header('token'),
            'successMsg'=> 'Success Get Alumni Detail',
            'mode'      => 'detail',
            'type'      => 'admin',
            'sql'       => UserAlumni::exclude([]),
            'addCase'   => array (
                ['and', 'id', '=', $id],
            )
        );

        $response = new UtilityAPI();
        $response = $response->APIProcess($componentAPI);
        return $response;
    }

    public function register(Request $request)
    {
        if($request->mode == 'admin')
        { $input['is_active'] = "Y"; }

        else
        { $input['is_active'] = "V"; }

        $inputVal = array (
            'email'         => 'required|email',
            'password'      => 'required',
            'nama_alumni'   => 'required',
            'contact'       => 'required',
            'alamat'        => 'required',
            'angkatan'      => 'required',
            'jurusan_id'    => 'required',
            'prodi_id'      => 'required',
            'negara_id'     => 'required',
            'profesi_id'    => 'required',
            'pendapatan'    => 'required',
            'foto_ktp'      => 'required',
            'foto_profil'   => 'required',
            'nik'           => 'required',
        );

        $input += array (
            'email'         => $request->email,
            'password'      => md5($request->password),
            'nama_alumni'   => $request->nama_alumni,
            'contact'       => $request->contact,
            'alamat'        => $request->alamat,
            'angkatan'      => $request->angkatan,
            'jurusan_id'    => $request->jurusan_id,
            'prodi_id'      => $request->prodi_id,
            'negara_id'     => $request->negara_id,
            'hobi'          => $request->hobi,
            'profesi_id'    => $request->profesi_id,
            'nama_profesi'  => $request->nama_profesi,
            'jumlah_pegawai'=> $request->jumlah_pegawai,
            'pendapatan'    => $request->pendapatan,
            'foto_ktp'      => $request->foto_ktp,
            'foto_profil'   => $request->foto_profil,
            'nik'           => $request->nik,
            'active_code'   => md5(date("Y-m-d H:i:s")),
            'created_at'    => date("Y-m-d H:i:s"),
            'updated_at'    => date("Y-m-d H:i:s"),
        );

        $parameter = array (
            'type'          => $request->mode,
            'apiToken'      => $request->header('apiToken'),
            'email'         => $request->header('email'),
            'token'         => $request->header('token'),
            'additional'    => array (
                'mode_exec' => 'Add Profil',
            )
        );

        $response = new UtilityAPI();
        $name = '_execAlumni';
        $function = new UserAlumniController;
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;      
    }

    public function update_user(Request $request)
    {
        $inputVal = $input = [];
        if($request->mode == 'admin')
        { 
            $input['id'] = $request->id; 
            $inputVal['id'] = 'required';
        }

        
        $inputVal += array (
            'nama_alumni'   => 'required',
            'contact'       => 'required',
            'alamat'        => 'required',
            'angkatan'      => 'required',
            'jurusan_id'    => 'required',
            'prodi_id'      => 'required',
            'negara_id'     => 'required',
            'profesi_id'    => 'required',
            'pendapatan'    => 'required',
            'nik'           => 'required',
        );

        $input += array (
            'password'      => md5($request->password),
            'nama_alumni'   => $request->nama_alumni,
            'contact'       => $request->contact,
            'alamat'        => $request->alamat,
            'angkatan'      => $request->angkatan,
            'jurusan_id'    => $request->jurusan_id,
            'prodi_id'      => $request->prodi_id,
            'negara_id'     => $request->negara_id,
            'hobi'          => $request->hobi,
            'profesi_id'    => $request->profesi_id,
            'nama_profesi'  => $request->nama_profesi,
            'jumlah_pegawai'=> $request->jumlah_pegawai,
            'pendapatan'    => $request->pendapatan,
            'foto_ktp'      => $request->foto_ktp,
            'foto_profil'   => $request->foto_profil,
            'nik'           => $request->nik,
            'updated_at'    => date("Y-m-d H:i:s"),
        );

        $parameter = array (
            'type'          => $request->mode,
            'apiToken'      => $request->header('apiToken'),
            'email'         => $request->header('email'),
            'token'         => $request->header('token'),
            'additional'    => array (
                'mode_exec' => 'Edit',
            )
        );

        $response = new UtilityAPI();
        $name = '_execAlumni';
        $function = new UserAlumniController;
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;      
    }

    public function approveAlumni(Request $request)
    {
        $inputVal = array (
            'id'            => 'required',
        );

        $input = array (
            'id'            => $request->id,
            'is_active'     => 'Y',
            'updated_at'    => date('Y-m-d H:i:s'),
        );

        $parameter = array (
            'type'          => 'admin',
            'approval'      => 'yes',
            'apiToken'      => $request->header('apiToken'),
            'email'         => $request->header('email'),
            'token'         => $request->header('token'),
            'additional'    => array (
                'mode_exec' => 'Approve',
            )
        );

        $response = new UtilityAPI();
        $function = new UserAlumniController;
        $name = '_execAlumni';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;      
    }

    public function declineAlumni(Request $request)
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
            'approval'      => 'yes',
            'apiToken'      => $request->header('apiToken'),
            'email'         => $request->header('email'),
            'token'         => $request->header('token'),
            'additional'    => array (
                'mode_exec' => 'Delete',
            )
        );

        $response = new UtilityAPI();
        $function = new UserAlumniController;
        $name = '_execAlumni';
        $response = $response->UpdateData($inputVal, $input, $parameter, $function, $name);
        return $response;      
    }

    public function _execAlumni($input, $parameter)
    {
        $error = false;     
        if($parameter['additional']['mode_exec'] == 'Add Profil')
        { $data_user = UserAlumni::where('email', $input['email'])->where('is_active', 'Y')->first(); }
        
        else
        { $data_user = null; }

        if($data_user !== null)
        {
            $errorCode = 401;
            $msg = 'Email sudah digunakan'; 
        }
         
        else if(!isset($parameter['mode']))
        {
            try {
                if($parameter['additional']['mode_exec'] == 'Edit' && $parameter['type'] == 'user')
                { $input['email'] = $parameter['email']; }
                
                if(isset($input['foto_ktp']) && $parameter['additional']['mode_exec'] != 'Delete')
                {
                    $file = $input['foto_ktp'];
                    if($file != null)
                    {
                        $bin = base64_decode($file);
                        $dt = Carbon::now();
                        $fileName = str_random(16).'-'.$dt->format('Y-m-d').'-ktp-'.md5($input['email']).'.jpg';
                        $test = Storage::disk('ktp')->put($fileName, $bin);

                        $input['foto_ktp'] = $fileName;
                    }

                    else
                    { $input['foto_ktp'] = ''; }
                }

                if(isset($input['foto_profil']) && $parameter['additional']['mode_exec'] != 'Delete')
                {

                    $file = $input['foto_profil'];
                    if($file != null)
                    {
                        $bin = base64_decode($file);
                        $dt = Carbon::now();
                        $fileName = str_random(16).'-'.$dt->format('Y-m-d').'-profil-'.md5($input['email']).'.jpg';
                        $test = Storage::disk('profil')->put($fileName, $bin);

                        $input['foto_profil'] = $fileName;
                    }

                    else
                    { $input['foto_profil'] = ''; }
                }

                if($parameter['additional']['mode_exec'] == 'Add Profil')
                { 
                    $data_user = UserAlumni::insert($input);
                    $errorCode = 200;

                    if($parameter['type'] == 'admin')
                    { $msg = $parameter['additional']['mode_exec'].' Alumni Success'; }

                    else
                    {
                        $dataUser = array(
                            'name'      => $input['nama_alumni'], 
                            'code'      => $input['active_code'], 
                            'email'     => $input['email'],
                            'subject'   => 'Verifikasi Akun', 
                            'perihal'   => 'untuk verifikasi akun di Website Ikapunija', 
                        );
        
                        Mail::to($input['email'])
                        ->send(new SendMail($dataUser));
                        $msg = 'Register Success, Please check your email !';

                        UserService::SendVerificationNewAlumni($data_user);
                    }
                }

                else
                {                     
                    if($parameter['type'] == 'admin')
                    { $data_query = UserAlumni::where('id', $input['id']); }

                    else
                    { 
                        $data_query = UserAlumni::where('email', $parameter['email']); 
                        unset ($input['email']);
                    }

                    if(!isset($parameter['approval']))
                    { 
                        $data_query = $data_query->where('is_active', 'Y'); 
                        $data_image = $data_query->first();

                        if(($parameter['additional']['mode_exec'] == 'Delete' || $input['foto_ktp'] != null) && isset($data_image['foto_ktp']))
                        { Storage::disk('ktp')->delete($data_image['foto_ktp']); }

                        else
                        { unset ($input['foto_ktp']); }

                        if(($parameter['additional']['mode_exec'] == 'Delete' || $input['foto_profil'] != null) && isset($data_image['foto_profil']))
                        { Storage::disk('profil')->delete($data_image['foto_profil']); }
                        
                        else
                        { unset ($input['foto_profil']); }
                    }
                  
                    if($parameter['additional']['mode_exec'] == 'Approve' || $parameter['additional']['mode_exec'] == 'Delete')
                    {
                        $dataApproval = UserAlumni::select('nama_alumni', 'email')->where('id', $input['id'])->where('is_active', 'P')->first();

                        if($dataApproval !=  null)
                        {
                            $dataUser = array(
                                'name'      => $dataApproval->nama_alumni, 
                                'code '     => '0',
                                'email'     => $dataApproval->email,
                                'subject'   => $parameter['additional']['mode_exec'] == 'Approve' ? 'Approval Akun' : 'Decline Akun', 
                                'perihal'   => 'approval akun di Website Ikapunija', 
                            );
                            //dd($dataUser);
                            Mail::to($dataApproval->email)
                            ->send(new SendMail($dataUser));
                            $msg = $dataUser['subject'].' Success, Please check your email !';
                        }  
                    }

                    $data_user = $data_query->update($input);
                    $errorCode = 200;
                    $msg = $parameter['additional']['mode_exec'].' Alumni Success'; 
                }
     
            }
                
            catch(Exception $e) {
                $errorCode = 500;
                $msg = 'Error Occured on Register';
            }
        }

        else
        { $errorCode = 400; }

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

    public function profile(Request $request)
    {
        $componentAPI = array (
            'apiToken'  => $request->header('apiToken'),
            'email'     => $request->header('email'),
            'token'     => $request->header('token'),
            'successMsg'=> 'Success Get Profile Detail',
            'mode'      => 'detail',
            'type'      => $request->header('type'),
            'sql'       => UserAlumni::exclude([]),
            'addCase'   => array (
                ['and', 'email', '=', $request->header('email')],
                ['and', 'is_active', '=', 'Y'],
            )
        );
        
        $response = new UtilityAPI();
        $response = $response->APIProcess($componentAPI);
        return $response;

    }
}


