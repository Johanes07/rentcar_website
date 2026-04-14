<?php

namespace App\Filament\Widgets;

use App\Models\Car;
use App\Models\Customer;
use App\Models\Rental;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverviewResource extends BaseWidget
{
    protected function getCards(): array
    {
        $totalPendapatan = Rental::sum('total_harga');

        return [
            Card::make('Total Mobil', Car::count())
                ->description('Semua mobil terdaftar')
                ->color('primary'),

            Card::make('Mobil Tersedia', Car::where('status', 'available')->count())
                ->description('Siap disewa')
                ->color('success'),

            Card::make('Mobil Disewa', Car::where('status', 'rented')->count())
                ->description('Sedang dalam sewa')
                ->color('warning'),

            Card::make('Total Customer', Customer::count())
                ->description('Customer terdaftar')
                ->color('secondary'),

            Card::make('Rental Aktif', Rental::whereHas('car', fn($q) => $q->where('status', 'rented'))->count())
                ->description('Sewa sedang berjalan')
                ->color('warning'),

            Card::make('Total Pendapatan', 'Rp ' . number_format($totalPendapatan, 0, ',', '.'))
                ->description('Semua waktu')
                ->color('success'),
        ];
    }
}