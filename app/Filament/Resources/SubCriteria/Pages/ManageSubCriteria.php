<?php

namespace App\Filament\Resources\SubCriteria\Pages;

use App\Filament\Resources\SubCriteria\SubCriterionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

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
