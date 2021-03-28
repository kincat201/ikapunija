<?php

namespace App\Service;

use App\City;
use App\Util\Constant;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class CommonService {

    public static function GenerateDefaultLimit($request) {
        $data = [];
        $data['limit'] = !empty($request->dataPerPage) ? $request->dataPerPage : 10;
        $data['offset'] = !empty($request->pageCurrent) ? ($data['limit'] * ($request->pageCurrent - 1)) : 0;
        return $data;
    }

    public static function GenerateDefaultOrder($request) {
        $data = [];
        $data['name'] = !empty($request->orderBy) && count($request->orderBy) > 0 ? $request->orderBy[0] : 'id';
        $data['sort'] = !empty($request->orderBy) && count($request->orderBy) > 0 ? $request->orderBy[1] : 'desc';
        return $data;
    }

    public static function GenerateDefaultOption($request) {
        $data = [];
        $generateLimit = self::GenerateDefaultLimit($request);
        $generateOrder = self::GenerateDefaultOrder($request);

        $data['limit'] = $generateLimit['limit'];
        $data['offset'] = $generateLimit['offset'];
        $data['order'] = $generateOrder;
        return $data;
    }

    public static function GenerateListModel($model, $option, $custom = false) {
        $data = [];

        $data['total'] = $custom ? count($model->get()) : $model->count();
        $datas = $model->orderBy($option['order']['name'], $option['order']['sort']);

        if($option['limit'] !== -1){
            $datas->limit($option['limit'])->offset($option['offset']);
        }

        $data['list'] = $datas->get();
        return $data;
    }

    public static function GenerateListConstant($datas, $search='') {
        $data = [];

        $data['total'] = count($datas);
        $data['list'] = [];

        foreach ($datas as $key => $val){
            if(!empty($search)){
                if (strpos(strtoupper($val), strtoupper($search)) !== false){
                    $data['list'][] = [
                        "id"=>$key,
                        "name"=>$val
                    ];
                }
            }else{
                $data['list'][] = [
                    "id"=>$key,
                    "name"=>$val
                ];
            }
        }

        return $data;
    }

    public static function ConverTanggalToDDMMYYYY($data = "") {
        $result = "";
        if (!empty($data)) {
            $result = substr($data, -2) . "-" . substr($data, 5, 2) . "-" . substr($data, 0, 4);
        }
        return $result;
    }

    public static function SetCityProvinceRajaOngkir(){
        $datas = json_decode(file_get_contents(url('province.json')));

        $provinces = [];
        foreach ($datas->rajaongkir->results as $result){
            $provinces[] = [
                'id'=>$result->province_id,
                'name'=>$result->province,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now(),
            ];
        }

        foreach ($provinces as $province){
            $responseData = (object) ['status' => false, 'message' => 'SYSTEM ERROR'];

            $cities = [];

            $url = 'https://pro.rajaongkir.com/api/city?province='.$province['id'];
            try {
                $client = new Client(['verify' => false]);
                //$client = new Client();

                $headers = ['key'=>'f1e42690c673cbf7572f4210038b8cbc'];
                $options = ['headers' => $headers];

                $response = $client->get($url, $options);
                $rawResponse = $response->getBody()->__toString();
                $responseData = json_decode($rawResponse);

                foreach ($responseData->rajaongkir->results as $val){
                    $cities[] = [
                        'province_id'=>$val->province_id,
                        'name'=>$val->type. ' '.$val->city_name,
                        'created_at'=>Carbon::now(),
                        'updated_at'=>Carbon::now(),
                    ];
                }

                City::insert($cities);

            } catch (RequestException $e) {
                $rawResponse = 'RequestException : ' . $e->getResponse()->getBody()->__toString();
                $responseData->message = $rawResponse;
            }
        }

        return response()->json($datas);
    }

    public static function CleanString($data = "",$upper = false) {
        $data = str_replace(' ', '', $data); // Replaces all spaces.
        $data = preg_replace('/[^A-Za-z0-9]/', '', $data); // Removes special chars.
        if($upper) $data = strtoupper($data);
        return $data;
    }

}
