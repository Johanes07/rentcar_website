<?php

namespace App\Exports;

use App\Models\Rental;

class RentalExport
{
    public function download()
    {
        $rentals = Rental::with(['car', 'customer'])->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="rentals.csv"',
        ];

        $callback = function () use ($rentals) {
            $file = fopen('php://output', 'w');

            // Header kolom
            fputcsv($file, ['No', 'Mobil', 'Customer', 'Tanggal Sewa', 'Tanggal Kembali', 'Total Harga']);

            foreach ($rentals as $i => $rental) {
                fputcsv($file, [
                    $i + 1,
                    $rental->car->nama ?? '-',
                    $rental->customer->nama ?? '-',
                    $rental->tanggal_sewa,
                    $rental->tanggal_kembali,
                    'Rp ' . number_format($rental->total_harga, 0, ',', '.'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}