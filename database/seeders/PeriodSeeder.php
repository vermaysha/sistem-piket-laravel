<?php

namespace Database\Seeders;

use App\Models\Period;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Period::insert([
            [
                'nama' => 'Malam',
                'mulai' => '18:00',
                'selesai' => '23:00',
                'updated_at' => now(),
                'created_at' => now(),
            ],
            [
                'nama' => 'Pagi',
                'mulai' => '06:00',
                'selesai' => '10:00',
                'updated_at' => now(),
                'created_at' => now(),
            ],
        ]);
    }
}
