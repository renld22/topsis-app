<?php

namespace App\Filament\Mahasiswa\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MahasiswaStats extends BaseWidget
{
    
    protected function getStats(): array
    {
        return [
            Stat::make('Total Kriteria Penilaian', '5')
                ->description('Kriteria yang digunakan dalam metode TOPSIS')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('primary'),

            Stat::make('Status Akun Mahasiswa', 'Aktif')
                ->description('Anda terdaftar sebagai penilai')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
                Stat::make('Sistem Informasi', 'Penilaian Evaluasi Kinerja Dosen')
                ->description('Universitas Muhammadiyah Banten') // Menyesuaikan kampus Anda
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('primary'),
         ];
    }
}