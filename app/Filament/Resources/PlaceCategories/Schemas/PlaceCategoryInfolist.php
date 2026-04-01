<?php

namespace App\Filament\Resources\PlaceCategories\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PlaceCategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('parent.name')
                    ->label('Parent')
                    ->placeholder('-'),
                TextEntry::make('name'),
                TextEntry::make('slug'),
                TextEntry::make('icon_name')
                    ->placeholder('-'),
                TextEntry::make('color_code'),
                IconEntry::make('has_menu')
                    ->boolean(),
                IconEntry::make('has_booking')
                    ->boolean(),
                IconEntry::make('has_delivery')
                    ->boolean(),
                TextEntry::make('keywords')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('schema_type')
                    ->placeholder('-'),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('sort_order')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
