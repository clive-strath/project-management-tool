<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\ChartWidget;

class ProjectDistribution extends ChartWidget
{
    protected static ?string $heading = 'Projects by Status';

    protected function getData(): array
    {
        $data = Project::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Projects',
                    'data' => array_values($data),
                    'backgroundColor' => ['#10b981', '#3b82f6', '#f59e0b'],
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
