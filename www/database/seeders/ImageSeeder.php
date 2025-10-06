<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('images')->insert([
            [
                'owner_id' => 1,
                'filename' => 'sample1.jpg',
            ],
            [
                'owner_id' => 1,
                'filename' => 'sample2.jpg',
            ],
            [
                'owner_id' => 1,
                'filename' => 'sample3.jpg',
            ],
            [
                'owner_id' => 1,
                'filename' => 'sample4.jpg',
            ],
            [
                'owner_id' => 1,
                'filename' => 'sample5.jpg',
            ],
            [
                'owner_id' => 1,
                'filename' => 'sample6.jpg',
            ],
        ]);
    }
}
