<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        // $this->call(InterestSeeder::class);
        // $this->call(AlumniPostSeeder::class);
        // $this->call(CompanySeeder::class);
        $this->call(CitySeeder::class);
    }
}
