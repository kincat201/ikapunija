<?php

namespace App\Http\Controllers\API\Mobile;

use App\Http\Controllers\Controller;
use App\InterestAlumni;
use App\Service\CompanyService;
use App\Service\ResponseService;
use App\Service\UserService;
use App\Util\Constant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\UserAlumni;
use DB;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|string|max:255',
            'password'=> 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(ResponseService::ResponseError('Invalid Payload', $validator->errors()),200);
        }

        $user = UserAlumni::where([
            'email' => $request->email,
            'password' => md5($request->password)
        ])->first();

        if(empty($user)){
            return response()->json(ResponseService::ResponseError('Email atau password salah, jika pernah mengisi data melalui gform silahkan reset password dengan fitur lupa password'),200);
        }

        if($user->is_active == Constant::ACTIVE_STATUS_VERIFICATION){
            return response()->json(ResponseService::ResponseError('Akun anda belum diverifikasi, mohon verifikasi di email anda!'),200);
        }

        if($user->is_active != Constant::ACTIVE_STATUS_YES){
            return response()->json(ResponseService::ResponseError('Akun anda berstatus '.Constant::ACTIVE_STATUS_LIST[$user->is_active].', mohon hubungi admin!'),200);
        }

        try {
            if (!$token=JWTAuth::fromUser($user)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        UserService::GenerateUserLog($user->id,$user->email,Constant::USER_LOG_USER_MODE,str_random(32));
        if($request->has('device_token')){
            UserService::SaveDeviceToken($user->id,$request->device_token);
            $user->device_token = $request->device_token;
        }

        return response()->json(ResponseService::ResponseSuccess('Berhasil authentikasi.',compact('user','token')));
    }

    public function register(Request $request)
    {
        $validate_rule = [
            'email'         => 'required|email|unique:user_alumni,email',
            'password'      => 'required|min:6',
            'nama_alumni'   => 'required',
            'angkatan'      => 'required|numeric',
            'jurusan_id'    => 'required',
            'negara_id'      => 'required',
            'prodi_id'      => 'required',
            'profesi_id'    => 'required',
            'company'=> 'required',
            //'interest_list'    => 'required|array|min:1',
            'last_education'    => 'required',
        ];

        if($request->has('photo')){
            $validate_rule['photo'] = 'max:9600|mimes:jpeg,jpg,png,JPG,JPEG,PNG';
        }

        $validator = Validator::make($request->all(), $validate_rule);

        if($validator->fails()){
            return response()->json(ResponseService::ResponseError('Invalid Payload', $validator->errors()),200);
        }

        \DB::beginTransaction();

        try{
            $alumni = new UserAlumni();
            $alumni->fill((array) $request->all());
            $alumni->password = md5($request->password);
            $alumni->active_code = md5(date("Y-m-d H:i:s"));
            $alumni->is_active = Constant::ACTIVE_STATUS_VERIFICATION;

            if($request->has('company')){
                $alumni->company = CompanyService::CheckCompanyExist($request->company);
            }

            if($request->has('photo')){
                $dt = Carbon::now();
                $extension = $request->file('photo')->getClientOriginalExtension();
                $fileName = str_random(16).'-'.$dt->format('Y-m-d').'-profil-'.md5($alumni->email).'.'.$extension;
                Storage::disk('profil')->put($fileName, file_get_contents($request->file('photo')));
                $alumni->foto_profil = $fileName;
            }else{
                $alumni->foto_profil = 'default.png';
            }

            $alumni->save();

            if($request->has('interest_list')){

                $alumniIntereset = [];
                foreach ($request->interest_list as $interest){
                    $alumniIntereset[]=[
                        'alumni_id'=>$alumni->id,
                        'interest_id'=>$interest,
                        'created_at'=>Carbon::now(),
                        'updated_at'=>Carbon::now(),
                    ];
                }

                InterestAlumni::insert($alumniIntereset);
            }

            UserService::SendVerificationNewAlumni($alumni);

            \DB::commit();

            return response()->json(ResponseService::ResponseSuccess('Berhasil registrasi, mohon verifikasi email anda!',['email'=>$alumni->email]));

        } catch (\Exception $e){
            \Log::info($e);
            \DB::rollback();
            return response()->json(ResponseService::ResponseError('Gagal Registrasi mohon coba lagi nanti!'),500);
        }
    }

    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        return response()->json(compact('user'));
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'=> 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(ResponseService::ResponseError('Invalid Payload', $validator->errors()),200);
        }

        $user = UserAlumni::where('email',$request->email)->where('is_active','Y')->first();
        if(empty($user->id)) return response()->json(ResponseService::ResponseError('Email Yang Anda Masukkan Tidak Terdaftar'),200);

        UserService::SendForgotPassword($user);

        return response()->json(ResponseService::ResponseSuccess('Pengaturan reset password telah dikirimkan ke email anda.',['email'=>$user->email]));
    }

    public function verifyForgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'=> 'required',
            'verify_code'=> 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(ResponseService::ResponseError('Invalid Payload', $validator->errors()),200);
        }

        $data = UserAlumni::where('email',$request->email)->where('is_active','Y')->first();
        if(empty($data->id)) return response()->json(ResponseService::ResponseError('Akun Tidak Tersedia'),200);

        $verifyPassword = UserService::VerifyForgotPassword($data,$request->verify_code);

        if($verifyPassword['status']){
            return response()->json(ResponseService::ResponseSuccess($verifyPassword['message']),200);
        }else{
            return response()->json(ResponseService::ResponseError($verifyPassword['message']),200);
        }

    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|max:255',
            'verify_code' => 'required',
            'password'=> 'required|confirmed|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json(ResponseService::ResponseError('Invalid Payload', $validator->errors()),200);
        }

        $data = UserAlumni::where('email',$request->email)->where('is_active','B')->first();
        if(empty($data->id)) return response()->json(ResponseService::ResponseError('Akun Tidak Tersedia'),200);

        $verifyPassword = UserService::UpdateForgotPassword($data,$request->verify_code,$request->password);

        if($verifyPassword['status']){
            return response()->json(ResponseService::ResponseSuccess($verifyPassword['message']),200);
        }else{
            return response()->json(ResponseService::ResponseError($verifyPassword['message']),200);
        }
    }
}


