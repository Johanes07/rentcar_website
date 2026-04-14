<?php

namespace App\Filament\Resources\CarResource\Pages;

use App\Filament\Resources\CarResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCar extends EditRecord
{
    protected static string $resource = CarResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
{
    $data['gambar'] = $this->record->gambar ?? [];
    return $data;
}

protected function mutateFormDataBeforeSave(array $data): array
{
    if (empty($data['gambar'])) {
        $data['gambar'] = $this->record->gambar ?? [];
    }
    return $data;
}
}