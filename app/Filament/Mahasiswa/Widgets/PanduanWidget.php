<?php

namespace App\Filament\Mahasiswa\Widgets;

use Filament\Widgets\Widget;

class PanduanWidget extends Widget
{
    // Hapus kata 'static' di bawah ini
    protected string $view = 'filament.widgets.panduan-penilaian';
    
    // Agar widget panduan ini lebar (memakan 2 kolom)
    protected int | string | array $columnSpan = 'full';
}