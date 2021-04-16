<?php

namespace App\Http\Controllers\API\Mobile;

use App\Http\Controllers\Controller;
use App\Service\CommonService;
use App\Service\ResponseService;
use App\Util\Constant;
use Illuminate\Http\Request;
use App\UserAlumni;

class SearchAlumniController extends Controller
{
    public function list(Request $request)
    {
        $option = CommonService::GenerateDefaultOption($request);
        $model = UserAlumni::query();
        $model->where('user_alumni.is_active',Constant::ACTIVE_STATUS_YES);
        if($request->filter['types'] == Constant::SEARCH_ALUMNI_TYPES_LOCATION){
            $model->select('cities.id as code','cities.name',\DB::raw("CONCAT('cities/',cities.image) as image"),\DB::raw('COUNT(user_alumni.id) as total_alumni'));
            $model->join('cities','user_alumni.city_id','=','cities.id');
            $model->groupBy('user_alumni.city_id');
        }else{
            $model->select('companies.code','companies.name',\DB::raw("CONCAT('companies/',companies.image) as image"),\DB::raw('COUNT(user_alumni.id) as total_alumni'));
            $model->join('companies','user_alumni.company','=','companies.code');
            $model->groupBy('user_alumni.company');
        }

        if(empty($request->filter['countryId'])){
            $model->where('negara_id','!=','ID');
        }else{
            $model->where('negara_id',$request->filter['countryId']);
        }

        $datas = CommonService::GenerateListModel($model, $option,true);
        $result = [];

        foreach ($datas['list'] as $row) {
            $row->image = env('APP_ASSET').$row->image;
            $result[] = $row;
        }

        return response()->json(ResponseService::ResponseList($result, $option, $datas['total'], $request));
    }

    public function listDetail(Request $request)
    {
        $option = CommonService::GenerateDefaultOption($request);
        $model = UserAlumni::select(
            'user_alumni.id','user_alumni.nama_alumni','user_alumni.angkatan','user_alumni.foto_profil','jurusan.nama_jurusan',
            'user_alumni.nama_profesi','profesi.nama_profesi as jenis_profesi','user_alumni.nama_profesi','user_alumni.city_id','user_alumni.company','companies.name as companyName'
        )
            ->where('user_alumni.is_active',Constant::ACTIVE_STATUS_YES)
            ->join('jurusan','jurusan.id','=','user_alumni.jurusan_id')
            ->leftJoin('companies','companies.code','=','user_alumni.company')
            ->join('profesi','profesi.id','=','user_alumni.profesi_id');


        if(!empty($request->filter['code'])){
            $code = $request->filter['code'];
            if($request->filter['types'] == Constant::SEARCH_ALUMNI_TYPES_LOCATION){
                $model->where('user_alumni.city_id',$code);
            }else{
                $model->where('user_alumni.company',$code);
            }
        }

        $datas = CommonService::GenerateListModel($model, $option);
        $result = [];

        foreach ($datas['list'] as $row) {
            $row->foto_profil = env('APP_ASSET').'user_alumni/profil/'.(!empty($row->foto_profil) ? $row->foto_profil : 'default.png');
            $result[] = $row;
        }

        return response()->json(ResponseService::ResponseList($result, $option, $datas['total'], $request));
    }
}


