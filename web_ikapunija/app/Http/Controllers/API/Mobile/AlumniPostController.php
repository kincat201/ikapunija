<?php

namespace App\Http\Controllers\API\Mobile;

use App\AlumniPost;
use App\Http\Controllers\Controller;
use App\Service\AlumniPostService;
use App\Service\CommonService;
use App\Service\ResponseService;
use App\Util\Constant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AlumniPostController extends Controller
{
    public function list(Request $request)
    {
        $option = CommonService::GenerateDefaultOption($request);
        $model = AlumniPost::select(
            'alumni_posts.id','alumni_posts.types','alumni_posts.content','alumni_posts.media','alumni_posts.likes',
            'alumni_posts.comments','alumni_posts.created_at','alumni_posts.updated_at','user_alumni.nama_alumni',
            'user_alumni.angkatan','user_alumni.foto_profil','jurusan.nama_jurusan','alumni_posts.created_at','alumni_posts.updated_at'
            )
            ->join('user_alumni','user_alumni.id','=','alumni_posts.alumni_id')
            ->join('jurusan','jurusan.id','=','user_alumni.jurusan_id');

        if (!empty($request->filter['search'])) {
            $search = $request->filter['search'];

            $model->where(function($q) use ($search) {
                $q->orWhere('alumni_posts.content', 'like', '%' . $search . '%');
                $q->orWhere('user_alumni.nama_alumni', 'like', '%' . $search . '%');
            });
        }

        if (!empty($request->filter['alumni_id'])){
            $model->where('alumni_posts.alumni_id');
        }

        $datas = CommonService::GenerateListModel($model, $option);
        $result = [];

        foreach ($datas['list'] as $row) {
            $result[] = AlumniPostService::MappingRowPost($row);;
        }

        return response()->json(ResponseService::ResponseList($result, $option, $datas['total'], $request));
    }

    public function detail($id)
    {
        $data = AlumniPost::select(
            'alumni_posts.id','alumni_posts.types','alumni_posts.content','alumni_posts.media','alumni_posts.likes',
            'alumni_posts.comments','alumni_posts.created_at','alumni_posts.updated_at','user_alumni.nama_alumni',
            'user_alumni.angkatan','user_alumni.foto_profil','jurusan.nama_jurusan','alumni_posts.created_at','alumni_posts.updated_at'
            )
            ->with(['comment_list.alumni_comment.jurusan'])
            ->join('user_alumni','user_alumni.id','=','alumni_posts.alumni_id')
            ->join('jurusan','jurusan.id','=','user_alumni.jurusan_id')->find($id);
        if(empty($data)) return response()->json(ResponseService::ResponseError('Post not found!',200));

        $data = AlumniPostService::MappingRowPost($data);
        $data->reactions = $data->reactionResult();

        $comments = [];

        foreach ($data->comment_list as $comment){
            $comments[]=[
                'name'=> !empty($comment->alumni_comment->nama_alumni) ? $comment->alumni_comment->nama_alumni : '-',
                'angkatan'=> !empty($comment->alumni_comment->angkatan) ? $comment->alumni_comment->angkatan : '-',
                'jurusan'=> !empty($comment->alumni_comment->jurusan->nama_jurusan) ? $comment->alumni_comment->jurusan->nama_jurusan : '-',
                'content'=> $comment->content,
                'created_at'=> $comment->created_at,
                'updated_at'=> $comment->updated_at,
            ];
        }

        unset($data->comment_list);
        $data->comment_list = $comments;

        return response()->json(ResponseService::ResponseSuccess('success get detail post',$data),200);
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'types'=> 'required',
            'content'=> 'required',
        ]);

        if($request->has('media')){
            $validate_rule['media'] = 'max:9600|mimes:jpeg,jpg,png,JPG,JPEG,PNG';
        }

        if ($validator->fails()) {
            return response()->json(ResponseService::ResponseError('Invalid Payload', $validator->errors()),200);
        }

        $data = AlumniPostService::SaveAlumiPost($request);

        return response()->json(ResponseService::ResponseSuccess('success save post',$data));
    }

    public function delete(Request $request){

        $validator = Validator::make($request->all(), [
            'id'=> 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(ResponseService::ResponseError('Invalid Payload', $validator->errors()),200);
        }

        $data = AlumniPost::find($request->id);
        if(empty($data)) return response()->json(ResponseService::ResponseError('Post not found!',200));
        $data->delete();
        $data = AlumniPostService::MappingRowPost($data);

        return response()->json(ResponseService::ResponseSuccess('success delete post',$data),200);
    }

    public function like(Request $request){

        $validator = Validator::make($request->all(), [
            'id'=> 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(ResponseService::ResponseError('Invalid Payload', $validator->errors()),200);
        }

        $data = AlumniPost::find($request->id);
        if(empty($data)) return response()->json(ResponseService::ResponseError('Post not found!',200));

        $data = AlumniPostService::SetLikePost($data);

        return response()->json(ResponseService::ResponseSuccess('success set like post',$data),200);
    }

    public function comment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id'=> 'required',
            'content'=> 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(ResponseService::ResponseError('Invalid Payload', $validator->errors()),200);
        }

        $data = AlumniPost::find($request->id);
        if(empty($data)) return response()->json(ResponseService::ResponseError('Post not found!',200));

        $data = AlumniPostService::SetCommentPost($data,$request);

        return response()->json(ResponseService::ResponseSuccess('success save post',$data));
    }

    public function reaction(Request $request){

        $validator = Validator::make($request->all(), [
            'id'=> 'required',
            'content'=> 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(ResponseService::ResponseError('Invalid Payload', $validator->errors()),200);
        }

        $data = AlumniPost::find($request->id);
        if(empty($data)) return response()->json(ResponseService::ResponseError('Post not found!',200));

        $data = AlumniPostService::SetReactionPost($data,$request);

        return response()->json(ResponseService::ResponseSuccess('success set like post',$data),200);
    }
}


