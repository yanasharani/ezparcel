<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->updateOrInsert(
            ['matric_number' => 'ADMIN001'],
            [
                'matric_number' => 'ADMIN001',
                'name' => 'Admin',
                'email' => 'admin@ezparcel.com',
                'phone' => '0123456789',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_admin' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}