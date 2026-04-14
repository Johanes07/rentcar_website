<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;

class FilamentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Filament::serving(function () {
            Filament::registerWidgets([
                \App\Filament\Widgets\StatsOverviewResource::class,
                \App\Filament\Widgets\RentalChartWidget::class,
                \App\Filament\Widgets\PendapatanChartWidget::class,
            ]);
        });
    }
}