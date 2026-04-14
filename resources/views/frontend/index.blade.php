<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sabila Rental — Rental Mobil Premium</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;1,9..40,300&display=swap"
        rel="stylesheet">
     @vite(['resources/css/frontend.css', 'resources/js/frontend.js'])
</head>

<body>

    <nav>
        <div class="nav-logo">Sabila Rent <span>Car</span></div>
       <div class="nav-links">
        <a href="#armada">Armada</a>
        <a href="{{ route('cara-sewa') }}">Cara Sewa</a>
        <a href="#">Tentang Kami</a>
        <a href="#" class="nav-cta">Hubungi Kami</a>
       </div>
    </nav>

    <section class="hero">
        <div>
            <div class="hero-tag">Tersedia hari ini</div>
            <h1>Sewa Mobil<br><em>Kapan saja,</em><br>Di mana saja.</h1>
            <p>Armada premium terpilih, harga transparan, proses mudah. Perjalananmu dimulai dari sini.</p>
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-num">{{ $cars->count() }}</div>
                    <div class="stat-label">Unit tersedia</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">24/7</div>
                    <div class="stat-label">Layanan aktif</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">100%</div>
                    <div class="stat-label">Kepuasan sewa</div>
                </div>
            </div>
        </div>
        <div class="hero-visual">
            <svg viewBox="0 0 200 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="10" y="35" width="180" height="45" rx="10" fill="#0d0d0d" />
                <path d="M30 35 L55 10 L145 10 L170 35Z" fill="#0d0d0d" />
                <circle cx="48" cy="80" r="14" fill="#0d0d0d" />
                <circle cx="152" cy="80" r="14" fill="#0d0d0d" />
            </svg>
        </div>
    </section>

    <section class="search-section">
        <form method="GET" action="{{ url()->current() }}">
            <div class="search-bar">
                <span class="search-label">Cari:</span>
                <input type="text" name="search" placeholder="Nama mobil..." value="{{ request('search') }}">
                <select name="merk">
                    <option value="">Semua merk</option>
                    @foreach ($cars->pluck('merk')->unique() as $merk)
                        <option value="{{ $merk }}" {{ request('merk') == $merk ? 'selected' : '' }}>
                            {{ $merk }}</option>
                    @endforeach
                </select>
                <select name="status">
                    <option value="">Semua status</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>Disewa</option>
                </select>
                <button type="submit" class="btn-book" style="white-space:nowrap;">Cari Mobil</button>
            </div>
        </form>
    </section>

    <div class="section-header">
        <h2 class="section-title">Armada Kami</h2>
        <span class="section-count">{{ $cars->count() }} unit ditemukan</span>
    </div>

    <div class="cars-grid">
        @forelse($cars as $car)
            @php
                $gambarArr = is_array($car->gambar) ? $car->gambar : json_decode($car->gambar, true) ?? [];
                $cid = $car->id;
                $imgUrls = array_map(fn($g) => asset('storage/' . $g), $gambarArr);
            @endphp

            <div class="car-card">
                {{-- GALLERY --}}
                <div class="car-gallery" id="gallery-{{ $cid }}" data-car-id="{{ $cid }}"
                    data-images="{{ json_encode($imgUrls) }}">

                    @if (!empty($gambarArr))
                        <div class="slides-track" id="track-{{ $cid }}">
                            @foreach ($gambarArr as $i => $img)
                                <div class="slide" onclick="openLightbox({{ $cid }}, {{ $i }})">
                                    <img src="{{ asset('storage/' . $img) }}"
                                        alt="{{ $car->nama }} foto {{ $i + 1 }}"
                                        loading="{{ $i === 0 ? 'eager' : 'lazy' }}">
                                </div>
                            @endforeach
                        </div>

                        @if (count($gambarArr) > 1)
                            <button class="slide-btn prev"
                                onclick="slideCard(event,{{ $cid }},-1)">&#8249;</button>
                            <button class="slide-btn next"
                                onclick="slideCard(event,{{ $cid }},1)">&#8250;</button>
                            <div class="slide-dots" id="dots-{{ $cid }}">
                                @foreach ($gambarArr as $i => $img)
                                    <div class="dot {{ $i === 0 ? 'active' : '' }}"
                                        onclick="goSlide(event,{{ $cid }},{{ $i }})"></div>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <div class="gallery-no-img">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="3" />
                                <circle cx="8.5" cy="8.5" r="1.5" />
                                <polyline points="21 15 16 10 5 21" />
                            </svg>
                            Belum ada gambar
                        </div>
                    @endif

                    <span
                        class="status-badge {{ $car->status === 'available' ? 'status-available' : 'status-rented' }}">
                        {{ $car->status === 'available' ? 'Tersedia' : 'Disewa' }}
                    </span>
                </div>

                {{-- THUMBNAILS — tampil jika foto lebih dari 1 --}}
                @if (count($gambarArr) > 1)
                    <div class="thumbnails" id="thumbs-{{ $cid }}">
                        @foreach ($gambarArr as $i => $img)
                            <div class="thumb {{ $i === 0 ? 'active' : '' }}"
                                onclick="thumbClick({{ $cid }}, {{ $i }}, this)">
                                <img src="{{ asset('storage/' . $img) }}" alt="thumb {{ $i + 1 }}">
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- INFO --}}
                <div class="car-info">
                    <div class="car-top">
                        <div>
                            <div class="car-name">{{ $car->nama }}</div>
                            <div class="car-merk">{{ $car->merk }}</div>
                        </div>
                    </div>
                    <div class="car-pills">
                        <span class="pill">Plat: {{ $car->plat_nomor }}</span>
                        <span class="pill">/hari</span>
                    </div>
                    <div class="car-bottom">
                        <div class="car-price">
                            Rp {{ number_format($car->harga_sewa, 0, ',', '.') }}
                            <small>/ hari</small>
                        </div>
                        <button class="btn-book" {{ $car->status !== 'available' ? 'disabled' : '' }}
                            onclick="openModal({{ $cid }}, '{{ addslashes($car->nama) }}', {{ $car->harga_sewa }})">
                            {{-- Ganti tombol pesan lama dengan ini --}}
                            @if($car->status === 'available')
                                <a href="{{ route('booking.create', $car->id) }}" class="btn-book">Pesan</a>
                            @else
                                <button class="btn-book" disabled>Tidak tersedia</button>
                            @endif
                            
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div style="grid-column:1/-1; text-align:center; padding:4rem; color:var(--gray);">
                <p style="font-size:1rem;">Tidak ada mobil yang sesuai pencarian.</p>
            </div>
        @endforelse
    </div>

    {{-- LIGHTBOX --}}
    <div class="lb-overlay" id="lb-overlay">
        <button class="lb-close" onclick="closeLightbox()">✕</button>
        <div class="lb-main" id="lb-main">
            <div class="lb-track" id="lb-track"></div>
            <button class="lb-arrow left" id="lb-left" onclick="lbSlide(-1)">&#8249;</button>
            <button class="lb-arrow right" id="lb-right" onclick="lbSlide(1)">&#8250;</button>
        </div>
        <div class="lb-counter" id="lb-counter"></div>
        <div class="lb-thumbs" id="lb-thumbs"></div>
    </div>


    <footer>
        <div class="footer-logo">Sabila Rent <span>Car</span></div>
        <p>&copy; {{ date('Y') }} Sabila Rental. Semua hak dilindungi.</p>
        <p style="color:#555;">Purwakarta, Jawa Barat</p>
    </footer>

 
</body>

</html>
