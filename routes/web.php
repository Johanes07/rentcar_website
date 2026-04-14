<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\BookingController;

Route::get('/', [FrontendController::class, 'index']);

Route::get('/rental/export/pdf', [App\Http\Controllers\RentalController::class, 'exportPdf'])
    ->name('rental.export.pdf')
    ->middleware('auth');

Route::get('/rental/export/csv', [App\Http\Controllers\RentalController::class, 'exportCsv'])
    ->name('rental.export.csv')
    ->middleware('auth');

// Fix Filepond "waiting for size" di Filament v2
Route::match(['HEAD', 'GET'], '/storage-check/{path}', function ($path) {
    $fullPath = storage_path('app/public/' . $path);
    if (!file_exists($fullPath)) {
        abort(404);
    }
    return response('', 200, [
        'Content-Type'   => mime_content_type($fullPath),
        'Content-Length' => filesize($fullPath),
    ]);
})->where('path', '.*');

// endpoint untuk booking
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/confirm/{id}', [BookingController::class, 'confirm'])->name('booking.confirm');
Route::post('/booking/upload-bukti/{id}', [BookingController::class, 'uploadBukti'])->name('booking.upload-bukti');

// booking creete
Route::get('/booking/{car}', [BookingController::class, 'create'])->name('booking.create');

// CARA SEWA
Route::get('/cara-sewa', function () {return view('frontend.cara-sewa');})->name('cara-sewa');