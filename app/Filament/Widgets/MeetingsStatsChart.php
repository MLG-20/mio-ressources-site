<?php

namespace App\Filament\Widgets;

use App\Models\Meeting;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class MeetingsStatsChart extends ChartWidget
{
    protected static ?string $heading = 'Réunions - Activité';

    protected static ?string $description = 'Nombre de réunions par statut';

    protected static ?int $sort = 6;

    public static function canView(): bool
    {
        return request()->get('tab') === 'contenus';
    }

    protected function getData(): array
    {
        $data = Meeting::groupBy('status')
            ->selectRaw('status, COUNT(*) as count')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Nombre',
                    'data' => $data->pluck('count')->toArray(),
                    'backgroundColor' => [
                        '#3b82f6',
                        '#f59e0b',
                        '#10b981',
                        '#ef4444',
                    ],
                ],
            ],
            'labels' => $data->map(fn ($item) => match($item->status) {
                'scheduled' => 'Programmées',
                'in_progress' => 'En cours',
                'completed' => 'Terminées',
                'cancelled' => 'Annulées',
                default => $item->status,
            })->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
