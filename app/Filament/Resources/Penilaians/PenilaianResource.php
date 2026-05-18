<?php

namespace App\Filament\Resources\Penilaians;

use App\Filament\Resources\Penilaians\Pages\ManagePenilaians;
use App\Models\Score; // Menggunakan model Score agar terhitung di TOPSIS
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PenilaianResource extends Resource
{
    // Arahkan ke model Score agar nyambung ke perhitungan TOPSIS
    protected static ?string $model = \App\Models\Score::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function shouldRegisterNavigation(): bool
{
    return filament()->getCurrentPanel()->getId() === 'mahasiswa';
}
   public static function form(Schema $schema): Schema {
    return $schema->components([
        Select::make('alternative_id')
                ->label('Pilih Dosen')
                ->options(\App\Models\Alternative::pluck('name', 'id'))
                ->searchable()
                ->preload()
                ->native(false)
    
                ->extraAttributes([
        'style' => 'max-height: 150px; overflow-y: auto;',
    ])
    ->required(),
            
        Select::make('criterion_id')
            ->label('Kriteria')
            ->options(\App\Models\Criterion::pluck('name', 'id'))
            ->searchable()    
            ->preload()       
            ->native(false)   
            ->required(),
            
        TextInput::make('value')
            ->label('Nilai (1-100)')
            ->numeric()
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
                TextColumn::make('value')
                    ->label('Skor'),
                TextColumn::make('created_at')
                    ->label('Waktu Input')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                // Mahasiswa biasanya tidak boleh edit/hapus penilaian yang sudah dikirim
                // Jika ingin diaktifkan, hapus tanda komentar di bawah ini
                // EditAction::make(), 
                // DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePenilaians::route('/'),
        ];
    }
}