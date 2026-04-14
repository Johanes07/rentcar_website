<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentSettingResource\Pages;
use App\Models\PaymentSetting;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;

class PaymentSettingResource extends Resource
{
    protected static ?string $model = PaymentSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Rekening Pembayaran';

    protected static ?string $pluralModelLabel = 'Rekening Pembayaran';

    protected static ?string $modelLabel = 'Rekening';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('bank_name')
                    ->label('Nama Bank')
                    ->placeholder('Contoh: BCA, BRI, Mandiri')
                    ->required(),

                TextInput::make('account_number')
                    ->label('Nomor Rekening')
                    ->placeholder('Contoh: 1234567890')
                    ->required(),

                TextInput::make('account_name')
                    ->label('Atas Nama')
                    ->placeholder('Nama pemilik rekening')
                    ->required(),

                Toggle::make('is_active')
                    ->label('Aktifkan rekening ini')
                    ->helperText('Hanya rekening aktif yang ditampilkan ke customer')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bank_name')
                    ->label('Bank')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('account_number')
                    ->label('No. Rekening')
                    ->copyable()
                    ->copyMessage('Nomor rekening disalin!')
                    ->copyMessageDuration(1500),

                TextColumn::make('account_name')
                    ->label('Atas Nama'),

                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

                // Toggle aktif/nonaktif langsung dari tabel
                Tables\Actions\Action::make('toggle_active')
                    ->label(fn ($record) => $record->is_active ? 'Nonaktifkan' : 'Aktifkan')
                    ->color(fn ($record) => $record->is_active ? 'danger' : 'success')
                    ->icon(fn ($record) => $record->is_active ? 'heroicon-o-x' : 'heroicon-o-check')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['is_active' => !$record->is_active]);
                        \Filament\Notifications\Notification::make()
                            ->title($record->is_active ? 'Rekening diaktifkan' : 'Rekening dinonaktifkan')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePaymentSettings::route('/'),
        ];
    }
}