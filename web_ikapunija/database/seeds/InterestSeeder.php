<?php

use Faker\Factory;
use Illuminate\Database\Seeder;

class InterestSeeder extends Seeder
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

        foreach (\App\Util\Constant::COMMON_YESNO_LIST as $key => $val){ $status[] = $key; }

        for ($i=1; $i <= 20; $i++) {
            $data['id'] = $i;
            $data['name'] = $faker->text(8);
            $data['description'] = $faker->paragraph(2);
            $data['status'] = $status[mt_rand(0,(count($status)-1))];
            array_push($datas, $data);
        }

        DB::table('interests')->insert($datas);
    }
}
