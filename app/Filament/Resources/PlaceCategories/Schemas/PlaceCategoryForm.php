<?php

namespace App\Filament\Resources\PlaceCategories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PlaceCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('parent_id')
                    ->relationship('parent', 'name')
                    ->default(null),
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('icon_name')
                    ->default(null),
                TextInput::make('color_code')
                    ->required()
                    ->default('#3b82f6'),
                Toggle::make('has_menu')
                    ->required(),
                Toggle::make('has_booking')
                    ->required(),
                Toggle::make('has_delivery')
                    ->required(),
                Textarea::make('keywords')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('schema_type')
                    ->default(null),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
