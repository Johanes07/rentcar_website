<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Mobil — Sabila Rental</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">
    @vite(['resources/css/frontend.css'])
</head>
<body>
<nav>
    <div class="nav-logo">
        <a href="{{ url('/') }}" style="text-decoration:none;color:inherit;">Sabila Rent <span>Car</span></a>
    </div>
    <div class="nav-links">
        <a href="{{ url('/') }}">← Kembali ke Armada</a>
    </div>
</nav>

<div class="booking-wrapper">

    {{-- CAR SUMMARY --}}
    <div class="booking-car-summary">
        @php $gambarArr = is_array($car->gambar) ? $car->gambar : json_decode($car->gambar, true) ?? []; @endphp
        @if(!empty($gambarArr))
            <img src="{{ asset('storage/'.$gambarArr[0]) }}" alt="{{ $car->nama }}">
        @endif
        <div>
            <div class="booking-car-name">{{ $car->nama }}</div>
            <div class="booking-car-meta">{{ $car->merk }} · Plat {{ $car->plat_nomor }}</div>
            <div class="booking-car-price">
                Rp {{ number_format($car->harga_sewa, 0, ',', '.') }}
                <small style="font-size:0.75rem;font-weight:400;color:var(--gray)">/ hari</small>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div style="background:#fee2e2;color:#991b1b;padding:1rem;border-radius:12px;margin-bottom:1.5rem;font-size:0.875rem;">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('booking.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="car_id" value="{{ $car->id }}">

        {{-- DATA DIRI --}}
            <div class="booking-card">
                <h3>
                    <span class="card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </span>
                    Data Diri Penyewa
                </h3>
                <div class="form-row">
                    <div class="form-group">
                        <label>NAMA LENGKAP *</label>
                        <input type="text" name="nama_penyewa" value="{{ old('nama_penyewa') }}" placeholder="Budi Santoso" required>
                        @error('nama_penyewa')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>NO. HP / WhatsApp *</label>
                        <input type="tel" name="no_hp" value="{{ old('no_hp') }}" placeholder="08xx-xxxx-xxxx" required>
                        @error('no_hp')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>NO. KTP *</label>
                        <input type="text" name="no_ktp" value="{{ old('no_ktp') }}" placeholder="3201xxxxxxxxxxxxxx" maxlength="16" required>
                        @error('no_ktp')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>FOTO KTP *</label>
                        <div class="file-input-wrapper">
                            <input type="file" name="foto_ktp" accept="image/*" required>
                        </div>
                        @error('foto_ktp')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <label>ALAMAT LENGKAP</label>
                    <input type="text" name="alamat" value="{{ old('alamat') }}" placeholder="Jl. Contoh No. 1, Purwakarta">
                </div>
            </div>

            {{-- TANGGAL --}}
            <div class="booking-card">
                <h3>
                    <span class="card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                    </span>
                    Tanggal Sewa
                </h3>
                <div class="form-row">
                    <div class="form-group">
                        <label>TANGGAL MULAI *</label>
                        <input type="date" name="tanggal_sewa" id="tanggal_sewa" value="{{ old('tanggal_sewa', date('Y-m-d')) }}" required>
                        @error('tanggal_sewa')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label>TANGGAL SELESAI *</label>
                        <input type="date" name="tanggal_kembali" id="tanggal_kembali" value="{{ old('tanggal_kembali', date('Y-m-d')) }}" required>
                        @error('tanggal_kembali')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="booking-summary-box">
                    <div class="booking-summary-row">
                        <span>Harga per hari</span>
                        <span>Rp {{ number_format($car->harga_sewa, 0, ',', '.') }}</span>
                    </div>
                    <div class="booking-summary-row">
                        <span>Durasi</span>
                        <span id="durasi-label">1 hari</span>
                    </div>
                    <div class="booking-summary-row total">
                        <span>Total Estimasi</span>
                        <span id="total-label">Rp {{ number_format($car->harga_sewa, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- CATATAN --}}
            <div class="booking-card">
                <h3>
                    <span class="card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                    </span>
                    Catatan Tambahan
                </h3>
                <div class="form-group" style="margin:0;">
                    <textarea name="catatan" rows="3" class="form-textarea"
                        placeholder="Contoh: perlu supir, antar jemput, dll...">{{ old('catatan') }}</textarea>
                </div>
            </div>

        <button type="submit" class="btn-confirm" style="width:100%;padding:14px;font-size:1rem;">
            Lanjut ke Pembayaran →
        </button>
    </form>
</div>

<footer>
    <div class="footer-logo">Sabila Rent <span>Car</span></div>
    <p>&copy; {{ date('Y') }} Sabila Rental. Semua hak dilindungi.</p>
    <p style="color:#555;">Purwakarta, Jawa Barat</p>
</footer>

<script>
const harga = {{ $car->harga_sewa }};
function hitungTotal() {
    const s = new Date(document.getElementById('tanggal_sewa').value);
    const e = new Date(document.getElementById('tanggal_kembali').value);
    if (isNaN(s) || isNaN(e)) return;
    const days = Math.max(1, Math.round((e - s) / 86400000) + 1);
    document.getElementById('durasi-label').textContent = days + ' hari';
    document.getElementById('total-label').textContent = 'Rp ' + (harga * days).toLocaleString('id-ID');
}
document.getElementById('tanggal_sewa').addEventListener('change', hitungTotal);
document.getElementById('tanggal_kembali').addEventListener('change', hitungTotal);
</script>
</body>
</html>