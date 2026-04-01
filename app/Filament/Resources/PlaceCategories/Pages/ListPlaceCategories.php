<?php

namespace App\Filament\Resources\PlaceCategories\Pages;

use App\Filament\Resources\PlaceCategories\PlaceCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPlaceCategories extends ListRecords
{
    protected static string $resource = PlaceCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
