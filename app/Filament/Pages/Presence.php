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
        ])->where('user_id', Auth::id())
            ->whereDate('created_at', Carbon::today())
            ->whereHas('schedule', function ($q) use ($today) {
                $q->where('week', $today->weekOfMonth)
                    ->where('day', $today->dayOfWeekIso)
                    ->whereHas('squad', function ($q) {
                        $q->where('id', Auth::user()->squad_id);
                    });
            })
            ->whereHas('schedule.period', function ($q) use ($hour) {
                $q->whereTime('start', '<=', $hour);
                $q->whereTime('end', '>', $hour);
            })
            ->first();
        $schedule = Schedule::with('period')->where('week', $today->weekOfMonth)
            ->where('day', $today->dayOfWeekIso)
            ->where('is_accepted', true)
            ->whereHas('squad', function ($q) {
                $q->where('id', Auth::user()->squad_id);
            })
            ->whereHas('period', function ($q) use ($hour) {
                $q->whereTime('start', '<=', $hour);
                $q->whereTime('end', '>', $hour);
            })
            ->first();

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
                ->label($alreadyPresence ? 'Sudah Presensi: ' . $alreadyPresence->schedule->period->name : 'Presensi: ' . $schedule->period->name)
                ->action(function (array $data) use ($today, $hour): void {
                    $data['user_id'] = Auth::id();
                    $data['keterangan'] = null;
                    $data['schedule_id'] = Schedule::where('week', $today->weekOfMonth)
                    ->where('day', $today->dayOfWeekIso)
                    ->whereHas('squad', function ($q) {
                        $q->where('id', Auth::user()->squad_id);
                    })->whereHas('period', function ($q) use ($hour) {
                        $q->whereTime('start', '<=', $hour);
                        $q->whereTime('end', '>', $hour);
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
