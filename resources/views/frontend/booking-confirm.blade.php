<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Booking — Sabila Rental</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">
    @vite(['resources/css/frontend.css'])
</head>
<body>

<nav>
    <div class="nav-logo"><a href="{{ url('/') }}" style="text-decoration:none;color:inherit;">Sabila Rent <span>Car</span></a></div>
</nav>

<div style="padding:7rem 4rem 4rem;max-width:640px;margin:0 auto;">

    {{-- SUCCESS HEADER --}}
    <div style="text-align:center;margin-bottom:2rem;">
        <div style="width:64px;height:64px;background:#d1fae5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:1.75rem;">✓</div>
        <h1 style="font-family:'Syne',sans-serif;font-size:1.75rem;font-weight:800;letter-spacing:-0.03em;">Booking Diterima!</h1>
        <p style="color:var(--gray);margin-top:0.5rem;">Selesaikan pembayaran untuk konfirmasi booking kamu.</p>
    </div>

    {{-- RINGKASAN BOOKING --}}
    <div style="background:#fff;border:1px solid var(--light-gray);border-radius:20px;padding:1.75rem;margin-bottom:1.5rem;">
        <h3 style="font-family:'Syne',sans-serif;font-size:1rem;font-weight:700;margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:1px solid var(--cream);">
            📋 Ringkasan Booking
        </h3>
        <div style="display:flex;flex-direction:column;gap:8px;font-size:0.875rem;">
            <div style="display:flex;justify-content:space-between;">
                <span style="color:var(--gray);">Kode Booking</span>
                <span style="font-weight:600;font-family:'Syne',sans-serif;">#{{ str_pad($rental->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <span style="color:var(--gray);">Mobil</span>
                <span style="font-weight:500;">{{ $rental->car->nama }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <span style="color:var(--gray);">Nama Penyewa</span>
                <span>{{ $rental->nama_penyewa }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <span style="color:var(--gray);">No. HP</span>
                <span>{{ $rental->no_hp }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <span style="color:var(--gray);">Tanggal Sewa</span>
                <span>{{ \Carbon\Carbon::parse($rental->tanggal_sewa)->format('d M Y') }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <span style="color:var(--gray);">Tanggal Kembali</span>
                <span>{{ \Carbon\Carbon::parse($rental->tanggal_kembali)->format('d M Y') }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding-top:10px;border-top:1px solid var(--cream);margin-top:4px;">
                <span style="font-weight:700;">Total Pembayaran</span>
                <span style="font-weight:700;font-family:'Syne',sans-serif;color:var(--accent-dark);font-size:1.1rem;">
                    Rp {{ number_format($rental->total_harga, 0, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

    {{-- INFO REKENING --}}
    @if($payment)
    <div style="background:var(--cream);border:1px solid var(--light-gray);border-radius:20px;padding:1.75rem;margin-bottom:1.5rem;">
        <h3 style="font-family:'Syne',sans-serif;font-size:1rem;font-weight:700;margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:1px solid var(--light-gray);">
            🏦 Transfer Pembayaran
        </h3>
        <div style="display:flex;flex-direction:column;gap:8px;font-size:0.875rem;">
            <div style="display:flex;justify-content:space-between;">
                <span style="color:var(--gray);">Bank</span>
                <span style="font-weight:600;">{{ $payment->bank_name }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;">
                <span style="color:var(--gray);">No. Rekening</span>
                <div style="display:flex;align-items:center;gap:8px;">
                    <span style="font-weight:700;font-family:'Syne',sans-serif;font-size:1.1rem;" id="norek">{{ $payment->account_number }}</span>
                    <button onclick="copyNorek()" style="background:var(--black);color:#fff;border:none;border-radius:6px;padding:4px 10px;font-size:0.75rem;cursor:pointer;" id="copy-btn">Salin</button>
                </div>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <span style="color:var(--gray);">Atas Nama</span>
                <span style="font-weight:500;">{{ $payment->account_name }}</span>
            </div>
        </div>
        <div style="background:#fff3cd;border-radius:10px;padding:10px 14px;margin-top:1rem;font-size:0.8rem;color:#856404;">
            ⚠️ Transfer tepat sesuai nominal. Booking akan dikonfirmasi setelah pembayaran diverifikasi.
        </div>
    </div>
    @else
    <div style="background:#fee2e2;border-radius:16px;padding:1.25rem;margin-bottom:1.5rem;font-size:0.875rem;color:#991b1b;">
        Info rekening belum tersedia. Silakan hubungi admin.
    </div>
    @endif

    {{-- UPLOAD BUKTI --}}
    <div style="background:#fff;border:1px solid var(--light-gray);border-radius:20px;padding:1.75rem;margin-bottom:1.5rem;">
        <h3 style="font-family:'Syne',sans-serif;font-size:1rem;font-weight:700;margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:1px solid var(--cream);">
            📤 Upload Bukti Transfer
        </h3>

        @if(session('success'))
            <div style="background:#d1fae5;color:#065f46;padding:1rem;border-radius:10px;margin-bottom:1rem;font-size:0.875rem;">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if($rental->bukti_transfer)
            <div style="margin-bottom:1rem;font-size:0.875rem;color:var(--gray);">
                ✓ Bukti transfer sudah dikirim. <a href="{{ asset('storage/'.$rental->bukti_transfer) }}" target="_blank" style="color:var(--accent-dark);">Lihat</a>
            </div>
        @endif

        <form method="POST" action="{{ route('booking.upload-bukti', $rental->id) }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>FOTO BUKTI TRANSFER</label>
                <input type="file" name="bukti_transfer" accept="image/*" required style="padding:0.5rem;width:100%;border:1px solid var(--light-gray);border-radius:10px;">
            </div>
            <button type="submit" class="btn-confirm" style="width:100%;padding:12px;margin-top:0.5rem;">
                Kirim Bukti Transfer
            </button>
        </form>
    </div>

    {{-- HUBUNGI WA --}}
    <a href="https://wa.me/62xxxxxxxxxx?text=Halo, saya sudah transfer untuk booking %23{{ str_pad($rental->id, 5, '0', STR_PAD_LEFT) }} atas nama {{ urlencode($rental->nama_penyewa) }}" 
        target="_blank"
        style="display:block;text-align:center;background:#25D366;color:#fff;padding:12px;border-radius:50px;text-decoration:none;font-weight:500;font-size:0.9rem;">
        💬 Konfirmasi via WhatsApp
    </a>

</div>

<footer>
    <div class="footer-logo">Sabila Rent <span>Car</span></div>
    <p>&copy; {{ date('Y') }} Sabila Rental. Semua hak dilindungi.</p>
    <p style="color:#555;">Purwakarta, Jawa Barat</p>
</footer>

<script>
function copyNorek() {
    const norek = document.getElementById('norek').textContent;
    navigator.clipboard.writeText(norek).then(() => {
        const btn = document.getElementById('copy-btn');
        btn.textContent = 'Tersalin!';
        btn.style.background = 'var(--green)';
        setTimeout(() => { btn.textContent = 'Salin'; btn.style.background = 'var(--black)'; }, 2000);
    });
}
</script>

</body>
</html>