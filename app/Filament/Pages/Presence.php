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
        return [
            Action::make('presensi')
                ->hidden(function (): bool {
                    $today = Carbon::today();
                    $alreadyPresence =  ModelsPresence::where('user_id', Auth::id())
                        ->whereDate('created_at', Carbon::today())->count() > 0;

                    // $hasSchedule = Schedule::with('period')->where('week', $today->weekOfMonth)
                    //     ->where('day', $today->dayOfWeekIso)
                    //     ->where('is_accepted', true)
                    //     ->whereHas('squad', function ($q) {
                    //         $q->where('id', Auth::user()->squad_id);
                    //     })->whereHas('period', function ($q) {
                    //         // $now = Carbon::now();
                    //         // $q->whereTime('start', '<=', $now);
                    //         // $q->whereTime('end', '>', $now);
                    //     })->first();

                    $hasSchedule = Schedule::where('week', $today->weekOfMonth)
                    ->where('day', $today->dayOfWeekIso)
                    ->where('is_accepted', true)
                    ->whereHas('squad', function ($q) {
                        $q->where('id', Auth::user()->squad_id);
                    })->first();

                    $isExpired = false;

                    if ($hasSchedule) {
                        $now = Carbon::now();
                        $hour = $now->hour;
                        $period = $hasSchedule->period;
                        $start = $period->start->hour;
                        $end = $period->end->hour;

                        if ($hour >= $start && $hour <= $end) {
                            $isExpired = false;
                        }
                        else if ($hour >= $start && $hour > $end) {
                            $isExpired = true;
                        }
                    }

                    return $alreadyPresence || $isExpired === false;
                })
                ->label('Presensi')
                ->action(function (array $data): void {
                    $today = Carbon::today();
                    $data['user_id'] = Auth::id();
                    $data['keterangan'] = null;
                    $data['schedule_id'] = Schedule::where('week', $today->weekOfMonth)
                    ->where('day', $today->dayOfWeekIso)
                    ->whereHas('squad', function ($q) {
                        $q->where('id', Auth::user()->squad_id);
                    })->first()->id;

                    ModelsPresence::create($data);

                    redirect('/presence');
                })
                // ->form([
                //     Forms\Components\Textarea::make('keterangan')
                //     ->label('Keterangan')
                // ]),
        ];
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->role === 'anggota';
    }
}
