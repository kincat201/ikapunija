<?php

namespace App\Http\Controllers\API\Mobile;

use App\AlumniPost;
use App\Http\Controllers\Controller;
use App\Notification;
use App\Service\AlumniPostService;
use App\Service\CommonService;
use App\Service\ResponseService;
use App\Util\Constant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function list(Request $request)
    {
        $option = CommonService::GenerateDefaultOption($request);
        $model = Notification::select('id','type','referenceId','subject','description','status','created_at','updated_at')
                ->where('receiverId',\Auth::user()->id);

        if (!empty($request->filter['search'])) {
            $search = $request->filter['search'];
        }

        if (!empty($request->filter['status'])) {
            $model->where('status',$request->filter['status']);
        }

        $datas = CommonService::GenerateListModel($model, $option);
        $result = [];

        foreach ($datas['list'] as $row) {
            $result[] = $row;;
        }

        return response()->json(ResponseService::ResponseList($result, $option, $datas['total'], $request));
    }

    public function setRead(Request $request)
    {
        $validate_rule = [
            'notification_list'    => 'required|array|min:1',
        ];

        $validator = Validator::make($request->all(), $validate_rule);

        if($validator->fails()){
            return response()->json(ResponseService::ResponseError('Invalid Payload', $validator->errors()),200);
        }

        Notification::whereIn('id',$request->notification_list)->update(['status'=>Constant::NOTIFICATION_STATUS_READ]);

        return response()->json(ResponseService::ResponseSuccess('Berhasil read notif!'));

    }
}


