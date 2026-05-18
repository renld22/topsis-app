<?php

namespace App\Filament\Resources\Scores\Pages;

use App\Filament\Resources\Scores\ScoreResource;
use Filament\Resources\Pages\ManageRecords;

class ManageScores extends ManageRecords
{
    protected static string $resource = ScoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tambahkan aksi jika perlu, misalnya CreateAction::make(),
        ];
    }
}
