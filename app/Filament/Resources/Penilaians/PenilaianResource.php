<?php

namespace App\Filament\Resources\Penilaians;

use App\Filament\Resources\Penilaians\Pages\ManagePenilaians;
use App\Models\Alternative;
use App\Models\Criterion;
use App\Models\Score;
use App\Models\SubCriterion;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PenilaianResource extends Resource
{
    protected static ?string $model = Score::class;

    protected static ?string $navigationLabel = 'Penilaian';

    protected static ?string $modelLabel = 'Penilaian';

    protected static ?string $pluralModelLabel = 'Data Penilaian';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function shouldRegisterNavigation(): bool
    {
        return filament()->getCurrentPanel()->getId() === 'mahasiswa';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('alternative_id')
                ->label('Pilih Dosen')
                ->options(Alternative::pluck('name', 'id'))
                ->searchable()
                ->preload()
                ->native(false)
                ->required(),

            Select::make('criterion_id')
                ->label('Kriteria')
                ->options(Criterion::pluck('name', 'id'))
                ->searchable()
                ->preload()
                ->native(false)
                ->required()
                ->helperText('Pilih kriteria terlebih dahulu sebelum memilih subkriteria.'),

            Select::make('sub_criterion_id')
                ->label('Subkriteria (Penilaian)')
                ->native(false)
                ->options(fn ($get) => SubCriterion::where('criterion_id', $get('criterion_id'))
                    ->orderByDesc('value')
                    ->get()
                    ->mapWithKeys(fn (SubCriterion $sub) => [
                        $sub->id => "Nilai {$sub->value} — {$sub->description}",
                    ]))
                ->disabled(fn ($get) => empty($get('criterion_id')))
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('alternative.name')
                    ->label('Nama Dosen')
                    ->searchable(),
                TextColumn::make('criterion.name')
                    ->label('Kriteria'),
                TextColumn::make('subCriterion.description')
                    ->label('Subkriteria')
                    ->limit(60)
                    ->wrap(),
                TextColumn::make('value')
                    ->label('Nilai'),
                TextColumn::make('created_at')
                    ->label('Waktu Input')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePenilaians::route('/'),
        ];
    }
}
