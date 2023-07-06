<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleResource\Pages;
use App\Filament\Resources\ScheduleResource\RelationManagers;
use App\Models\Schedule;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $modelLabel = 'Jadwal Anggota';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('regu_id')->label('Regu')->relationship('squad', 'nama')->required(),
                Select::make('periode_id')->label('Periode')->relationship('period', 'nama')->required(),
                Select::make('minggu')->label('Minggu ke')->options([
                    1 => 'Minggu ke-1',
                    2 => 'Minggu ke-2',
                    3 => 'Minggu ke-3',
                    4 => 'Minggu ke-4',
                ])->required(),
                Select::make('hari')->label('Hari')->options([
                    1 => 'Senin',
                    2 => 'Selasa',
                    3 => 'Rabu',
                    4 => 'Kamis',
                    5 => 'Jumat',
                    6 => 'Sabtu',
                    7 => 'Minggu',
                ])->required(),
                Toggle::make('diterima')
                    ->label('Status')
                    ->inline()
                    ->hidden(function () {
                        return auth()->user()->role === 'admin';
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('squad.nama')->label('Nama Regu'),
                TextColumn::make('period.nama')->label('Periode')->alignCenter(),
                BadgeColumn::make('minggu')->label('Minggu')->color('primary')->enum([
                    1 => 'Pertama',
                    2 => 'Kedua',
                    3 => 'Ketiga',
                    4 => 'Keempat',
                ])->alignCenter(),
                BadgeColumn::make('hari')->label('Hari')->color('primary')->enum([
                    1 => 'Senin',
                    2 => 'Selasa',
                    3 => 'Rabu',
                    4 => 'Kamis',
                    5 => 'Jumat',
                    6 => 'Sabtu',
                    7 => 'Minggu',
                ])->alignCenter(),
                BadgeColumn::make('diterima')->color(function ($state) {
                    if ($state) {
                        return 'success';
                    }
                    return 'danger';
                })->enum([
                    0 => 'Non Aktif',
                    1 => 'Aktif'
                ])->label('Status')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'view' => Pages\ViewSchedule::route('/{record}'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()->role === 'admin') {
            return parent::getEloquentQuery()->where('diterima', true);
        }

        return parent::getEloquentQuery()->where('diterima', false);
    }

    public static function getModelLabel(): string
    {
        if (Auth::user()->role === 'pimpinan') {
            return 'Approval Jadwal Anggota';
        }

        return static::$modelLabel;
    }
}
