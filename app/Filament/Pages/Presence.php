<?php

namespace App\Filament\Pages;

use App\Models\Presence as ModelsPresence;
use App\Models\Schedule;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Pages\Actions\Action;
use Filament\Forms;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class Presence extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static string $view = 'filament.pages.presence';

    protected static ?string $navigationLabel = 'Presensi';

    protected ?string $heading = 'Presensi';

    protected function getActions(): array
    {
        $today = Carbon::today();
        $now = Carbon::now();
        $hour = $now->format('H:i:s');

        $alreadyPresence =  ModelsPresence::with([
            'schedule',
            'schedule.period'
        ])->where('anggota_id', Auth::id())
            ->whereDate('created_at', Carbon::today())
            ->whereHas('schedule', function ($q) use ($today) {
                $q->where('minggu', $today->weekOfMonth)
                    ->where('hari', $today->dayOfWeekIso)
                    ->whereHas('squad', function ($q) {
                        $q->where('id', Auth::user()->regu_id);
                    });
            })
            ->whereHas('schedule.period', function ($q) use ($hour) {
                $q->whereTime('mulai', '<=', $hour);
                $q->whereTime('selesai', '>', $hour);
            })
            ->first();
        $schedule = Schedule::with('period')->where('minggu', $today->weekOfMonth)
            ->where('hari', $today->dayOfWeekIso)
            ->where('diterima', true)
            ->whereHas('squad', function ($q) {
                $q->where('id', Auth::user()->regu_id);
            })
            ->whereHas('period', function ($q) use ($hour) {
                $q->whereTime('mulai', '<=', $hour);
                $q->whereTime('selesai', '>', $hour);
            })
            ->first();

        $title = 'Tidak ada presensi';
        if ($alreadyPresence) {
            $title = 'Sudah Presensi: ' . $alreadyPresence->schedule->period->nama;
        } elseif ($schedule) {
            $title = 'Presensi: ' . $schedule->period->nama;
        }

        return [
            Action::make('presensi')
                ->disabled(function () use ($alreadyPresence, $schedule): bool {
                    if ($alreadyPresence) {
                        return true;
                    }

                    if ($schedule) {
                        return false;
                    }

                    return true;
                })
                ->label($title)
                ->action(function (array $data) use ($today, $hour): void {
                    $data['anggota_id'] = Auth::id();
                    $data['keterangan'] = null;
                    $data['jadwal_id'] = Schedule::where('minggu', $today->weekOfMonth)
                    ->where('hari', $today->dayOfWeekIso)
                    ->whereHas('squad', function ($q) {
                        $q->where('id', Auth::user()->regu_id);
                    })->whereHas('period', function ($q) use ($hour) {
                        $q->whereTime('mulai', '<=', $hour);
                        $q->whereTime('selesai', '>', $hour);
                    })->first()->id;

                    ModelsPresence::create($data);

                    redirect('/presence');
                })
        ];
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->role === 'anggota';
    }
}
