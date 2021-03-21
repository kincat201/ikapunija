<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Interest;
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
}


