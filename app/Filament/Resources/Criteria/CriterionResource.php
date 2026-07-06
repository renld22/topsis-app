<?php

namespace App\Filament\Resources\Criteria;

use App\Filament\Resources\Criteria\Pages\ManageCriteria;
use App\Models\Criterion;
use App\Services\WeightService;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Table;

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
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'benefit' ? 'success' : 'danger'),

                TextColumn::make('sub_criteria_count')
                    ->label('Jumlah Subkriteria')
                    ->counts('subCriteria'),

                TextColumn::make('weight')
                    ->label('Bobot (otomatis)')
                    ->state(fn (Criterion $record): float => app(WeightService::class)->weightFor($record->id))
                    ->formatStateUsing(fn ($state): string => number_format((float) $state, 3))
                    ->tooltip('Bobot dihitung otomatis dari penilaian mahasiswa (total selalu = 1).'),
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
