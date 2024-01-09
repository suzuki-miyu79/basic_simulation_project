<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // ユーザーテーブルにダミーデータを挿入
        for ($i = 0; $i < 100; $i++) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // ステータステーブルにダミーデータを挿入
            $userId = DB::getPdo()->lastInsertId();
            DB::table('statuses')->insert([
                'user_id' => $userId,
                'work_started' => false,
                'work_ended' => false,
                'break_started' => false,
                'break_ended' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
