<?php

namespace App\Http\Controllers\API\Mobile;

use App\Http\Controllers\Controller;
use App\Service\CompanyService;
use App\Service\ResponseService;
use App\Service\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\UserAlumni;

class ProfileController extends Controller
{
    public function detail($id)
    {
        $data = UserAlumni::with(['country','province','city','jurusan','prodi','profession','interests.interest','occupation'])->find($id);
        $data->companyName = !empty($data->occupation) ? $data->occupation->name : "-";

        if(empty($data)) return response()->json(ResponseService::ResponseError('Alumni not found!',200));

        $data->foto_profil = env('APP_ASSET').'user_alumni/profil/'.(!empty($data->foto_profil) ? $data->foto_profil : 'default.png');

        return response()->json(ResponseService::ResponseSuccess('success get detail profile',$data));
    }

    public function update(Request $request)
    {
        $validate_rule = [
            'nama_alumni'   => 'required',
            'angkatan'      => 'required|numeric',
            'jurusan_id'    => 'required',
            'profesi_id'    => 'required',
            'nama_profesi'    => 'required',
            'company'    => 'required',
            'contact'    => 'required',
            'last_education'    => 'required',
        ];

        if($request->has('photo')){
            $validate_rule['photo'] = 'max:9600|mimes:jpeg,jpg,png,JPG,JPEG,PNG';
        }

        $validator = Validator::make($request->all(), $validate_rule);

        if($validator->fails()){
            return response()->json(ResponseService::ResponseError('Invalid Payload', $validator->errors()),200);
        }

        $data = UserAlumni::find(\Auth::user()->id);
        $data->fill((array) $request->all());

        if($request->has('company')){
            $data->company = CompanyService::CheckCompanyExist($request->company);
        }

        if($request->has('photo')){
            if(!empty($data->foto_profil) && file_exists('asset_image/user_alumni/profil/'.$data->foto_profil) && ($data->foto_profil != 'default.png')){
                unlink('asset_image/user_alumni/profil/'.$data->foto_profil);
            }
            $dt = Carbon::now();
            $extension = $request->file('photo')->getClientOriginalExtension();
            $fileName = str_random(16).'-'.$dt->format('Y-m-d').'-profil-'.md5($data->email).'.'.$extension;
            Storage::disk('profil')->put($fileName, file_get_contents($request->file('photo')));
            $data->foto_profil = $fileName;
        }

        $data->save();

        $data = UserAlumni::with(['country','province','city','jurusan','prodi','profession','interests.interest'])->find(\Auth::user()->id);

        $data->foto_profil = env('APP_ASSET').'user_alumni/profil/'.(!empty($data->foto_profil) ? $data->foto_profil : 'default.png');

        return response()->json(ResponseService::ResponseSuccess('Success edit data!',$data));
    }

    public function setDeviceToken(Request $request)
    {

        $validator = Validator::make($request->all(), [
            //'device_token'=> 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(ResponseService::ResponseError('Invalid Payload', $validator->errors()),200);
        }

        $user = \Auth::user();

        if(empty($user->id)) return response()->json(ResponseService::ResponseError('User not valid!'),200);

        UserService::SaveDeviceToken($user->id,$request->device_token);

        return response()->json(ResponseService::ResponseSuccess('Berhasil set device token.'));
    }

}


