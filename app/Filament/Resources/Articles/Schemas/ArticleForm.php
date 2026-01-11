<?php

namespace App\Filament\Resources\Articles\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('news_title')
                    ->label('Title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                DatePicker::make('news_date')
                    ->label('Date')
                    ->default(now())
                    ->required(),

                TimePicker::make('news_time')
                    ->label('Time')
                    ->default(now())
                    ->required(),

                Select::make('id_cat')
                    ->label('Type')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                // Select::make('author')
                //     ->label('Author')
                //     ->relationship('author', 'name')
                //     ->searchable()
                //     ->preload()
                //     ->required(),

                FileUpload::make('news_file')
                    ->multiple()
                    ->label('Image Gallery')
                    ->disk('public')
                    ->visibility('public')
                    ->acceptedFileTypes(['image/*'])
                    ->columnSpanFull(),

                FileUpload::make('thumbnail_image')
                    ->label('Thumbnail Image')
                    ->disk('public')
                    ->visibility('public')
                    ->acceptedFileTypes(['image/*'])
                    ->columnSpanFull(),

                FileUpload::make('image')
                    ->label('Cover Image')
                    ->disk('public')
                    ->directory('uploads/news')
                    ->visibility('public')
                    ->acceptedFileTypes(['image/*'])
                    ->columnSpanFull(),

                Group::make()
                    ->schema([
                        Checkbox::make('active')
                            ->label('Active')
                            ->default(true)
                            ->required(),
                        Checkbox::make('show_slider')
                            ->label('Show on Home Slider')
                            ->default(false),
                    ]),
                Group::make()
                    ->schema([
                        Checkbox::make('important')
                            ->label('Important')
                            ->default(false),
                        Checkbox::make('notification')
                            ->label('Notification')
                            ->default(false),
                    ]),

                RichEditor::make('news_desc')
                    ->label('Content')
                    ->required()
                    ->disableToolbarButtons([
                        'attachFiles',
                        'codeBlock',
                        'blockquote',
                        'h1',
                        'h2',
                        'h3',
                        'link',
                        'table',
                    ])
                    ->columnSpanFull(),

                // 1. Save the ID for the new Relationship
                Hidden::make('user_id')
                    ->default(fn() => Auth::id()),

                // 2. Save the Name for the Legacy Website (Backend Compatibility)
                Hidden::make('addBy')
                    ->default(fn() => Auth::user()?->name ?? 'System'),

                Hidden::make('updateBy')
                    ->default(fn() => Auth::user()?->name ?? 'System'),

                DatePicker::make('addDate')
                    ->default(now())
                    ->hidden()
                    ->required(),

                DatePicker::make('updateDate')
                    ->default(now())
                    ->hidden()
                    ->required(),

                // not in use
                // TextInput::make('youtube_url')
                //     ->url()
                //     ->required(),
                // TextInput::make('voiceover_url')
                //     ->url()
                //     ->required(),

                // Check What is this for
                // Textarea::make('embedding')
                //     ->columnSpanFull(),

            ]);
    }
}
