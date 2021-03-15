<?php

namespace App\Service;

class ResponseService {
    public static function ResponseSuccess($message = '', $data = '') {
        return ['status'=>true,'message'=>$message,'data'=>$data];
    }

    public static function ResponseError($message = '', $data = '') {
        return ['status'=>false,'message'=>$message,'error'=>$data];
    }

    public static function ResponseList($data, $option, $total, $request) {
        $pageTotal = ceil($total/$option['limit']);
        return ['status'=>true, 'data'=>[
            'list' => $data,
            'dataPerPage' => $option['limit'],
            'pageCurrent' => $request->pageCurrent,
            'pageTotal' => $pageTotal <=1 ? 1 : $pageTotal,
            'countTotal' => $total,
        ]];
    }
}
