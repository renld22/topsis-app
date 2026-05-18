<?php

namespace App\Filament\Resources\Criteria\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

use App\Filament\Resources\Criteria\CriterionResource;

class ManageCriteria extends ManageRecords
{
    protected static string $resource = CriterionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}