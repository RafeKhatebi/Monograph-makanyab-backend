<?php

namespace App\Filament\Resources\Places\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PlacesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover_image')
                ->circular() // Makes it look like an avatar
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('user_id')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('place_categories_id')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('tagline')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('phone_1')
                    ->searchable(),
                TextColumn::make('phone_2')
                    ->searchable(),
                TextColumn::make('whatsapp')
                    ->searchable(),
                TextColumn::make('website')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('address')
                    ->searchable(),
                TextColumn::make('country')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('province')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('city')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('district')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('subdistrict')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('village')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('rt_rw')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('neighborhood')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('postal_code')
                    ->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('latitude')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('longitude')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('price_level')
                    ->badge(),
                IconColumn::make('is_verified')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
