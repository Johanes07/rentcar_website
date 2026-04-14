<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cara Sewa — Sabila Rental</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap" rel="stylesheet">
    @vite(['resources/css/frontend.css'])
</head>
<body>

<nav>
    <div class="nav-logo">
        <a href="{{ url('/') }}" style="text-decoration:none;color:inherit;">Sabila Rent <span>Car</span></a>
    </div>
    <div class="nav-links">
        <a href="{{ url('/') }}">Armada</a>
        <a href="{{ route('cara-sewa') }}" style="color:var(--black);font-weight:600;">Cara Sewa</a>
        <a href="#">Tentang Kami</a>
        <a href="#" class="nav-cta">Hubungi Kami</a>
    </div>
</nav>

{{-- HERO --}}
<section style="padding:8rem 4rem 4rem;text-align:center;max-width:680px;margin:0 auto;">
    <div class="hero-tag" style="display:inline-flex;margin-bottom:1.25rem;">Mudah & Cepat</div>
    <h1 style="font-family: 'Montserrat', sans-serif;font-size:clamp(2rem,4vw,3rem);font-weight:800;letter-spacing:-0.04em;line-height:1.1;margin-bottom:1rem;">
        Sewa Mobil dalam<br><em style="font-style:italic;color:var(--accent);font-family:'DM Sans',sans-serif;font-weight:300;">4 Langkah Mudah</em>
    </h1>
    <p style="color:var(--gray);font-size:1rem;line-height:1.7;">
        Proses pemesanan kami dirancang sesederhana mungkin — dari pilih mobil sampai kunci di tangan.
    </p>
</section>

{{-- STEPS --}}
<section style="padding:0 4rem 5rem;max-width:900px;margin:0 auto;">

    {{-- Step 1 --}}
    <div class="cara-step">
        <div class="cara-step-number">01</div>
        <div class="cara-step-content">
            <div class="cara-step-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="1" y="3" width="15" height="13" rx="2"/>
                    <path d="M16 8h4l3 5v3h-7V8z"/>
                    <circle cx="5.5" cy="18.5" r="2.5"/>
                    <circle cx="18.5" cy="18.5" r="2.5"/>
                </svg>
            </div>
            <h3>Pilih Mobil</h3>
            <p>Jelajahi armada kami di halaman utama. Filter berdasarkan merk atau ketersediaan. Klik <strong>Pesan</strong> pada mobil yang kamu inginkan.</p>
            <a href="{{ url('/') }}" class="cara-step-cta">Lihat Armada →</a>
        </div>
    </div>

    <div class="cara-step-divider"></div>

    {{-- Step 2 --}}
    <div class="cara-step">
        <div class="cara-step-number">02</div>
        <div class="cara-step-content">
            <div class="cara-step-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                    <line x1="10" y1="9" x2="8" y2="9"/>
                </svg>
            </div>
            <h3>Isi Data Diri</h3>
            <p>Lengkapi form pemesanan dengan data diri kamu — nama, nomor HP, KTP, dan foto KTP. Pilih tanggal mulai dan selesai sewa.</p>
            <div class="cara-step-info">
                <div class="cara-info-item">
                    <span class="cara-info-icon">✓</span>
                    <span>Nama lengkap & nomor HP</span>
                </div>
                <div class="cara-info-item">
                    <span class="cara-info-icon">✓</span>
                    <span>Nomor & foto KTP</span>
                </div>
                <div class="cara-info-item">
                    <span class="cara-info-icon">✓</span>
                    <span>Tanggal sewa & kembali</span>
                </div>
            </div>
        </div>
    </div>

    <div class="cara-step-divider"></div>

    {{-- Step 3 --}}
    <div class="cara-step">
        <div class="cara-step-number">03</div>
        <div class="cara-step-content">
            <div class="cara-step-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="1" y="4" width="22" height="16" rx="2"/>
                    <line x1="1" y1="10" x2="23" y2="10"/>
                </svg>
            </div>
            <h3>Lakukan Pembayaran</h3>
            <p>Setelah form terisi, kamu akan mendapat ringkasan booking beserta nomor rekening untuk transfer. Transfer sesuai nominal yang tertera.</p>
            <div class="cara-step-warning">
                ⚠️ Transfer tepat sesuai nominal agar verifikasi lebih cepat.
            </div>
        </div>
    </div>

    <div class="cara-step-divider"></div>

    {{-- Step 4 --}}
    <div class="cara-step">
        <div class="cara-step-number">04</div>
        <div class="cara-step-content">
            <div class="cara-step-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="16 16 12 12 8 16"/>
                    <line x1="12" y1="12" x2="12" y2="21"/>
                    <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
                </svg>
            </div>
            <h3>Upload Bukti Transfer</h3>
            <p>Upload foto bukti transfer di halaman konfirmasi. Tim kami akan memverifikasi dan menghubungi kamu via WhatsApp dalam waktu singkat.</p>
            <div class="cara-step-info">
                <div class="cara-info-item">
                    <span class="cara-info-icon">✓</span>
                    <span>Konfirmasi via WhatsApp</span>
                </div>
                <div class="cara-info-item">
                    <span class="cara-info-icon">✓</span>
                    <span>Struk booking dikirim ke kamu</span>
                </div>
            </div>
        </div>
    </div>

