<?php 
 
namespace App\Filament\Pages; 
 
use Filament\Tables; 
use Filament\Pages\Page; 
use Filament\Tables\Table; 
use Filament\Actions\Action; 
use App\Services\TopsisService; 
use Illuminate\Support\Facades\Cache; 
use Filament\Tables\Columns\TextColumn; 
 
 
class TopsisCalculation extends Page implements Tables\Contracts\HasTable 
{ 
    use Tables\Concerns\InteractsWithTable; 
 
    // sesuai signature parent (global BackedEnum) 
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-calculator'; 
 
    protected static ?string $navigationLabel = 'Kalkulasi TOPSIS'; 
 
    protected static ?int $navigationSort = 4; 
 
    // non-static di Filament v4 
    protected string $view = 'filament.pages.topsis-calculation'; 
 
    public static function canAccess(): bool 
    {
        return auth()->user()->role === 'admin';
    }
    // -------------------------
    public $results; 
 
    public function mount(TopsisService $topsisService) 
    {
         $this->results = Cache::remember('topsis_results', 3600, fn () => $topsisService->calculateTopsis());
    } 
 protected function getHeaderActions(): array
{
    return [
        Action::make('recalculateAll')
            ->label('Recalculate All')
            ->icon('heroicon-o-arrow-path')
            ->color('warning')
            ->requiresConfirmation() // biar ada konfirmasi sebelum hitung
            ->action(function (TopsisService $topsisService) {
                Cache::forget('topsis_results');
                $this->results = $topsisService->calculateTopsis();
                
                // Memberikan feedback ke user
                \Filament\Notifications\Notification::make()
                    ->title('Berhasil dihitung ulang!')
                    ->success()
                    ->send();
            }),
    ];
}
    public function table(Table $table): Table 
    { 
        return $table 
            ->columns($this->getTableColumns()) 
            ->actions($this->getTableActions()) 
            
            ->records(fn () => collect($this->results)); 
    } 
 
    protected function getTableColumns(): array 
    { 
        return [ 
            TextColumn::make('name')->label('Alternative'), 
            TextColumn::make('preference_score') 
                ->label('Preference Score') 
                ->formatStateUsing(fn ($state) => number_format($state, 3)),
            TextColumn::make('rank')->label('Rank'), 
        ]; 
    } 
 
    protected function getTableActions(): array 
    { 
 
 
    return [ 
        
]; 
} 
} 