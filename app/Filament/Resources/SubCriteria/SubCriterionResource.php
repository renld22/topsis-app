<?php

namespace App\Filament\Resources\SubCriteria;

use App\Filament\Resources\SubCriteria\Pages\ManageSubCriteria;
use App\Models\SubCriterion;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class SubCriterionResource extends Resource
{
    protected static ?string $model = SubCriterion::class;

    protected static ?string $navigationLabel = 'Sub Kriteria';

    protected static ?string $modelLabel = 'Sub Kriteria';

    protected static ?string $pluralModelLabel = 'Data Sub Kriteria';

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    protected static ?string $recordTitleAttribute = 'description';

    public static function shouldRegisterNavigation(): bool
    {
        return filament()->getCurrentPanel()->getId() === 'admin';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('criterion_id')
                    ->label('Kriteria')
                    ->relationship('criterion', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('value')
                    ->label('Nilai Subkriteria')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(5)
                    ->required()
                    ->helperText('Nilai 1 sampai 5 (5 = terbaik).'),

                Textarea::make('description')
                    ->label('Keterangan')
                    ->rows(3)
                    ->required()
                    ->columnSpanFull()
                    ->helperText('Keterangan level penilaian yang muncul saat mahasiswa menilai.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup('criterion.name')
            ->groups([
                Group::make('criterion.name')->label('Kriteria'),
            ])
            ->defaultSort('value', 'desc')
            ->columns([
                TextColumn::make('criterion.name')
                    ->label('Kriteria')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('value')
                    ->label('Nilai')
                    ->badge()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Keterangan')
                    ->wrap(),
            ])

            ->recordActions([
                EditAction::make(),
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
