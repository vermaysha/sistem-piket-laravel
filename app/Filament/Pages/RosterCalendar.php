<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\CalendarWidget;
use App\Models\Schedule;
use App\Models\Squad;
use Carbon\CarbonImmutable;
use Filament\Pages\Page;
use Termwind\Components\Dd;

class RosterCalendar extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.roster-calendar';

    protected static ?string $title = 'Kalender Piket';

    protected static ?int $navigationSort = 5;


    protected function getHeaderWidgets(): array
    {
        return [
            CalendarWidget::class
        ];
    }

    protected function getViewData(): array
    {
        $today = CarbonImmutable::today();
        $squads = Squad::with([
            'schedules',
            'schedules.period'
            ])->whereHas('schedules', function ($q) use ($today) {
                $q->where('minggu', $today->weekOfMonth);
            })->get();

        $result = [];

        // Menambahkan array kosong apabila
        foreach ($squads as $i => $squad) {
            $result[$i] = $squad->toArray();
            $newSchedules = [];
            for ($j = 1; $j <= 7; $j++) {
                $add = true;
                foreach ($result[$i]['schedules'] as $schedule) {
                    if ($schedule['hari'] === $j) {
                        $newSchedules[] = $schedule;
                        $add = false;
                    }
                }

                if ($add) {
                    $newSchedules[] = [];
                }
            }
            $result[$i]['schedules'] = $newSchedules;
        }

        return [
            'squads' => $result,
            'schedules' => $result,
            'tohari' => $today,
            'days' => [
                'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'
            ]
        ];
    }
}
