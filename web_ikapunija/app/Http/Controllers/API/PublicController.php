<?php

namespace App\Http\Controllers\API;

use App\City;
use App\Country;
use App\Http\Controllers\Controller;
use App\Interest;
use App\Jurusan;
use App\Prodi;
use App\Profesi;
use App\Province;
use App\Service\CommonService;
use App\Service\ResponseService;
use App\Util\Constant;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PublicController extends Controller
{
    public function interestList(Request $request) {
        $option = CommonService::GenerateDefaultOption($request);
        $model = Interest::active()->select('id','name','description');

        if (!empty($request->filter['search'])) {

            $search = $request->filter['search'];

            $model->where(function($q) use ($search) {
                $q->orWhere('name', 'like', '%' . $search . '%');
                $q->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $datas = CommonService::GenerateListModel($model, $option);
        $result = [];

        foreach ($datas['list'] as $row) {
            $result[] = $row;
        }

        return response()->json(ResponseService::ResponseList($result, $option, $datas['total'], $request));
    }

    public function professionList(Request $request) {
        $option = CommonService::GenerateDefaultOption($request);
        $model = Profesi::active()->select('id','nama_profesi');

        if (!empty($request->filter['search'])) {

            $search = $request->filter['search'];

            $model->where(function($q) use ($search) {
                $q->orWhere('nama_profesi', 'like', '%' . $search . '%');
            });
        }

        $datas = CommonService::GenerateListModel($model, $option);
        $result = [];

        foreach ($datas['list'] as $row) {
            $result[] = $row;
        }

        return response()->json(ResponseService::ResponseList($result, $option, $datas['total'], $request));
    }

    public function facultyList(Request $request) {
        $option = CommonService::GenerateDefaultOption($request);
        $model = Jurusan::active()->select('id','nama_jurusan');

        if (!empty($request->filter['search'])) {

            $search = $request->filter['search'];

            $model->where(function($q) use ($search) {
                $q->orWhere('nama_jurusan', 'like', '%' . $search . '%');
            });
        }

        $datas = CommonService::GenerateListModel($model, $option);
        $result = [];

        foreach ($datas['list'] as $row) {
            $result[] = $row;
        }

        return response()->json(ResponseService::ResponseList($result, $option, $datas['total'], $request));
    }

    public function programStudyList(Request $request) {
        $option = CommonService::GenerateDefaultOption($request);
        $model = Prodi::active()->select('id','nama_prodi','id_jurusan');

        if (!empty($request->filter['search'])) {
            $search = $request->filter['search'];

            $model->where(function($q) use ($search) {
                $q->orWhere('nama_prodi', 'like', '%' . $search . '%');
            });
        }

        if(!empty($request->filter['facultyId'])){
            $model->where('id_jurusan',$request->filter['facultyId']);
        }

        $datas = CommonService::GenerateListModel($model, $option);
        $result = [];

        foreach ($datas['list'] as $row) {
            $result[] = $row;
        }

        return response()->json(ResponseService::ResponseList($result, $option, $datas['total'], $request));
    }

    public function countryList(Request $request) {

        $option = CommonService::GenerateDefaultOption($request);
        $model = Country::select('id','name');

        if (!empty($request->filter['search'])) {
            $search = $request->filter['search'];

            $model->where(function($q) use ($search) {
                $q->orWhere('name', 'like', '%' . $search . '%');
            });
        }

        $datas = CommonService::GenerateListModel($model, $option);
        $result = [];

        foreach ($datas['list'] as $row) {
            $result[] = $row;
        }

        return response()->json(ResponseService::ResponseList($result, $option, $datas['total'], $request));
    }

    public function provinceList(Request $request) {

        $option = CommonService::GenerateDefaultOption($request);
        $model = Province::select('id','name');

        if (!empty($request->filter['search'])) {
            $search = $request->filter['search'];

            $model->where(function($q) use ($search) {
                $q->orWhere('name', 'like', '%' . $search . '%');
            });
        }

        if(!empty($request->filter['countryId'])){
            $model->where('country_id',$request->filter['countryId']);
        }

        $datas = CommonService::GenerateListModel($model, $option);
        $result = [];

        foreach ($datas['list'] as $row) {
            $result[] = $row;
        }

        return response()->json(ResponseService::ResponseList($result, $option, $datas['total'], $request));
    }

    public function cityList(Request $request) {

        $option = CommonService::GenerateDefaultOption($request);
        $model = City::select('id','name');

        if (!empty($request->filter['search'])) {
            $search = $request->filter['search'];

            $model->where(function($q) use ($search) {
                $q->orWhere('name', 'like', '%' . $search . '%');
            });
        }

        if(!empty($request->filter['provinceId'])){
            $model->where('province_id',$request->filter['provinceId']);
        }

        $datas = CommonService::GenerateListModel($model, $option);
        $result = [];

        foreach ($datas['list'] as $row) {
            $result[] = $row;
        }

        return response()->json(ResponseService::ResponseList($result, $option, $datas['total'], $request));
    }

    public function lastEducationList(Request $request) {

        $option = CommonService::GenerateDefaultOption($request);
        $datas = CommonService::GenerateListConstant(Constant::LAST_EDUCATION_LIST,$request->filter['search']);
        $result = [];

        foreach ($datas['list'] as $row) {
            $result[] = $row;
        }

        return response()->json(ResponseService::ResponseList($result, $option, $datas['total'], $request));
    }
}


