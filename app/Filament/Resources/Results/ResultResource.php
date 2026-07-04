<?php

namespace App\Filament\Resources\Results;

use BackedEnum;
use App\Models\Result;
use Barryvdh\DomPDF\Facade\Pdf;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;

use Filament\Forms\Components\TextInput;

use Filament\Tables\Columns\TextColumn;

use App\Filament\Resources\Results\Pages\ManageResults;

class ResultResource extends Resource
{
    protected static ?string $model = Result::class;

    protected static ?string $navigationLabel = 'Hasil';

    protected static ?string $modelLabel = 'Hasil';

    protected static ?string $pluralModelLabel = 'Data Hasil';

    protected static ?int $navigationSort = 5;

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedChartBarSquare;

    protected static ?string $recordTitleAttribute = 'result';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('result')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('result')

            ->columns([
                TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),

                TextColumn::make('alternative.name')
                    ->label('Nama')
                    ->searchable(),

                TextColumn::make('preference_score')
                    ->label('Skor Akhir')
                    ->formatStateUsing(
                        fn($state) => number_format($state, 3)
                    ),

                TextColumn::make('rank')
                    ->label('Rank'),
            ])

            ->filters([])

            ->recordActions([])

            ->toolbarActions([
                Action::make('export_pdf')
                    ->label('Export PDF')
                    ->icon('heroicon-o-document-text')
                    ->action(function () {

                        $results = Result::with('alternative')->get();

                        $pdf = Pdf::loadView('pdf.results-pdf', [
                            'results' => $results
                        ]);

                        return response()->streamDownload(
                            fn() => print($pdf->output()),
                            'Hasil-TOPSIS.pdf'
                        );
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageResults::route('/'),
        ];
    }
}