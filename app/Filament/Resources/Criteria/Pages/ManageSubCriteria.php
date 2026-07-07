<?php

namespace App\Filament\Resources\Criteria\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

use App\Filament\Resources\Criteria\SubCriterionResource;

class ManageSubCriteria extends ManageRecords
{
    protected static string $resource = SubCriterionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
