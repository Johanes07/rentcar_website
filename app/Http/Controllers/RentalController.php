<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Barryvdh\DomPDF\Facade\Pdf;

class RentalController extends Controller
{
    public function exportPdf()
    {
        $rentals = Rental::with(['car', 'customer'])->get();

        $pdf = Pdf::loadView('exports.rental-pdf', compact('rentals'));

        return $pdf->download('rentals.pdf');
    }

    public function exportCsv()
    {
        return (new \App\Exports\RentalExport)->download();
    }
}