<?php

namespace App\Filament\Resources\PlaceCategories\Pages;

use App\Filament\Resources\PlaceCategories\PlaceCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPlaceCategory extends EditRecord
{
    protected static string $resource = PlaceCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
