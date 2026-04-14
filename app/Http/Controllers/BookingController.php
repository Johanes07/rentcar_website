<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Rental;
use App\Models\Customer;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'car_id'         => 'required|exists:cars,id',
            'nama_penyewa'   => 'required|string|max:255',
            'no_hp'          => 'required|string|max:20',
            'no_ktp'         => 'required|string|max:20',
            'alamat'         => 'nullable|string',
            'foto_ktp'       => 'required|image|max:2048',
            'tanggal_sewa'   => 'required|date',
            'tanggal_kembali'=> 'required|date|after_or_equal:tanggal_sewa',
            'catatan'        => 'nullable|string',
        ]);

        $car = Car::findOrFail($request->car_id);

        if ($car->status !== 'available') {
            return back()->with('error', 'Mobil sudah tidak tersedia.');
        }

        // Hitung total harga
        $start = \Carbon\Carbon::parse($request->tanggal_sewa);
        $end   = \Carbon\Carbon::parse($request->tanggal_kembali);
        $days  = max(1, $start->diffInDays($end) + 1);
        $total = $days * $car->harga_sewa;

        // Upload foto KTP
        $fotoKtp = $request->file('foto_ktp')->store('ktp', 'public');

        // Cari atau buat customer berdasarkan no_hp
        $customer = Customer::firstOrCreate(
            ['no_hp' => $request->no_hp],
            [
                'nama'   => $request->nama_penyewa,
                'alamat' => $request->alamat ?? '-',
            ]
        );

        // Simpan booking
        $rental = Rental::create([
            'car_id'          => $car->id,
            'customer_id'     => $customer->id,
            'nama_penyewa'    => $request->nama_penyewa,
            'no_hp'           => $request->no_hp,
            'no_ktp'          => $request->no_ktp,
            'alamat'          => $request->alamat,
            'foto_ktp'        => $fotoKtp,
            'tanggal_sewa'    => $request->tanggal_sewa,
            'tanggal_kembali' => $request->tanggal_kembali,
            'total_harga'     => $total,
            'status_booking'  => 'pending',
            'catatan'         => $request->catatan,
        ]);

        // Ambil info rekening aktif
        $payment = PaymentSetting::where('is_active', true)->first();

        return redirect()->route('booking.confirm', $rental->id)
                         ->with('payment', $payment);
    }

    public function confirm($id)
    {
        $rental  = Rental::with('car')->findOrFail($id);
        $payment = PaymentSetting::where('is_active', true)->first();

        return view('frontend.booking-confirm', compact('rental', 'payment'));
    }

    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti_transfer' => 'required|image|max:2048',
        ]);

        $rental = Rental::findOrFail($id);
        $path   = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        $rental->update(['bukti_transfer' => $path]);

        return back()->with('success', 'Bukti transfer berhasil dikirim! Kami akan segera konfirmasi.');
    }
    
        public function create(Car $car)
    {
        if ($car->status !== 'available') {
            return redirect('/')->with('error', 'Mobil tidak tersedia.');
        }
        return view('frontend.booking', compact('car'));
    }
}