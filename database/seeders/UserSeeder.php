<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;
use Hash;
use Str;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $now = Carbon::now();

        DB::table('users')->insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'profile_photo' => null,
                'email_verified_at' => $now,
                'password' => Hash::make('admin123'), // Change password as needed
                'is_admin' => 1,
                'remember_token' => Str::random(10),
                'created_at' => $now,
                'updated_at' => $now,
                'role' => 'admin',
                'is_approved' => 1,
            ],
            [
                'name' => 'Regular User',
                'email' => 'user@example.com',
                'profile_photo' => null,
                'email_verified_at' => $now,
                'password' => Hash::make('user123'), // Change password as needed
                'is_admin' => 0,
                'remember_token' => Str::random(10),
                'created_at' => $now,
                'updated_at' => $now,
                'role' => 'students',
                'is_approved' => 1,
            ],
        ]);
    }
}