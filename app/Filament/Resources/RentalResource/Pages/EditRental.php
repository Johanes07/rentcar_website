<?php

namespace App\Filament\Resources\RentalResource\Pages;

use App\Filament\Resources\RentalResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRental extends EditRecord
{
    protected static string $resource = RentalResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }


     protected function mutateFormDataBeforeFill(array $data): array
{
    $data['foto_ktp'] = $this->record->foto_ktp ?? [];
    return $data;
}

protected function mutateFormDataBeforeSave(array $data): array
{
    if (empty($data['foto_ktp'])) {
        $data['foto_ktp'] = $this->record->foto_ktp ?? [];
    }
    return $data;
}
}
