<?php

namespace App\Filament\Resources\CarResource\Pages;

use App\Filament\Resources\CarResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCar extends CreateRecord
{

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected static string $resource = CarResource::class;
}
