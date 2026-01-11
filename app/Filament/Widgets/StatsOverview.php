<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        return [
            Stat::make('Total Articles', Article::count())
                ->description('All time news')
                ->descriptionIcon('heroicon-m-newspaper')
                ->color('primary'),

            Stat::make('Active Articles', Article::where('active', '1')->count())
                ->description('Currently published')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Total Views', Article::sum('views'))
                ->description('Across all articles')
                ->descriptionIcon('heroicon-m-eye')
                ->color('info')
                ->chart([7, 2, 10, 3, 15, 4, 17]), // Fake trend line for visual flair
        ];
    }
}
