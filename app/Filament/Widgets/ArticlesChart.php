<?php

namespace App\Filament\Widgets;

use App\Models\Article;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class ArticlesChart extends ChartWidget
{
    public function getHeading(): string
    {
        return 'Articles Published (Last 30 Days)';
    }

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Use simple string dates since we removed casting
        $start = now()->subDays(30)->format('Y-m-d');
        $end = now()->addDay()->format('Y-m-d'); // Add 1 day to ensure we cover today fully

        $data = Trend::model(Article::class)
            ->dateColumn('news_date')
            ->between(
                start: now()->subDays(30), // Keep this as Carbon for the Trend package
                end: now(),
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Articles',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                    'borderColor' => '#931e0e',
                    'backgroundColor' => '#931e0e20',
                    'fill' => true,
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
