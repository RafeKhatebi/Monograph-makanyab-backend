<?php

namespace App\Filament\Resources\PlaceCategories;

use App\Filament\Resources\PlaceCategories\Pages\CreatePlaceCategory;
use App\Filament\Resources\PlaceCategories\Pages\EditPlaceCategory;
use App\Filament\Resources\PlaceCategories\Pages\ListPlaceCategories;
use App\Filament\Resources\PlaceCategories\Pages\ViewPlaceCategory;
use App\Filament\Resources\PlaceCategories\Schemas\PlaceCategoryForm;
use App\Filament\Resources\PlaceCategories\Schemas\PlaceCategoryInfolist;
use App\Filament\Resources\PlaceCategories\Tables\PlaceCategoriesTable;
use App\Models\PlaceCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class PlaceCategoryResource extends Resource
{
    protected static ?string $model = PlaceCategory::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'PlaceCategory';

    public static function form(Schema $schema): Schema
    {
        return PlaceCategoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PlaceCategoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PlaceCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPlaceCategories::route('/'),
            'create' => CreatePlaceCategory::route('/create'),
            'view' => ViewPlaceCategory::route('/{record}'),
            'edit' => EditPlaceCategory::route('/{record}/edit'),
        ];
    }
}
