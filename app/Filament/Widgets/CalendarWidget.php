<?php

namespace App\Filament\Widgets;

use App\Models\Period;
use App\Models\Schedule;
use App\Models\Squad;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    /**
     * Return events that should be rendered statically on calendar.
     */
    public function getViewData(): array
    {
        $today = CarbonImmutable::today()->startOfMonth()->startOfWeek()->addWeek();

        $squads = Squad::with([
            'schedules',
            'schedules.period'
            ])->whereHas('schedules', function ($q) {
                $q->where('diterima', true);
            });

        if (Auth::user()->role === 'anggota') {
           $squads ->whereHas('members', function ($q) {
                $q->where('id', Auth::id());
            });
        }

        $results = [];
        foreach ($squads->get() as $squad) {
            for ($i=1; $i <= 4; $i++) {
                $total = 7;
                if ($i === 4) {
                    $lastWeekStart = $today->copy()->endOfMonth()->startOfWeek();
                    $total = $today->copy()->endOfMonth()->diffInDays($lastWeekStart) + 1;
                }
                for ($j=1; $j <= $total; $j++) {
                    foreach ($squad->schedules as $schedule) {
                        if ($schedule->hari === $j && $schedule->minggu == $i && $schedule->diterima === true) {
                            if (Auth::user()->role === 'anggota') {
                                $results[] = [
                                    'id' => uniqid('fl'),
                                    'title' => $schedule->period->nama,
                                    'start' => $today->addWeeks($i - 1)->addDays($schedule->hari - 1)->setHour($schedule->period->mulai->format('H')),
                                    'end' => $today->addWeeks($i - 1)->addDays($schedule->hari - 1)->setHour($schedule->period->selesai->format('H')),
                                    'display' => 'block',
                                    'editable' => false,
                                    'resourceEditable' => false,
                                    'backgroundColor' => '#19647E',
                                    'borderColor' => '#19647E'
                                ];
                            } else {
                                foreach ($squad->members as $member) {
                                    $name = ucfirst(mb_strtolower(explode(' ', $member->fullname)[0]));
                                    $results[] = [
                                        'id' => uniqid('fl'),
                                        'title' =>  $name . ' : ' . $schedule->period->nama,
                                        'start' => $today->addWeeks($i - 1)->addDays($schedule->hari - 1)->setHour($schedule->period->mulai->format('H')),
                                        'end' => $today->addWeeks($i - 1)->addDays($schedule->hari - 1)->setHour($schedule->period->selesai->format('H')),
                                        'display' => 'block',
                                        'editable' => false,
                                        'resourceEditable' => false,
                                        'backgroundColor' => '#19647E',
                                        'borderColor' => '#19647E'
                                    ];
                                }
                            }
                        } else {
                            if (Auth::user()->role === 'anggota') {
                                $results[] = [
                                    'id' => uniqid('fl'),
                                    'title' => 'Lepas Dinas',
                                    'start' => $today->addWeeks($i - 1)->addDays($j - 1)->setHour($schedule->period->mulai->format('H')),
                                    'end' => $today->addWeeks($i - 1)->addDays($j - 1)->setHour($schedule->period->selesai->format('H')),
                                    'display' => 'block',
                                    'editable' => false,
                                    'resourceEditable' => false,
                                    'backgroundColor' => '#e54f6d',
                                    'borderColor' => '#e54f6d'
                                ];
                            }
                        }
                    }
                }
            }
        }
        return $results;

        return [];
    }

    /**
     * FullCalendar will call this function whenever it needs new event data.
     * This is triggered when the user clicks prev/next or switches views on the calendar.
     */
    public function fetchEvents(array $fetchInfo): array
    {
        // You can use $fetchInfo to filter events by date.
        return [];
    }

    public static function canCreate(): bool
    {
        // Returning 'false' will remove the 'Create' button on the calendar.
        return false;
    }

    public static function canEdit(?array $event = null): bool
    {
        // Returning 'false' will disable the edit modal when clicking on a event.
        return false;
    }
}
