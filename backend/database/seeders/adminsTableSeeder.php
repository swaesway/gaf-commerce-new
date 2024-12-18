<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class adminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 
        DB::table('admins')->insert([
            'email' => 'admin@gaf.com',
            'password' => Hash::make('workhard')
        ]);
    }
}
