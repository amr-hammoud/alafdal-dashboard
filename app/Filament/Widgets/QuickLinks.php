<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\Analytics;
use App\Filament\Resources\Articles\ArticleResource;
use App\Filament\Resources\Authors\AuthorResource;
use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Users\UserResource;
use Filament\Widgets\Widget;

class QuickLinks extends Widget
{
    protected string $view = 'filament.widgets.quick-links';

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 0;

    public function getLinks(): array
    {
        return [
            [
                'label' => 'Articles',
                'url' => ArticleResource::getUrl(),
                'icon' => 'heroicon-o-newspaper',
            ],
            [
                'label' => 'Authors',
                'url' => AuthorResource::getUrl(),
                'icon' => 'heroicon-o-user-group',
            ],
            [
                'label' => 'Categories',
                'url' => CategoryResource::getUrl(),
                'icon' => 'heroicon-o-tag',
            ],
            [
                'label' => 'Users',
                'url' => UserResource::getUrl(),
                'icon' => 'heroicon-o-users',
            ],
            [
                'label' => 'Analytics',
                'url' => Analytics::getUrl(),
                'icon' => 'heroicon-o-presentation-chart-line',
            ],
        ];
    }
}
