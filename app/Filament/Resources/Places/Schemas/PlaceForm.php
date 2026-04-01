<?php

namespace App\Filament\Resources\Places\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PlaceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required(),
                TextInput::make('place_categories_id')
                    ->required()
                    ->numeric(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('tagline')
                    ->default(null),
                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('phone_1')
                    ->tel()
                    ->required(),
                TextInput::make('phone_2')
                    ->tel()
                    ->default(null),
                TextInput::make('whatsapp')
                    ->default(null),
                TextInput::make('website')
                    ->url()
                    ->default(null),
                Textarea::make('social_links')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('address')
                    ->required(),
                TextInput::make('country')
                    ->required(),
                TextInput::make('province')
                    ->required(),
                TextInput::make('city')
                    ->required(),
                TextInput::make('district')
                    ->required(),
                TextInput::make('subdistrict')
                    ->default(null),
                TextInput::make('village')
                    ->default(null),
                TextInput::make('rt_rw')
                    ->default(null),
                TextInput::make('neighborhood')
                    ->default(null),
                TextInput::make('postal_code')
                    ->default(null),
                TextInput::make('latitude')
                    ->numeric()
                    ->default(null),
                TextInput::make('longitude')
                    ->numeric()
                    ->default(null),
                FileUpload::make('cover_image')
                    ->image(),
                Textarea::make('gallery')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(['open' => 'Open', 'closed' => 'Closed', 'temporarily_closed' => 'Temporarily closed'])
                    ->default('open')
                    ->required(),
                Select::make('price_level')
                    ->options(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'luxury' => 'Luxury'])
                    ->default('medium')
                    ->required(),
                Toggle::make('is_verified')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
