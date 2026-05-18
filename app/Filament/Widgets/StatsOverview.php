<?php

namespace App\Filament\Widgets;

use App\Models\Result;
use App\Models\Criterion;
use App\Models\Alternative;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Kandidat', Alternative::count())
                ->description('Number of candidates')
                ->icon('heroicon-o-user-group'), 

            Stat::make('Total Kriteria', Criterion::count())
                ->description('Evaluation criteria')
                ->icon('heroicon-o-scale'), 

            Stat::make('Top Rank Bantuan', Result::orderBy('rank')->first()?->alternative->name ?? 'N/A')
                ->description('Highest preference score')
                ->icon('heroicon-o-trophy'), 
        ];
    }
}
