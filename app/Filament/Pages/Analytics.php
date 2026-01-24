<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ArticlesChart;
use App\Filament\Widgets\StatsOverview;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class Analytics extends Page
{
    protected string $view = 'filament.pages.analytics';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartLine;

    protected static ?int $navigationSort = 3;

    public function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class,
            ArticlesChart::class,
        ];
    }
}
