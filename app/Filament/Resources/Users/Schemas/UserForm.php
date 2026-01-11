<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => \Illuminate\Support\Facades\Hash::make($state))
                    ->dehydrated(fn($state) => filled($state)) // Only update if user typed a new password
                    ->required(fn(string $context): bool => $context === 'create'), // Required only on create

                \Filament\Forms\Components\Select::make('role')
                    ->label('Role')
                    ->options(UserRole::class) // Filament V4 reads the Enum labels automatically!
                    ->required()
                    ->default(UserRole::EDITOR),
            ]);
    }
}
