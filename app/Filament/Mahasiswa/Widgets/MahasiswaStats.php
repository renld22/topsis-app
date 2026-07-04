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
            Stat::make('Total Kriteria', '5')
                ->description('Semua kriteria TOPSIS tersedia')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->descriptionColor('primary')
                ->color('primary')
                ->chart([20, 40, 60, 80, 100]),

            Stat::make('Status Akun', 'Aktif')
                ->description('Akun Anda siap memberi nilai')
                ->descriptionIcon('heroicon-m-check-badge')
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