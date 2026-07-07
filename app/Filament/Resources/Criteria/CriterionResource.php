<?php

namespace App\Filament\Resources\Criteria;

use BackedEnum;
use App\Models\Criterion;

use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;

use Filament\Tables\Columns\TextColumn;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

use Filament\Tables\Enums\RecordActionsPosition;

use App\Filament\Resources\Criteria\Pages\ManageCriteria;

class CriterionResource extends Resource
{
    protected static ?string $model = Criterion::class;

    protected static ?string $navigationLabel = 'Kriteria';

    protected static ?string $modelLabel = 'Kriteria';

    protected static ?string $pluralModelLabel = 'Data Kriteria';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedScale;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Kriteria')
                    ->required(),

                Select::make('type')
                    ->label('Tipe')
                    ->options([
                        'benefit' => 'Benefit',
                        'cost' => 'Cost',
                    ])
                    ->required(),

                TextInput::make('weight')
                    ->label('Bobot (Weight)')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->maxValue(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),

                TextColumn::make('type')
                    ->label('Type'),

                TextColumn::make('weight')
                    ->label('Weight')
                    ->formatStateUsing(fn ($state) => number_format($state, 2)),
            ])

            ->recordActions([
                EditAction::make()
                    ->form([
                        TextInput::make('name')
                            ->label('Nama Kriteria')
                            ->required(),

                        Select::make('type')
                            ->label('Tipe')
                            ->options([
                                'benefit' => 'Benefit',
                                'cost' => 'Cost',
                            ])
                            ->required(),

                        TextInput::make('weight')
                            ->label('Bobot (Weight)')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->maxValue(1),
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
            'index' => ManageCriteria::route('/'),
        ];
    }
}