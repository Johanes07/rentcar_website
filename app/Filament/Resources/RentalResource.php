<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RentalResource\Pages;
use App\Filament\Resources\RentalResource\RelationManagers;
use App\Models\Rental;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RentalExport;

class RentalResource extends Resource
{
    protected static ?string $model = Rental::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

   public static function form(Form $form): Form
{
    return $form
        ->schema([
            Select::make('car_id')
                ->label('Mobil')
                ->options(
                    \App\Models\Car::all()->mapWithKeys(function ($car) {
                        return [
                            $car->id => $car->nama . ' - ' . ($car->status === 'available' ? '✅ Available' : '❌ Rented')
                        ];
                    })
                )
                ->searchable()
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $car = \App\Models\Car::find($state);
                    if ($car && $get('tanggal_sewa') && $get('tanggal_kembali')) {
                        $days = max(1, \Carbon\Carbon::parse($get('tanggal_sewa'))
                            ->diffInDays(\Carbon\Carbon::parse($get('tanggal_kembali'))));
                        $set('total_harga', $days * $car->harga_sewa);
                    }
                }),

            Select::make('customer_id')
                ->relationship('customer', 'nama')
                ->required()
                ->label('Customer'),

            TextInput::make('nama_penyewa')
                ->label('Nama Penyewa')
                ->required(),

            TextInput::make('no_hp')
                ->label('No. HP')
                ->required(),

            TextInput::make('no_ktp')
                ->label('No. KTP'),

            TextInput::make('alamat')
                ->label('Alamat'),

            DatePicker::make('tanggal_sewa')
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $car = \App\Models\Car::find($get('car_id'));
                    if ($car && $state && $get('tanggal_kembali')) {
                        $days = max(1, \Carbon\Carbon::parse($state)
                            ->diffInDays(\Carbon\Carbon::parse($get('tanggal_kembali'))));
                        $set('total_harga', $days * $car->harga_sewa);
                    }
                }),

            DatePicker::make('tanggal_kembali')
                ->required()
                ->reactive()
                ->after('tanggal_sewa')
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $car = \App\Models\Car::find($get('car_id'));
                    if ($car && $get('tanggal_sewa') && $state) {
                        $days = max(1, \Carbon\Carbon::parse($get('tanggal_sewa'))
                            ->diffInDays(\Carbon\Carbon::parse($state)));
                        $set('total_harga', $days * $car->harga_sewa);
                    }
                }),

            TextInput::make('total_harga')
                ->disabled()
                ->formatStateUsing(fn ($state) => $state ? 'Rp ' . number_format($state, 0, ',', '.') : null)
                ->dehydrateStateUsing(fn ($state) => (int) str_replace(['Rp', '.', ' '], '', $state)),

            Select::make('status_booking')
                ->label('Status Booking')
                ->options([
                    'pending'   => '🕐 Pending',
                    'confirmed' => '✅ Confirmed',
                    'cancelled' => '❌ Cancelled',
                    'completed' => '🏁 Completed',
                ])
                ->required(),

            Forms\Components\FileUpload::make('foto_ktp')
                ->label('Foto KTP')
                ->disk('public')
                ->directory('ktp')
                ->image()
                ->maxSize(2048),

            Forms\Components\FileUpload::make('bukti_transfer')
                ->label('Bukti Transfer')
                ->disk('public')
                ->directory('bukti_transfer')
                ->image()
                ->maxSize(2048),

            Forms\Components\Textarea::make('catatan')
                ->label('Catatan')
                ->rows(3),
        ]);
}

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('car.nama')
                ->label('Mobil')
                ->searchable()
                ->sortable(),

            TextColumn::make('nama_penyewa')
                ->label('Nama Penyewa')
                ->searchable(),

            TextColumn::make('no_hp')
                ->label('No. HP')
                ->searchable(),

            TextColumn::make('tanggal_sewa')
                ->label('Tgl Sewa')
                ->date('d M Y')
                ->sortable(),

            TextColumn::make('tanggal_kembali')
                ->label('Tgl Kembali')
                ->date('d M Y')
                ->sortable(),

            TextColumn::make('total_harga')
                ->label('Total')
                ->money('IDR', true)
                ->sortable(),

            Tables\Columns\BadgeColumn::make('status_booking')
                ->label('Status')
                ->colors([
                    'warning' => 'pending',
                    'success' => 'confirmed',
                    'danger'  => 'cancelled',
                    'primary' => 'completed',
                ])
                ->icons([
                    'heroicon-o-clock'       => 'pending',
                    'heroicon-o-check-circle' => 'confirmed',
                    'heroicon-o-x-circle'    => 'cancelled',
                    'heroicon-o-badge-check' => 'completed',
                ])
                ->sortable(),

            Tables\Columns\ImageColumn::make('foto_ktp')
    ->label('KTP')
    ->disk('public')
    ->height(40)
    ->width(60)
    ->getStateUsing(fn ($record) => $record->foto_ktp 
        ? asset('storage/' . $record->foto_ktp) 
        : null)
    ->extraImgAttributes(['style' => 'border-radius:6px;object-fit:cover;']),

