<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rental;

class CheckRentalStatus extends Command
{
    protected $signature = 'rentals:check-expired';

    protected $description = 'Mengecek rental yang sudah melewati tanggal kembali dan membebaskan mobil';

    public function handle()
    {
        $expiredRentals = Rental::where('tanggal_kembali', '<', now())
            ->whereHas('car', fn($q) => $q->where('status', 'rented'))
            ->get();

        foreach ($expiredRentals as $rental) {
            $rental->car->update(['status' => 'available']);
            $this->info("Mobil {$rental->car->nama} sudah dibebaskan (Rental ID: {$rental->id})");
        }

        $this->info("Selesai. Total: {$expiredRentals->count()} rental diproses.");

        return Command::SUCCESS;
    }
}