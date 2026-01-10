<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Category Name')
                    ->required()
                    ->maxLength(255),

                // PARENT SELECTOR
                // This lets you pick another category to be the "Parent"
                Select::make('parent_id')
                    ->label('Parent Category')
                    ->relationship('parent', 'name') // Uses the 'parent' relationship in your Model
                    ->searchable()
                    ->preload()
                    ->placeholder('Select a parent (optional)'),

                Group::make()
                    ->schema([
                        Checkbox::make('is_parent')
                            ->label('Is a Main Section?')
                            ->helperText('Check this if this category will contain sub-categories.')
                            ->default(false),

                        Checkbox::make('active')
                            ->label('Active')
                            ->default(true),
                    ])->columns(2),

                // AUDIT FIELDS (Hidden)
                // Hidden::make('addBy')
                //     ->default(fn() => auth()->user()->name ?? 'System'),

                Hidden::make('addDate')
                    ->default(now()),
            ]);
    }
}
