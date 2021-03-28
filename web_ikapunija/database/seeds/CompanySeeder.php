<?php

use Faker\Factory;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        $datas = array();

        for ($i=1; $i <= 20; $i++) {
            $data['id'] = $i;
            $data['name'] = $faker->company;
            $data['code'] = \App\Service\CommonService::CleanString($data['name'],true);
            $dates = \Carbon\Carbon::now()->subDay(mt_rand(0,30));
            $data['created_at'] = $dates;
            $data['updated_at'] = $dates;
            array_push($datas, $data);
        }

        DB::table('companies')->insert($datas);

        $alumnies = \App\UserAlumni::get();
        foreach ($alumnies as $alumni){
            $alumni->company = $datas[mt_rand(0,19)]['code'];
            $alumni->save();
        }
    }
}
