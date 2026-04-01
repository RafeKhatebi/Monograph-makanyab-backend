<?php

namespace App\Filament\Resources\PlaceCategories\Pages;

use App\Filament\Resources\PlaceCategories\PlaceCategoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPlaceCategory extends ViewRecord
{
    protected static string $resource = PlaceCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
