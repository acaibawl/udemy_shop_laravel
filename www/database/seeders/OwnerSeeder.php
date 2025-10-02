<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owners = [];

        for ($i = 1; $i <= 100; $i++) {
            $owners[] = [
                'name' => 'test' . $i,
                'email' => 'test' . $i . '@test.com',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('owners')->insert($owners);
    }
}
