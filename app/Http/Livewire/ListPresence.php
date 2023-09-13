<?php

namespace App\Http\Livewire;

use App\Models\Presence;
use App\Models\Schedule;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ListPresence extends Component implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected function getTableQuery(): Builder
    {
        if (Auth::user()->role === 'anggota') {
            return Presence::query()->where('anggota_id', Auth::id());
        } else {
            $query =  Presence::query();
            $month = request()->get('month');

            if ($month) {
                $query->whereMonth('created_at', $month);
            }

            return $query;
        }
    }

    protected function getTableEmptyStateIcon(): ?string
    {
        return 'heroicon-o-bookmark';
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'Tidak ada riwayat presensi';
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('id')->rowIndex(false)->label('No'),
            TextColumn::make('user.fullname')->label('Nama')->visible(function () {
                return Auth::user()->role === 'pimpinan';
            }),
            ImageColumn::make('bukti')
                ->label('Bukti')
                ->defaultImageUrl('https://placehold.co/600x400?text=Tidak%20Ada%20Bukti')
                ->disk('public')
                ->openUrlInNewTab(true)
                ->size(80),
            Tables\Columns\TextColumn::make('keterangan')->label('Keterangan'),
            Tables\Columns\BadgeColumn::make('created_at')->dateTime('l, d F Y \P\u\k\u\l H:i')->label('Tanggal Presensi')->color('success')->alignCenter(),
            BadgeColumn::make('schedule.minggu')->label('Jadwal Minggu')->color('primary')->enum([
                1 => 'Pertama',
                2 => 'Kedua',
                3 => 'Ketiga',
                4 => 'Keempat',
            ])->alignCenter(),
            BadgeColumn::make('schedule.hari')->label('Jadwal Hari')->color('primary')->enum([
                1 => 'Senin',
                2 => 'Selasa',
                3 => 'Rabu',
                4 => 'Kamis',
                5 => 'Jumat',
                6 => 'Sabtu',
                7 => 'Minggu',
            ])->alignCenter(),
            BadgeColumn::make('schedule.period.nama')->label('Periode Piket')->color('primary')->alignCenter()
        ];
    }

    public function render(): View
    {
        return view('livewire.list-presence');
    }
}
