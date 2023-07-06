<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Filament\Resources\MemberResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MemberResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'Anggota';

    protected static ?string $navigationIcon = 'heroicon-s-user-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('fullname')->label('Nama Lengkap')->required()->autofocus()->autocapitalize(),
                TextInput::make('email')->email()->label('Email')->required()->autocomplete('username'),
                TextInput::make('pangkat')->label('Pangkat')->required(),
                TextInput::make('jabatan')->label('Jabatan')->required(),
                TextInput::make('password')->password()->hiddenOn('edit')->visibleOn('create')->required()->autocomplete('new-password'),
                Select::make('squad')->relationship('squad', 'nama')->label('Regu'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('fullname')->label('Nama'),
                TextColumn::make('email')->label('Email'),
                TextColumn::make('pangkat')->label('Pangkat'),
                TextColumn::make('jabatan')->label('Jabatan'),
                TextColumn::make('squad.nama')->label('Regu'),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'anggota');
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'view' => Pages\ViewMember::route('/{record}'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
