<?php

namespace App\Filament\Widgets;

use App\Models\Rental;
use Filament\Widgets\ChartWidget;

class PendapatanChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Pendapatan Per Bulan';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->format('M Y');
            $data[] = (int) Rental::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total_harga');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan (Rp)',
                    'data' => $data,
                    'borderColor' => 'rgb(34, 197, 94)',
                    'fill' => true,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}