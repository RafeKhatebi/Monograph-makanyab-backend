<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(['lg' => 3])
            ->components([
                Group::make()
                    ->columnSpan(['lg' => 2])
                    ->schema([
                        Section::make('Personal Information')
                            ->icon('heroicon-o-user')
                            ->columns(2)
                            ->schema([
                                TextInput::make('name')->required(),
                                TextInput::make('lastname')->default(null),
                                TextInput::make('username')->required(),
                                TextInput::make('phone')->tel()->default(null),
                                DatePicker::make('date_of_birth'),
                                Select::make('gender')
                                    ->options([
                                        'male' => 'Male',
                                        'female' => 'Female',
                                        'other' => 'Other',
                                        'prefer_not_to_say' => 'Prefer not to say',
                                    ])
                                    ->native(false)
                                    ->default(null),
                                TextInput::make('address')->default(null)->columnSpanFull(),
                                Textarea::make('bio')->default(null)->rows(3)->columnSpanFull(),
                            ]),

                        Section::make('Account Credentials')
                            ->icon('heroicon-o-lock-closed')
                            ->schema([
                                TextInput::make('email')
                                    ->label('Email Address')
                                    ->email()
                                    ->required(),
                                TextInput::make('password')
                                    ->password()
                                    ->required(),
                                DateTimePicker::make('email_verified_at'),
                            ]),

                        Section::make('Settings')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Textarea::make('settings')->default(null)->rows(3),
                            ]),
                    ]),

                Group::make()
                    ->columnSpan(['lg' => 1])
                    ->schema([
                        Section::make('Avatar')
                            ->icon('heroicon-o-camera')
                            ->schema([
                                FileUpload::make('profile_picture')
                                    ->image()
                                    ->columnSpanFull()
                                    ->directory('profile-photos')
                                    ->default(null),
                            ]),

                        Section::make('Role & Status')
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                Select::make('role')
                                    ->options(['admin' => 'Admin', 'user' => 'User', 'owner' => 'Owner'])
                                    ->default('user')
                                    ->native(false)
                                    ->required(),
                                Toggle::make('is_active')->required(),
                            ]),
                    ]),
            ]);
    }
}
