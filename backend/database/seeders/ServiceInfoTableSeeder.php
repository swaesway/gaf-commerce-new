<?php

namespace Database\Seeders;

use Pest\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServiceInfoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        //
        for ($i=0; $i < 10 ; $i++) { 
            # code...
            $faker = Faker::create();
            DB::table('serviceinfos')->insert([
                'servicenumber' => Str::random(6), // Generate a random 6-character alphanumeric string
                'telephone' => $this->generateRandomPhoneNumber(),
                'name' => $faker->name
            ]);
        }
    }

     private function generateRandomPhoneNumber()
    {
        return '0' . rand(100000000, 999999999); // Ensures the number starts with 0
    }
}
