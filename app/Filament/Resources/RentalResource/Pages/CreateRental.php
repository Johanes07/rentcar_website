<?php

namespace App\Filament\Resources\RentalResource\Pages;

use App\Filament\Resources\RentalResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRental extends CreateRecord
{
    protected static string $resource = RentalResource::class;

    // ← Tambahkan ini
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $rental = parent::handleRecordCreation($data);

        $rental->car->update(['status' => 'rented']);

        return $rental;
    }
}