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
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('user_id')
                    ->searchable(),
                TextColumn::make('place_categories_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('tagline')
                    ->searchable(),
                TextColumn::make('phone_1')
                    ->searchable(),
                TextColumn::make('phone_2')
                    ->searchable(),
                TextColumn::make('whatsapp')
                    ->searchable(),
                TextColumn::make('website')
                    ->searchable(),
                TextColumn::make('address')
                    ->searchable(),
                TextColumn::make('country')
                    ->searchable(),
                TextColumn::make('province')
                    ->searchable(),
                TextColumn::make('city')
                    ->searchable(),
                TextColumn::make('district')
                    ->searchable(),
                TextColumn::make('subdistrict')
                    ->searchable(),
                TextColumn::make('village')
                    ->searchable(),
                TextColumn::make('rt_rw')
                    ->searchable(),
                TextColumn::make('neighborhood')
                    ->searchable(),
                TextColumn::make('postal_code')
                    ->searchable(),
                TextColumn::make('latitude')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('longitude')
                    ->numeric()
                    ->sortable(),
                ImageColumn::make('cover_image'),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('price_level')
                    ->badge(),
                IconColumn::make('is_verified')
                    ->boolean(),
                IconColumn::make('is_active')
                    ->boolean(),
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
