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
        $userId = DB::table('users')->insertGetId([
            'name' => Str::random(10),
            'email' => Str::random(10) . '@example.com',
            'dateOfBirth' =>  Carbon::create('2000', '01', '01'),
            'created_at' => Carbon::now(),
            'name' => rand(0,1)
        ]);

        $userId = DB::table('users_phonenumbers')->insertGetId([
            'user_id' => $userId,
            'phone_number' => '+3630' . rand(1000000, 9999999),
            'created_at' => Carbon::now(),
        ]);        
    }
}
