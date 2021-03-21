<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Interest;
use App\Jurusan;
use App\Prodi;
use App\Profesi;
use App\Service\CommonService;
use App\Service\ResponseService;
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
}