</section>

{{-- FAQ --}}
<section style="padding:0 4rem 5rem;max-width:720px;margin:0 auto;">
    <h2 style="font-family:'Syne',sans-serif;font-size:1.5rem;font-weight:700;letter-spacing:-0.03em;margin-bottom:2rem;text-align:center;">
        Pertanyaan Umum
    </h2>

    <div class="faq-list">
        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                <span>Apakah bisa sewa dengan supir?</span>
                <span class="faq-arrow">↓</span>
            </button>
            <div class="faq-answer">
                Bisa! Tambahkan catatan "perlu supir" saat mengisi form pemesanan. Tim kami akan menghubungi kamu untuk info biaya tambahan.
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                <span>Berapa lama proses verifikasi pembayaran?</span>
                <span class="faq-arrow">↓</span>
            </button>
            <div class="faq-answer">
                Verifikasi biasanya dilakukan dalam 1–2 jam di hari kerja. Setelah terverifikasi, kami akan konfirmasi via WhatsApp.
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                <span>Apakah bisa antar jemput?</span>
                <span class="faq-arrow">↓</span>
            </button>
            <div class="faq-answer">
                Bisa, tersedia layanan antar jemput di wilayah Purwakarta dan sekitarnya. Cantumkan alamat di kolom catatan saat pemesanan.
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                <span>Bagaimana jika ingin membatalkan pemesanan?</span>
                <span class="faq-arrow">↓</span>
            </button>
            <div class="faq-answer">
                Hubungi kami via WhatsApp sebelum tanggal sewa. Pembatalan yang dilakukan lebih dari 24 jam sebelum tanggal sewa tidak dikenakan biaya.
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question" onclick="toggleFaq(this)">
                <span>Dokumen apa saja yang diperlukan?</span>
                <span class="faq-arrow">↓</span>
            </button>
            <div class="faq-answer">
                Cukup KTP yang masih berlaku. Foto KTP diupload saat pengisian form pemesanan online.
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section style="padding:0 4rem 6rem;text-align:center;">
    <div style="background:var(--black);border-radius:24px;padding:3rem 2rem;max-width:600px;margin:0 auto;">
        <h2 style="font-family:'Syne',sans-serif;font-size:1.75rem;font-weight:800;color:var(--white);letter-spacing:-0.03em;margin-bottom:0.75rem;">
            Siap untuk pesan?
        </h2>
        <p style="color:#888;font-size:0.95rem;margin-bottom:1.75rem;">
            Armada tersedia sekarang. Booking hanya butuh beberapa menit.
        </p>
        <a href="{{ url('/') }}" 
           style="display:inline-block;background:var(--accent);color:var(--black);padding:12px 32px;border-radius:50px;font-weight:600;font-size:0.95rem;text-decoration:none;transition:opacity 0.2s;">
            Lihat Armada Sekarang →
        </a>
    </div>
</section>

<footer>
    <div class="footer-logo">Sabila Rent <span>Car</span></div>
    <p>&copy; {{ date('Y') }} Sabila Rental. Semua hak dilindungi.</p>
    <p style="color:#555;">Purwakarta, Jawa Barat</p>
</footer>

<script>
function toggleFaq(btn) {
    const answer = btn.nextElementSibling;
    const arrow = btn.querySelector('.faq-arrow');
    const isOpen = answer.style.maxHeight;

    // Tutup semua
    document.querySelectorAll('.faq-answer').forEach(a => a.style.maxHeight = '');
    document.querySelectorAll('.faq-arrow').forEach(a => {
        a.style.transform = '';
        a.style.color = '';
    });

    // Buka yang diklik (kalau belum terbuka)
    if (!isOpen) {
        answer.style.maxHeight = answer.scrollHeight + 'px';
        arrow.style.transform = 'rotate(180deg)';
        arrow.style.color = 'var(--accent)';
    }
}
</script>

</body>
</html>