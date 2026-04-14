<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'merk',
        'plat_nomor',
        'harga_sewa',
        'status',
        'gambar',
    ];

    protected $casts = [
        'gambar' => 'array', // ← INI yang bikin foreach bisa jalan
    ];

    public function rentals()
    {
        return $this->hasMany(Rental::class);
    }

}

