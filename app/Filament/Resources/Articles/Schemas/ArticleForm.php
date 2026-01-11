<?php

namespace App\Filament\Resources\Articles\Schemas;

use App\Models\Author;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Section::make('Article Content')
                            ->collapsible()
                            ->schema([
                                TextInput::make('news_title')
                                    ->label('Title')
                                    ->required()
                                    ->maxLength(255),

                                RichEditor::make('news_desc')
                                    ->label('Content')
                                    ->required()
                                    ->columnSpanFull()
                                    ->disableToolbarButtons([
                                        'attachFiles',
                                    ]),
                            ]),

                        Section::make('Media')
                            ->collapsible()
                            ->schema([
                                // 1. COVER IMAGE (Main)
                                FileUpload::make('image')
                                    ->label('Cover Image')
                                    ->image()
                                    ->disk('public')
                                    // We save to a temp folder first; the Observer will move it to {id}/
                                    ->directory('uploads/news/temp')
                                    ->visibility('public')
                                    ->required(),

                                // 2. GALLERY (Multiple Images)
                                Repeater::make('images')
                                    ->relationship('images') // Connects to the hasMany we created
                                    ->label('Image Gallery')
                                    ->schema([
                                        FileUpload::make('image_name')
                                            ->label('Image')
                                            ->image()
                                            ->disk('public')
                                            ->directory('uploads/news/temp')
                                            ->visibility('public')
                                            ->required(),

                                        // These satisfy the "NOT NULL" rules during the first INSERT.
                                        // The Observer will overwrite 'thumb_name' milliseconds later.

                                        Hidden::make('thumb_name')
                                            ->default('')
                                            ->dehydrateStateUsing(fn($state) => $state ?? ''),

                                        Hidden::make('active')
                                            ->default('1')
                                            ->dehydrateStateUsing(fn($state) => '1'),

                                        Hidden::make('coverpage')
                                            ->default('0')
                                            ->dehydrateStateUsing(fn($state) => '0'),
                                    ])
                                    ->grid(3) // Show 3 images per row
                                    ->defaultItems(0)
                                    ->reorderableWithButtons(),
                            ]),
                    ])
                    ->columnSpan(2),

                Group::make()
                    ->schema([
                        Section::make('Publishing Details')
                            ->collapsible()
                            ->schema([
                                Select::make('id_cat')
                                    ->label('Category')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('author')
                                    ->label('Author')
                                    ->options(Author::all()->pluck('name', 'name'))
                                    ->searchable()
                                    ->required()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->required()
                                            ->maxLength(255),
                                    ])
                                    ->createOptionUsing(function (array $data) {
                                        $newAuthor = Author::create($data);
                                        return $newAuthor->name;
                                    }),

                                DatePicker::make('news_date')
                                    ->label('Publish Date')
                                    ->default(now())
                                    ->required(),

                                TimePicker::make('news_time')
                                    ->label('Time')
                                    ->default(now())
                                    ->required(),
                            ])->columnSpan(1),

                        Section::make('Settings')
                            ->collapsible()
                            ->schema([
                                Checkbox::make('active')
                                    ->label('Published')
                                    ->default(true)
                                    // Force convert boolean true/false to string '1'/'0'
                                    ->dehydrateStateUsing(fn($state) => $state ? '1' : '0'),

                                Checkbox::make('important')
                                    ->label('Breaking News')
                                    ->default(false)
                                    ->dehydrateStateUsing(fn($state) => $state ? '1' : '0'),

                                Checkbox::make('show_slider')
                                    ->label('Show on Slider')
                                    ->default(false)
                                    ->dehydrateStateUsing(fn($state) => $state ? '1' : '0'),

                                Checkbox::make('notification')
                                    ->label('Send Notification')
                                    ->default(false)
                                    ->dehydrateStateUsing(fn($state) => $state ? '1' : '0'),
                            ])->columnSpan(1),

                        // HIDDEN AUDIT FIELDS
                        Hidden::make('user_id')
                            ->default(fn() => Auth::id())
                            ->dehydrated(true),


                        Hidden::make('addBy')
                            ->default(fn() => Auth::user()?->name ?? 'System')
                            ->dehydrated(true),

                        Hidden::make('updateBy')
                            ->default(fn() => Auth::user()?->name ?? 'System')
                            ->dehydrated(true),

                        Hidden::make('addDate')
                            ->default(now())
                            ->dehydrated(true),

                        Hidden::make('updateDate')
                            ->default(now())
                            ->dehydrated(true),

                        Hidden::make('views')
                            ->default(0)
                            ->dehydrated(true),
                    ])
                    ->columnSpan(2),

            ]);
    }
}
