<?php

namespace App\Filament\Mahasiswa\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MahasiswaStats extends BaseWidget
{
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Kriteria', \App\Models\Criterion::count())
                ->description('Semua kriteria TOPSIS')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->descriptionColor('primary')
                ->color('primary')
                ->chart([20, 40, 60, 80, 100]),

            Stat::make('Total Sub Kriteria', \App\Models\SubCriterion::count())
                ->description('Semua sub-kriteria evaluasi')
                ->descriptionIcon('heroicon-m-list-bullet')
                ->descriptionColor('success')
                ->color('success')
                ->chart([15, 35, 55, 75, 95]),

            Stat::make('Sistem', 'Evaluasi Mengajar Dosen')
                ->description('Universitas Muhammadiyah Banten')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->descriptionColor('warning')
                ->color('warning')
                ->chart([10, 30, 50, 70, 90]),
        ];
    }
}