<?php

namespace App\Filament\Widgets;

use App\Models\FinancialTransaction;
use Filament\Widgets\ChartWidget;

class TransactionTypesChart extends ChartWidget
{
    protected static ?string $heading = 'Types de Transactions';

    protected static ?string $description = 'Répartition des transactions';

    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 1;

    public static function canView(): bool
    {
        return request()->get('tab') === 'finances';
    }

    protected function getData(): array
    {
        $data = FinancialTransaction::groupBy('type')
            ->selectRaw('type, COUNT(*) as count')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Nombre',
                    'data' => $data->pluck('count')->toArray(),
                    'backgroundColor' => [
                        '#ef4444',
                        '#10b981',
                        '#f59e0b',
                        '#8b5cf6',
                        '#ec4899',
                    ],
                ],
            ],
            'labels' => $data->map(fn ($item) => match($item->type) {
                'withdrawal' => 'Retrait',
                'deposit' => 'Dépôt',
                'purchase' => 'Achat',
                'sale' => 'Vente',
                'refund' => 'Remboursement',
                default => $item->type,
            })->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
