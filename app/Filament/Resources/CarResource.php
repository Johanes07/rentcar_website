<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarResource\Pages;
use App\Filament\Resources\CarResource\RelationManagers;
use App\Models\Car;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;

class CarResource extends Resource
{
    protected static ?string $model = Car::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('nama')
                ->required()
                ->label('Nama Mobil'),

            TextInput::make('merk')
                ->required()
                ->label('Merk'),

            TextInput::make('plat_nomor')
                ->required()
                ->label('Plat Nomor'),

            TextInput::make('harga_sewa')
                ->numeric()
                ->mask(fn (TextInput\Mask $mask) => $mask
                    ->numeric()
                    ->thousandsSeparator('.')
                ),

            FileUpload::make('gambar')
                ->label('Gambar Mobil')
                ->helperText('Klik X pada foto tertentu untuk hapus, atau drag & drop untuk tambah foto baru.')
                ->image()
                ->multiple()
                ->disk('public')
                ->directory('cars')
                ->visibility('public')
                ->imagePreviewHeight('150')
                ->panelLayout('grid')
                ->getUploadedFileUrlUsing(fn ($file) => asset('storage/' . $file)),

            Select::make('status')
                ->options([
                    'available' => 'Available',
                    'rented'    => 'Rented',
                ])
                ->default('available')
                ->required(),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')->searchable(),
                TextColumn::make('merk'),
                TextColumn::make('plat_nomor'),
                TextColumn::make('harga_sewa')->money('IDR', true),
                TextColumn::make('status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListCars::route('/'),
            'create' => Pages\CreateCar::route('/create'),
            'edit' => Pages\EditCar::route('/{record}/edit'),
        ];
    }    
}
