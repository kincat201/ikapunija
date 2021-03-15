<?php

namespace App\Libraries;
use App\Libraries\CekAuth;
use Validator;

class UtilityAPI {
    public function limiter($sql, $limit, $offset)
    {
        if($limit > 0)
        { 
            if($offset < 0)
            { $sql->offset(0); }

            else
            { $sql->offset($offset); }
            $sql->limit($limit);
        }
        return $sql;
    }

    public function search($sql, $key, $column = [])
    {
        if(sizeof($column) > 0 && $key != null && $key != '')
        { 
            $sql->Where($column[0], 'like', '%'.$key.'%'); 
            
            for($i=1; $i<sizeof($column); $i++)
            { $sql->Where($column[$i], 'like', '%'.$key.'%'); }
        }

        return $sql;
    }
    
    public function totalPage($componentAPI)
    {
        $totalpage = $componentAPI['sql'];
        for($i = 0; $i < sizeof($componentAPI['addCase']); $i++)
        {
            $case = $componentAPI['addCase'][$i];

            if(sizeof($case) > 0)
            {
                if($case[0] == 'or')
                { $totalpage->orWhere($case[1], $case[2], $case[3]); }

                else if($case[0] == 'and')
                { $totalpage->where($case[1], $case[2], $case[3]); }
            }
        }
        $totalpage = $totalpage->count();
        return $totalpage;
    }
    
    public function responseAPI($code, $error, $message, $data = null, $totalPage = null)
    {
        $resp = array (
            'StatusCode' => $code,
            'Error'=> $error,
            'Message'=> $message,
        );
        if($totalPage != null)
        {  $resp['TotalPage'] = $totalPage; }

        if($data != null && $data != '')
        {  $resp['Data'] = $data; }
   
        return response()->json($resp);
    }

    public function response_list($sql, $message, $totalPage)
    {
        if(count($sql) >0)
        { 
            $response = $this->responseAPI(200, false, $message, $sql, $totalPage);
            return $response; 
        }

        else 
        { 
            $response = $this->responseAPI(404, true, 'Error, Data Not Found'); 
            return $response;
        }
    }

    public function response_detail($sql, $message)
    {
        if($sql != null)
        { 
            $response = $this->responseAPI(200, false, $message, $sql);
            return $response; 
        }

        else 
        { 
            $response = $this->responseAPI(404, true, 'Error, Data Not Found'); 
            return $response;
        }
    }

    public function response_denied($type = 'default')
    {
        $msg = 'Acess Denied';

        if($type == 'admin')
        { $msg = 'Acess Denied or Please Login'; }
        
        $response = $this->responseAPI(400, true, $msg);
            return $response;
        return $response;
    }
    
    public function APIProcess($componentAPI) {
        $totalPage = null;
        
        $checkKey = new CekAuth();
        if($componentAPI['type'] == 'web')
        { $verify = $checkKey->checkKey($componentAPI['apiToken']); }

        else
        { $verify = $checkKey->checkLogin($componentAPI); }
        if($verify != null) { 
            $sql =  $componentAPI['sql'];        
            
            for($i = 0; $i < sizeof($componentAPI['addCase']); $i++)
            {
                $case = $componentAPI['addCase'][$i];
                
                if(sizeof($case) > 0)
                {
                    if($case[0] == 'or')
                    { $sql->orWhere($case[1], $case[2], $case[3]); }

                    else if($case[0] == 'and')
                    { $sql->where($case[1], $case[2], $case[3]); }
                }
            }

            if($componentAPI['mode'] == 'get')
            {
                if($componentAPI['sort'][0] == null || $componentAPI['sort'][1] == null)
                { $componentAPI['sort'] = $componentAPI['defSort']; }

                $sql = $this->search($sql, $componentAPI['searchData'], $componentAPI['searchCol']);
                $sql = $this->limiter($sql, $componentAPI['limit'], $componentAPI['offset']); 

                $sql->orderby($componentAPI['sort'][0], $componentAPI['sort'][1]);
            }

            switch($componentAPI['mode']) {
                case 'detail':
                    $sql = $sql->first();
                    $response = $this->response_detail($sql, $componentAPI['successMsg']); 
                    break;
                
                default:
                    $sql = $sql->get();
                    $totalPage = $this->totalPage($componentAPI);
                    $response = $componentAPI['type'] == 'admin' ? $sql : $this->response_list($sql, $componentAPI['successMsg'], $totalPage); 
                    break;
            };
            
            return $response;
        }

        else {
            $response = $this->response_denied($componentAPI['type']); 
            return $response;
        }
    }

    public function UpdateData($inputVal, $input, $parameters, $addtionalFunc, $name) {
        $validator = Validator::make($input, $inputVal);
        $checkKey = new CekAuth();

        if ($validator->fails()) {
            $resps = response()->json(['Error'=>$validator->errors()]); 
            return $this->responseAPI('404', 'true', $resps);           
        }
        
        else if ($parameters['apiToken'] != env('APPS_KEY')) {
            $resp = $this->response_denied('admin');
        }
 
        else {
            $resp = 'web';
            if(($parameters['type'] == 'admin' || $parameters['type'] == 'user') && !isset($parameters['mode'])) {
                $verify = $checkKey->checkLogin($parameters);
                if($verify != null)
                { $resp = 'success_login'; }
            }

            else if(isset($parameters['mode']))
            { $resp = 'login'; }

            else if(isset($parameters['additional']['mode_exec']))
            {
                $exec = $parameters['additional']['mode_exec'];
                if($exec == 'Add Profil' || $exec == 'Kontak')
                { $resp = 'approve'; }
            }

            if($resp != 'web')
            {
                $respCode = $addtionalFunc->$name($input, $parameters); 
                $resps = $this->responseAPI($respCode['code'], $respCode['error'], $respCode['msg'], $respCode['data']);
            }

            else
            { $resps = $this->response_denied($parameters['type']); }
            
        }
        
        return $resps;
    }     
}