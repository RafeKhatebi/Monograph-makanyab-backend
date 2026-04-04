<?php

namespace App\Filament\Resources\Places\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PlaceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(['lg' => 3])
            ->components([
                Group::make()
                    ->columnSpan(['lg' => 2])
                    ->schema([
                        Section::make('Basic Information')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                TextInput::make('slug')
                                    ->required(),
                                TextInput::make('tagline')
                                    ->default(null),
                                Textarea::make('description')
                                    ->default(null)
                                    ->rows(4)
                                    ->columnSpanFull(),
                            ]),

                        Section::make('Contact & Links')
                            ->icon('heroicon-o-phone')
                            ->columns(2)
                            ->schema([
                                TextInput::make('phone_1')->tel()->required(),
                                TextInput::make('phone_2')->tel()->default(null),
                                TextInput::make('whatsapp')->default(null),
                                TextInput::make('website')->url()->default(null),
                                Textarea::make('social_links')
                                    ->default(null)
                                    ->columnSpanFull(),
                            ]),

                        Section::make('Location')
                            ->icon('heroicon-o-map-pin')
                            ->columns(2)
                            ->schema([
                                TextInput::make('address')->required()->columnSpanFull(),
                                TextInput::make('country')->required(),
                                TextInput::make('province')->required(),
                                TextInput::make('city')->required(),
                                TextInput::make('district')->required(),
                                TextInput::make('subdistrict')->default(null),
                                TextInput::make('village')->default(null),
                                TextInput::make('rt_rw')->default(null),
                                TextInput::make('neighborhood')->default(null),
                                TextInput::make('postal_code')->default(null),
                                TextInput::make('latitude')->numeric()->default(null),
                                TextInput::make('longitude')->numeric()->default(null),
                            ]),

                        Section::make('Gallery')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Textarea::make('gallery')
                                    ->helperText('Managed via the Media table.')
                                    ->disabled()
                                    ->default(null)
                                    ->rows(3),
                            ]),
                    ]),

                Group::make()
                    ->columnSpan(['lg' => 1])
                    ->schema([
                        Section::make('Ownership')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->native(false)
                                    ->required(),
                                Select::make('place_category_id')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->native(false)
                                    ->required(),
                            ]),

                        Section::make('Status')
                            ->icon('heroicon-o-check-circle')
                            ->schema([
                                Select::make('status')
                                    ->options(['open' => 'Open', 'closed' => 'Closed', 'temporarily_closed' => 'Temporarily Closed'])
                                    ->default('open')
                                    ->native(false)
                                    ->required(),
                                Select::make('price_level')
                                    ->options(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'luxury' => 'Luxury'])
                                    ->default('medium')
                                    ->native(false)
                                    ->required(),
                                Toggle::make('is_verified')->required(),
                                Toggle::make('is_active')->required(),
                            ]),
                    ]),
            ]);
    }
}
