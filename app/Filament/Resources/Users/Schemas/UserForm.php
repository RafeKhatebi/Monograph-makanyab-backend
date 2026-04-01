<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('lastname')
                    ->default(null),
                TextInput::make('username')
                    ->required(),
                TextInput::make('phone')
                    ->tel()
                    ->default(null),
                Select::make('role')
                    ->options(['admin' => 'Admin', 'user' => 'User', 'owner' => 'Owner'])
                    ->default('user')
                    ->required(),
                Select::make('gender')
                    ->options([
            'male' => 'Male',
            'female' => 'Female',
            'other' => 'Other',
            'prefer_not_to_say' => 'Prefer not to say',
        ])
                    ->default(null),
                DatePicker::make('date_of_birth'),
                TextInput::make('address')
                    ->default(null),
                Textarea::make('bio')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('profile_picture')
                    ->default(null),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
                Textarea::make('settings')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
