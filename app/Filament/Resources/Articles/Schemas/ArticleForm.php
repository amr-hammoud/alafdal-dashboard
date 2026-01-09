<?php

namespace App\Filament\Resources\Articles\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('news_title')
                    ->required()
                    ->columnSpanFull(),
                Textarea::make('news_desc')
                    ->required()
                    ->columnSpanFull(),
                Select::make('active')
                    ->options(['0', '1'])
                    ->default('1')
                    ->required(),
                DatePicker::make('news_date')
                    ->required(),
                TextInput::make('id_cat')
                    ->required()
                    ->numeric(),
                Select::make('important')
                    ->options([1 => '1', 0 => '0'])
                    ->required(),
                Select::make('notification')
                    ->options([1 => '1', 0 => '0'])
                    ->required(),
                Select::make('show_slider')
                    ->options([1 => '1', 0 => '0']),
                TextInput::make('news_time')
                    ->required(),
                TextInput::make('addBy')
                    ->required(),
                TextInput::make('updateBy')
                    ->required(),
                DatePicker::make('addDate')
                    ->required(),
                DatePicker::make('updateDate')
                    ->required(),
                TextInput::make('views')
                    ->required()
                    ->default('0'),
                TextInput::make('youtube_url')
                    ->url()
                    ->required(),
                TextInput::make('voiceover_url')
                    ->url()
                    ->required(),
                TextInput::make('author')
                    ->required(),
                Textarea::make('thumbnail_image')
                    ->columnSpanFull(),
                Textarea::make('image')
                    ->columnSpanFull(),
                Textarea::make('embedding')
                    ->columnSpanFull(),
            ]);
    }
}
