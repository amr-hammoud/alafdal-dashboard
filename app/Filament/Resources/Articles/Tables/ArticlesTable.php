<?php

namespace App\Filament\Resources\Articles\Tables;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. Cover Image
                ImageColumn::make('image')
                    ->label('Cover')
                    ->disk('public')
                    ->visibility('public'),

                // 2. Title (Searching the DB column 'news_title', displaying Accessor 'title')
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable(query: function ($query, string $search) {
                        return $query->where('news_title', 'like', "%{$search}%");
                    })
                    ->limit(50)
                    ->sortable(),

                // 3. Status
                IconColumn::make('active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('addBy')
                    ->label('Added By')
                    ->sortable()
                    ->searchable(),

                // 4. Date
                TextColumn::make('news_date')
                    ->label('Date')
                    ->date('M d, Y')
                    ->sortable(),

                TextColumn::make('news_time')
                    ->label('Time')
                    ->time('h:i A')
                    ->sortable()
                    ->searchable(),

                // 5. Views (Hidden by default to keep interface clean)
                TextColumn::make('views')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('news_id', 'desc')
            ->filters([
                
            ])
            ->recordActions([
                EditAction::make(),
            ]);
        // ->toolbarActions([
        //     BulkActionGroup::make([
        //         DeleteBulkAction::make(),
        //     ]),
        // ]);
    }
}
