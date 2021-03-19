<?php

namespace App\Http\Controllers\API\Mobile;

use App\Http\Controllers\Controller;
use App\Service\ResponseService;
use App\Service\UserService;
use App\Util\Constant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Libraries\UtilityAPI;
use App\UserAlumni;
use App\UserAccess;
use App\UserAdmin;
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

        try {
            if (!$token=JWTAuth::fromUser($user)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        UserService::GenerateUserLog($user->id,$user->email,Constant::USER_LOG_USER_MODE,str_random(32));

        return response()->json(ResponseService::ResponseSuccess('Berhasil registrasi.',compact('user','token')));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json(ResponseService::ResponseSuccess('Berhasil registrasi.',compact('user','token')));
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


