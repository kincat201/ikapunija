<?php

namespace App\Http\Controllers\API\Mobile;

use App\Http\Controllers\Controller;
use App\Service\ResponseService;
use Illuminate\Http\Request;
use JWTAuth;
use App\UserAlumni;
use DB;

class ProfileController extends Controller
{
    public function detail(Request $request)
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

        $user = UserAlumni::with(['country','province','city','jurusan','prodi','interests.interest'])->find($user->id);

        return response()->json(ResponseService::ResponseSuccess('success get detail profile',$user));
    }
}


