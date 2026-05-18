<?php

namespace App\Filament\Widgets;

use App\Services\TopsisService;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;
class TopsisChart extends ChartWidget
{
    protected ?string $heading = 'Topsis Chart';

    protected function getData(): array
{
    // HAPUS atau bypass Cache::remember agar langsung narik data segar dari database
    $results = app(TopsisService::class)->calculateTopsis();
    
    // Mengurutkan data berdasarkan peringkat (rank)
    $results = collect($results)->sortBy('rank')->values();

    return [
        'datasets' => [
            [
                'label' => 'Preference Score',
                'data' => $results->pluck('preference_score')->toArray(),
                'backgroundColor' => 'rgba(99, 102, 241, 0.7)',
                'borderColor' => 'rgba(79, 70, 229, 1)',
                'borderWidth' => 1,
            ],
        ],
        'labels' => $results->pluck('name')->toArray(),
    ];
}
    protected function getType(): string
    {
        return 'bar';
    }
}
