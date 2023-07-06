<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schedule::insert([
            [
                'regu_id' => 1,
                'periode_id' => 2,
                'minggu' => 1,
                'hari' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'diterima' => true,
            ],
            [
                'regu_id' => 2,
                'periode_id' => 1,
                'minggu' => 1,
                'hari' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'diterima' => true,
            ],
            [
                'regu_id' => 3,
                'periode_id' => 2,
                'minggu' => 1,
                'hari' => 2,
                'created_at' => now(),
                'updated_at' => now(),
                'diterima' => true,
            ],

            [
                'regu_id' => 4,
                'periode_id' => 2,
                'minggu' => 2,
                'hari' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'diterima' => true,
            ],
            [
                'regu_id' => 5,
                'periode_id' => 1,
                'minggu' => 2,
                'hari' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'diterima' => true,
            ],
            [
                'regu_id' => 6,
                'periode_id' => 2,
                'minggu' => 2,
                'hari' => 2,
                'created_at' => now(),
                'updated_at' => now(),
                'diterima' => true,
            ],

            [
                'regu_id' => 7,
                'periode_id' => 2,
                'minggu' => 3,
                'hari' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'diterima' => true,
            ],
            [
                'regu_id' => 8,
                'periode_id' => 1,
                'minggu' => 3,
                'hari' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'diterima' => true,
            ],
            [
                'regu_id' => 9,
                'periode_id' => 2,
                'minggu' => 3,
                'hari' => 2,
                'created_at' => now(),
                'updated_at' => now(),
                'diterima' => true,
            ],
        ]);
    }
}
