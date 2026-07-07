<?php

namespace App\Filament\Resources\Criteria;

use BackedEnum;
use App\Models\SubCriterion;
use App\Models\Criterion;

use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

use Filament\Tables\Columns\TextColumn;

use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

use Filament\Tables\Enums\RecordActionsPosition;

use App\Filament\Resources\Criteria\Pages\ManageSubCriteria;

class SubCriterionResource extends Resource
{
    protected static ?string $model = SubCriterion::class;

    protected static ?string $navigationLabel = 'Sub Kriteria';

    protected static ?string $modelLabel = 'Sub Kriteria';

    protected static ?string $pluralModelLabel = 'Data Sub Kriteria';

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('criterion_id')
                    ->label('Kriteria Induk')
                    ->options(Criterion::pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('name')
                    ->label('Nama Sub Kriteria')
                    ->required(),

                TextInput::make('value')
                    ->label('Nilai')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(5)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),

                TextColumn::make('criterion.name')
                    ->label('Kriteria Induk')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Nama Sub Kriteria')
                    ->wrap()
                    ->searchable(),

                TextColumn::make('value')
                    ->label('Nilai')
                    ->sortable(),

                TextColumn::make('weight')
                    ->label('Weight')
                    ->formatStateUsing(fn ($state, $record) => number_format($record->value, 0)),
            ])
            ->recordActions([
                EditAction::make()
                    ->form([
                        Select::make('criterion_id')
                            ->label('Kriteria Induk')
                            ->options(Criterion::pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('name')
                            ->label('Nama Sub Kriteria')
                            ->required(),

                        TextInput::make('value')
                            ->label('Nilai')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5)
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
            'index' => ManageSubCriteria::route('/'),
        ];
    }
}
