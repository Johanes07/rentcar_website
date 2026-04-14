<?php

namespace App\Filament\Resources\PaymentSettingResource\Pages;

use App\Filament\Resources\PaymentSettingResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePaymentSettings extends ManageRecords
{
    protected static string $resource = PaymentSettingResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
