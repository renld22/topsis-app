<?php

namespace App\Filament\Resources\Alternatives;

use BackedEnum;
use App\Models\Alternative;

use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;

use Filament\Forms\Components\TextInput;

use Filament\Tables\Columns\TextColumn;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

use Filament\Tables\Enums\RecordActionsPosition;

use App\Filament\Resources\Alternatives\Pages\ManageAlternatives;

class AlternativeResource extends Resource
{
    protected static ?string $model = Alternative::class;

    protected static ?string $navigationLabel = 'Data Kandidat';

    protected static ?string $modelLabel = 'Kandidat';

    protected static ?string $pluralModelLabel = 'Data Kandidat';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Kandidat')
                    ->required()
                    ->maxLength(255),

                TextInput::make('address')
                    ->label('Alamat')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Email Dosen')
                    ->email()
                    ->placeholder('contoh: dosen@umbanten.ac.id')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
{
    return $table
        ->recordTitleAttribute('name')

        ->columns([
            TextColumn::make('no')
                ->label('No')
                ->rowIndex(),

            TextColumn::make('name')
                ->label('Nama')
                ->searchable(),

            TextColumn::make('address')
                ->label('Alamat'),

            TextColumn::make('email')
                ->label('Email')
                ->icon('heroicon-m-envelope')
                ->searchable(),
        ])

        ->filters([])

        ->recordActions([
            EditAction::make()
                ->form([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('address')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('email')
                        ->email()
                        ->required(),
                ]),

            DeleteAction::make(),
        ])

        ->recordActionsPosition(
            RecordActionsPosition::AfterColumns
        );
}
    public static function getPages(): array
    {
        return [
            'index' => ManageAlternatives::route('/'),
        ];
    }
}