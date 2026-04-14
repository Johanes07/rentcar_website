
// Tambahkan ini di bagian PALING ATAS frontend.js

window.openLightbox = openLightbox;
window.closeLightbox = closeLightbox;
window.lbSlide = lbSlide;
window.lbGoTo = lbGoTo;
window.slideCard = slideCard;
window.goSlide = goSlide;
window.thumbClick = thumbClick;



    /* ═══════════════════════
            CARD GALLERY
        ═══════════════════════ */
    const cardState = {};

    function getState(id) {
        if (!cardState[id]) {
            const el = document.getElementById('gallery-' + id);
            const imgs = JSON.parse(el.dataset.images || '[]');
            cardState[id] = {
                index: 0,
                total: imgs.length,
                images: imgs
            };
        }
        return cardState[id];
    }

    function setCardIndex(id, idx) {
        const s = getState(id);
        s.index = (idx + s.total) % s.total;

        const track = document.getElementById('track-' + id);
        if (track) track.style.transform = `translateX(-${s.index * 100}%)`;

        const dotsEl = document.getElementById('dots-' + id);
        if (dotsEl) dotsEl.querySelectorAll('.dot').forEach((d, i) => d.classList.toggle('active', i === s.index));

        const thumbsEl = document.getElementById('thumbs-' + id);
        if (thumbsEl) thumbsEl.querySelectorAll('.thumb').forEach((t, i) => t.classList.toggle('active', i === s
        .index));
    }

    function slideCard(e, id, dir) {
        e.stopPropagation();
        const s = getState(id);
        setCardIndex(id, s.index + dir);
    }

    function goSlide(e, id, idx) {
        e.stopPropagation();
        setCardIndex(id, idx);
    }

    function thumbClick(id, idx) {
        setCardIndex(id, idx);
    }

    /* touch swipe on cards */
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.car-gallery').forEach(el => {
            let sx = 0,
                moved = false;
            const id = el.dataset.carId;
            if (!id) return;
            el.addEventListener('touchstart', e => {
                sx = e.touches[0].clientX;
                moved = false;
            }, {
                passive: true
            });
            el.addEventListener('touchmove', e => {
                if (Math.abs(e.touches[0].clientX - sx) > 8) moved = true;
            }, {
                passive: true
            });
            el.addEventListener('touchend', e => {
                if (!moved) return;
                const diff = sx - e.changedTouches[0].clientX;
                if (Math.abs(diff) > 40) {
                    const s = getState(id);
                    setCardIndex(id, s.index + (diff > 0 ? 1 : -1));
                }
            });
        });
    });

    /* ═══════════════════════
        LIGHTBOX
    ═══════════════════════ */
    let lb = {
        images: [],
        index: 0,
        zoomed: false
    };

    function openLightbox(cardId, startIdx) {
        const s = getState(cardId);
        lb.images = s.images;
        lb.index = startIdx ?? s.index;
        lb.zoomed = false;

        const track = document.getElementById('lb-track');
        const thumbsEl = document.getElementById('lb-thumbs');

        track.innerHTML = lb.images.map((src, i) => `
    <div class="lb-slide"><img src="${src}" alt="foto ${i+1}" draggable="false"></div>
`).join('');
        thumbsEl.innerHTML = lb.images.map((src, i) => `
    <div class="lb-thumb ${i===lb.index?'active':''}" onclick="lbGoTo(${i})">
        <img src="${src}" alt="t${i+1}">
    </div>
`).join('');

        track.querySelectorAll('.lb-slide img').forEach(img => {
            img.onclick = () => {
                lb.zoomed = !lb.zoomed;
                img.classList.toggle('zoomed', lb.zoomed);
            };
        });

        track.style.transition = 'none';
        track.style.transform = `translateX(-${lb.index * 100}%)`;
        requestAnimationFrame(() => {
            track.style.transition = '';
        });

        refreshLbUI();
        document.getElementById('lb-overlay').classList.add('open');
        document.body.style.overflow = 'hidden';

        if (lb.images.length > 1 && !sessionStorage.lbHint) {
            const hint = document.createElement('div');
            hint.className = 'lb-hint';
            hint.textContent = '← geser atau klik panah · klik foto untuk zoom →';
            document.getElementById('lb-overlay').appendChild(hint);
            setTimeout(() => hint.remove(), 3800);
            sessionStorage.lbHint = '1';
        }
    }

    function refreshLbUI() {
        const show = lb.images.length > 1;
        document.getElementById('lb-left').style.display = show ? 'flex' : 'none';
        document.getElementById('lb-right').style.display = show ? 'flex' : 'none';
        document.getElementById('lb-counter').textContent = show ? `${lb.index+1} / ${lb.images.length}` : '';
        document.getElementById('lb-track').style.transform = `translateX(-${lb.index * 100}%)`;
        document.querySelectorAll('#lb-thumbs .lb-thumb').forEach((t, i) => t.classList.toggle('active', i === lb
            .index));
        const active = document.querySelector('#lb-thumbs .lb-thumb.active');
        if (active) active.scrollIntoView({
            behavior: 'smooth',
            inline: 'center',
            block: 'nearest'
        });
    }

    function lbSlide(dir) {
        if (lb.zoomed) return;
        lb.index = (lb.index + dir + lb.images.length) % lb.images.length;
        document.querySelectorAll('.lb-slide img').forEach(i => i.classList.remove('zoomed'));
        lb.zoomed = false;
        refreshLbUI();
    }

    function lbGoTo(i) {
        lb.index = i;
        refreshLbUI();
    }

    function closeLightbox() {
        document.getElementById('lb-overlay').classList.remove('open');
        document.body.style.overflow = '';
        lb.zoomed = false;
    }

    (function() {
        let sx = 0;
        document.getElementById('lb-main').addEventListener('touchstart', e => {
            sx = e.touches[0].clientX;
        }, {
            passive: true
        });
        document.getElementById('lb-main').addEventListener('touchend', e => {
            const diff = sx - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 50) lbSlide(diff > 0 ? 1 : -1);
        });
    })();

    document.addEventListener('keydown', e => {
        if (!document.getElementById('lb-overlay').classList.contains('open')) return;
        if (e.key === 'ArrowRight') lbSlide(1);
        if (e.key === 'ArrowLeft') lbSlide(-1);
        if (e.key === 'Escape') closeLightbox();
    });
    document.getElementById('lb-overlay').addEventListener('click', e => {
        if (e.target === document.getElementById('lb-overlay')) closeLightbox();
    });

    /* ═══════════════════════
        BOOKING MODAL
    ═══════════════════════ */
    let selectedCar = null;

    function formatRp(n) {
        return 'Rp ' + parseInt(n).toLocaleString('id-ID');
    }

    function openModal(id, nama, harga) {
        selectedCar = {
            id,
            nama,
            harga
        };
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('modal-box').innerHTML = `
    <div class="modal-head">
        <h2>Pesan — ${nama}</h2>
        <button class="modal-close" onclick="closeModal()">✕</button>
    </div>
    <div class="modal-body">
        <div class="form-row">
            <div class="form-group"><label>NAMA LENGKAP *</label><input type="text" id="f-nama" placeholder="Budi Santoso"></div>
            <div class="form-group"><label>NO. HP *</label><input type="tel" id="f-hp" placeholder="08xx-xxxx-xxxx"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>TANGGAL MULAI *</label><input type="date" id="f-start" value="${today}" onchange="calcTotal()"></div>
            <div class="form-group"><label>TANGGAL SELESAI *</label><input type="date" id="f-end" value="${today}" onchange="calcTotal()"></div>
        </div>
        <div class="form-group"><label>ALAMAT PENGANTARAN</label><input type="text" id="f-addr" placeholder="Jl. Contoh No. 1, Tangerang"></div>
        <div class="booking-summary">
            <div class="sum-row"><span>Mobil</span><span>${nama}</span></div>
            <div class="sum-row"><span>Harga/hari</span><span>${formatRp(harga)}</span></div>
            <div class="sum-row"><span>Durasi</span><span id="s-durasi">1 hari</span></div>
            <div class="sum-row total"><span>Total</span><span id="s-total">${formatRp(harga)}</span></div>
        </div>
    </div>
    <div class="modal-foot">
        <button class="btn-cancel" onclick="closeModal()">Batal</button>
        <button class="btn-confirm" onclick="confirmBooking()">Konfirmasi Pemesanan →</button>
    </div>`;
        document.getElementById('modal-overlay').classList.add('open');
    }

    function calcTotal() {
        const s = new Date(document.getElementById('f-start').value);
        const e = new Date(document.getElementById('f-end').value);
        const days = Math.max(1, Math.round((e - s) / 86400000) + 1);
        document.getElementById('s-durasi').textContent = days + ' hari';
        document.getElementById('s-total').textContent = formatRp(selectedCar.harga * days);
    }

    function confirmBooking() {
        const nama = document.getElementById('f-nama').value.trim();
        const hp = document.getElementById('f-hp').value.trim();
        if (!nama || !hp) {
            alert('Nama dan No. HP wajib diisi.');
            return;
        }
        document.getElementById('modal-box').innerHTML = `
    <div class="success-box">
        <div class="success-icon">✓</div>
        <h3>Pemesanan Diterima!</h3>
        <p>Terima kasih <strong>${nama}</strong>!<br>Kami akan menghubungi <strong>${hp}</strong> segera.</p>
        <button class="btn-confirm" style="width:100%;max-width:200px;" onclick="closeModal()">Selesai</button>
    </div>`;
    }

    function closeModal() {
        document.getElementById('modal-overlay').classList.remove('open');
        selectedCar = null;
    }

    function handleOverlayClick(e) {
        if (e.target === document.getElementById('modal-overlay')) closeModal();
    }

    