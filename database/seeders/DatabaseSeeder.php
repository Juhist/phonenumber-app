<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 25; $i++) { 
            $userId = DB::table('users')->insertGetId([
                'name' => Str::random(10),
                'email' => Str::random(10) . '@example.com',
                'dateOfBirth' =>  Carbon::create(rand(1980,2012), rand(1,12), rand(1,28)),
                'created_at' => Carbon::now(),
                'isActive' => rand(0,1)
                ]);
                
                DB::table('phone_numbers')->insert([
                    'user_id' => $userId,
                    'phoneNumber' => '+3630' . rand(1000000, 9999999),
                    'isDefault' => 1,
                    'created_at' => Carbon::now(),
                    ]);
                DB::table('phone_numbers')->insert([
                    'user_id' => $userId,
                    'phoneNumber' => '+3630' . rand(1000000, 9999999),
                    'isDefault' => 0,
                    'created_at' => Carbon::now(),
                    ]);        
        }
    }
}
