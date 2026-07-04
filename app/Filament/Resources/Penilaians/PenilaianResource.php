<?php

namespace App\Filament\Resources\Penilaians;

use App\Filament\Resources\Penilaians\Pages\ManagePenilaians;
use App\Models\Score; 
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;

class PenilaianResource extends Resource
{
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
                ->required()
                ->helperText('Pilih kriteria terlebih dahulu sebelum mengisi nilai'),
                
            // FIELD PENILAIAN HANYA MUNCUL JIKA KRITERIA SUDAH DIPILIH
            TextInput::make('value') 
                ->label('Penilaian')
                ->numeric()
                ->minValue(1)
                ->maxValue(5)
                ->required(fn ($get) => !empty($get('criterion_id')))
                ->helperText('Isi nilai 1-5 (Pastikan kriteria sudah dipilih terlebih dahulu)')
                
                // Matikan validasi oranye bawaan Chrome
                ->extraAttributes(['novalidate' => 'novalidate']) 
                
                // Aturan validasi Laravel manual
                ->rules(['numeric', 'between:1,5'])
                
                // Pesan validasi kustom (sekarang 100% berfungsi)
                ->validationMessages([
                    'required' => 'Skor wajib diisi (Kriteria & Nilai harus lengkap)',
                    'numeric'  => 'Harus berupa angka!',
                    'between'  => 'Nilai harus antara 1-5',
                ]),
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
                //
            ]);
            // ⚠️ SEKARANG KOSONG: toolbarActions & bulkActions dihapus total agar checkbox di sebelah kiri hilang
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePenilaians::route('/'),
        ];
    }
}