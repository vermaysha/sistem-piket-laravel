<?php

namespace Database\Seeders;

use App\Models\Squad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SquadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
        ];
        for ($i=1; $i <= 9; $i++) {
            $data[] = [
                'nama' => 'Regu ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        Squad::insert($data);
    }
}
