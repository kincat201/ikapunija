<?php

namespace App\Service;

use App\Company;

class CompanyService {
    public static function CheckCompanyExist($name='') {
        $code = CommonService::CleanString($name,true);
        $company = Company::where('code',$code)->first();
        if(empty($company->id)){
            $company = new Company();
            $company->name = $name;
            $company->code = $code;
            $company->save();
        }

        return !empty($company->code) ? $company->code : false;
    }
}
