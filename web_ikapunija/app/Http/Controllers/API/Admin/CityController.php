<?php

namespace App\Http\Controllers\API\Admin;

use App\City;
use App\Http\Controllers\Controller;
use App\Service\ResponseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    public function detail($id)
    {
        $data = City::find($id);

        if(empty($data)) return response()->json(ResponseService::ResponseError('Data not found!',200));

        $data->image = env('APP_ASSET').'cities/'.(!empty($data->image) ? $data->image : 'default.png');

        return response()->json(ResponseService::ResponseSuccess('success get detail data',$data));
    }

    public function update(Request $request)
    {
        $validate_rule = [
            'id'   => 'required',
            //'name'   => 'required',
        ];

        if($request->has('image')){
            $validate_rule['image'] = 'max:9600|mimes:jpeg,jpg,png,JPG,JPEG,PNG';
        }

        $validator = Validator::make($request->all(), $validate_rule);

        if($validator->fails()){
            return response()->json(ResponseService::ResponseError('Invalid Payload', $validator->errors()),200);
        }

        $data = City::find($request->id);
        $data->fill((array) $request->all());

        if($request->has('image')){
            if(!empty($data->image) && file_exists('asset_image/cities/'.$data->image) && ($data->image != 'default.png')){
                unlink('asset_image/cities/'.$data->image);
            }
            $dt = Carbon::now();
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = str_random(16).'-'.$dt->format('Y-m-d').'-city-'.md5($data->email).'.'.$extension;
            Storage::disk('cities')->put($fileName, file_get_contents($request->file('image')));
            $data->image = $fileName;
        }

        $data->save();

        $data->image = env('APP_ASSET').'cities/'.(!empty($data->image) ? $data->image : 'default.png');

        return response()->json(ResponseService::ResponseSuccess('Success edit data!',$data));
    }

}