Tables\Columns\ImageColumn::make('bukti_transfer')
    ->label('Bukti Transfer')
    ->disk('public')
    ->height(40)
    ->width(60)
    ->getStateUsing(fn ($record) => $record->bukti_transfer 
        ? asset('storage/' . $record->bukti_transfer) 
        : null)
    ->extraImgAttributes(['style' => 'border-radius:6px;object-fit:cover;']),
                ])
        ->filters([
            Tables\Filters\SelectFilter::make('status_booking')
                ->label('Status')
                ->options([
                    'pending'   => 'Pending',
                    'confirmed' => 'Confirmed',
                    'cancelled' => 'Cancelled',
                    'completed' => 'Completed',
                ]),
        ])
        ->headerActions([
            Tables\Actions\Action::make('export_csv')
                ->label('Export CSV')
                ->color('success')
                ->url(route('rental.export.csv'))
                ->openUrlInNewTab(),

            Tables\Actions\Action::make('export_pdf')
                ->label('Export PDF')
                ->color('danger')
                ->url(route('rental.export.pdf'))
                ->openUrlInNewTab(),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),

            // Tombol konfirmasi booking
            // Tombol Konfirmasi — tampil saat pending ATAU cancelled
Tables\Actions\Action::make('konfirmasi')
    ->label('Konfirmasi')
    ->color('success')
    ->icon('heroicon-o-check')
    ->requiresConfirmation()
    ->visible(fn ($record) => in_array($record->status_booking, ['pending', 'cancelled']))
    ->action(function ($record) {
        $record->update(['status_booking' => 'confirmed']);
        $record->car->update(['status' => 'rented']);
        \Filament\Notifications\Notification::make()
            ->title('Booking dikonfirmasi!')
            ->success()
            ->send();
    }),

            // Tombol batalkan
            Tables\Actions\Action::make('batalkan')
                ->label('Batalkan')
                ->color('danger')
                ->icon('heroicon-o-x')
                ->requiresConfirmation()
                ->modalHeading('Batalkan Booking?')
                ->visible(fn ($record) => in_array($record->status_booking, ['pending', 'confirmed']))
                ->action(function ($record) {
                    $record->update(['status_booking' => 'cancelled']);
                    $record->car->update(['status' => 'available']);
                    \Filament\Notifications\Notification::make()
                        ->title('Booking dibatalkan!')
                        ->danger()
                        ->send();
                }),

            // Tombol kembalikan mobil
            Tables\Actions\Action::make('kembalikan')
                ->label('Kembalikan')
                ->color('warning')
                ->icon('heroicon-o-refresh')
                ->requiresConfirmation()
                ->modalHeading('Kembalikan Mobil?')
                ->visible(fn ($record) => $record->car?->status === 'rented' && $record->status_booking === 'confirmed')
                ->action(function ($record) {
                    $record->car->update(['status' => 'available']);
                    $record->update(['status_booking' => 'completed']);
                    \Filament\Notifications\Notification::make()
                        ->title('Mobil berhasil dikembalikan!')
                        ->success()
                        ->send();
                }),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
}
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRentals::route('/'),
            'create' => Pages\CreateRental::route('/create'),
            'edit' => Pages\EditRental::route('/{record}/edit'),
        ];
    }    
}
