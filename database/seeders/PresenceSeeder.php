<?php

namespace Database\Seeders;

use App\Models\Presence;
use App\Models\Schedule;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PresenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        $today = CarbonImmutable::today()->startOfMonth()->startOfWeek()->addWeek();
        foreach (Schedule::with(['squad', 'squad.members'])->get() as $schedule) {
            $squad = $schedule->squad;

            for ($i=1; $i <= 2; $i++) {
                $total = 7;
                if ($i === 4) {
                    $lastWeekStart = $today->copy()->endOfMonth()->startOfWeek();
                    $total = $today->copy()->endOfMonth()->diffInDays($lastWeekStart) + 1;
                }
                for ($j=1; $j <= $total; $j++) {
                    if ($schedule->hari === $j && $schedule->minggu == $i) {
                        foreach ($squad->members as $member) {
                            $data[] = [
                                'anggota_id' => $member->id,
                                'jadwal_id' => $schedule->id,
                                'keterangan' => 'Hadir',
                                'created_at' => $today->addWeeks($i - 1)->addDays($schedule->hari - 1)->setHour($schedule->period->mulai->format('H')),
                                'updated_at' => $today->addWeeks($i - 1)->addDays($schedule->hari - 1)->setHour($schedule->period->mulai->format('H')),
                            ];
                        }
                    }
                }
            }
        }

        Presence::insert($data);
    }
}
