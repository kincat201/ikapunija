<?php

use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = \App\City::get();
        $data_city = [];
        foreach ($cities as $city) $data_city[] = $city->id;
        $alumnies = \App\UserAlumni::get();
        foreach ($alumnies as $alumni){
            $alumni->city_id = $data_city[mt_rand(0,(count($data_city)-1))];
            $alumni->save();
        }
    }
}
