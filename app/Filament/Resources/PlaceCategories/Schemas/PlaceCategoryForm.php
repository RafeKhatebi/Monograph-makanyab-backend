<?php

namespace App\Filament\Resources\PlaceCategories\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PlaceCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(['lg' => 3])
            ->components([
                Group::make()
                    ->columnSpan(['lg' => 2])
                    ->schema([
                        Section::make('Category Details')
                            ->icon('heroicon-o-tag')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                TextInput::make('slug')
                                    ->required(),
                                Select::make('parent_id')
                                    ->relationship('parent', 'name')
                                    ->searchable()
                                    ->native(false)
                                    ->default(null),
                                TextInput::make('schema_type')
                                    ->default(null),
                            ]),
                        Section::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                Textarea::make('keywords')
                                    ->default(null)
                                    ->rows(3),
                            ]),
                    ]),

                Group::make()
                    ->columnSpan(['lg' => 1])
                    ->schema([
                        Section::make('Appearance')
                            ->icon('heroicon-o-paint-brush')
                            ->schema([
                                TextInput::make('icon_name')
                                    ->default(null),
                                ColorPicker::make('color_code')
                                    ->required()
                                    ->default('#3b82f6'),
                                TextInput::make('sort_order')
                                    ->required()
                                    ->numeric()
                                    ->default(0),
                            ]),
                        Section::make('Features')
                            ->icon('heroicon-o-adjustments-horizontal')
                            ->schema([
                                Toggle::make('has_menu')->required(),
                                Toggle::make('has_booking')->required(),
                                Toggle::make('has_delivery')->required(),
                            ]),
                        Section::make('Status')
                            ->icon('heroicon-o-check-circle')
                            ->schema([
                                Toggle::make('is_active')->required(),
                            ]),
                    ]),
            ]);
    }
}
