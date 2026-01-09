<?php

namespace App\Filament\Resources\Articles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('active')
                    ->badge(),
                TextColumn::make('news_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('id_cat')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('important')
                    ->badge(),
                TextColumn::make('notification')
                    ->badge(),
                TextColumn::make('show_slider')
                    ->badge(),
                TextColumn::make('news_time')
                    ->searchable(),
                TextColumn::make('addBy')
                    ->searchable(),
                TextColumn::make('updateBy')
                    ->searchable(),
                TextColumn::make('addDate')
                    ->date()
                    ->sortable(),
                TextColumn::make('updateDate')
                    ->date()
                    ->sortable(),
                TextColumn::make('views')
                    ->searchable(),
                TextColumn::make('youtube_url')
                    ->searchable(),
                TextColumn::make('voiceover_url')
                    ->searchable(),
                TextColumn::make('author')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
