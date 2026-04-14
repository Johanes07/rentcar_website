<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rental extends Model
{
    use HasFactory;

        protected $fillable = [
        'car_id',
        'customer_id',
        'nama_penyewa',
        'no_hp',
        'no_ktp',
        'alamat',
        'foto_ktp',
        'bukti_transfer',
        'tanggal_sewa',
        'tanggal_kembali',
        'total_harga',
        'status_booking',
        'catatan',
    ];

    // 🔥 RELASI KE MOBIL
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    // 🔥 RELASI KE CUSTOMER
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    protected static function booted()
    {
       static::created(function ($rental) {
        $rental->car->update(['status' => 'rented']);
        });

        static::deleted(function ($rental) {
            $rental->car->update(['status' => 'available']);
        });
    }
}