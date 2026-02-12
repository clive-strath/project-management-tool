<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Active system users')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
            Stat::make('Total Projects', Project::count())
                ->description('Ongoing projects')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('info'),
            Stat::make('Pending Tasks', Task::where('status', '!=', 'done')->count())
                ->description('Tasks in progress')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('warning'),
        ];
    }
}
