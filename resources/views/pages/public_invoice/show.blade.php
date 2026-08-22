<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>Bukti Transfer Dividen – {{ $transfer->code ?? 'TRF-'.$transfer->id }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800&display=swap" rel="stylesheet">

    <!-- html2canvas for High-Res Image Download -->
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --blue:        #1d4ed8;
            --blue-dark:   #1e3a8a;
            --blue-deeper: #172554;
            --blue-bg:     #eff6ff;
            --blue-light:  #bfdbfe;
            --green:       #10b981;
            --amber:       #f59e0b;
            --accent:      #2563eb;
            --text-dark:   #111827;
            --text-mid:    #6b7280;
            --text-light:  #9ca3af;
            --border:      #e5e7eb;
            --bg:          #f1f5f9;
            --white:       #ffffff;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 24px 16px;
        }

        .page-container {
            width: 100%;
            max-width: 430px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* ─────────── INVOICE RECEIPT BOX (Captured as Image) ─────────── */
        .invoice-receipt {
            background: var(--white);
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08), 0 4px 12px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(229, 231, 235, 0.8);
            position: relative;
        }

        /* ─────────── HERO HEADER ─────────── */
        .hero {
            background: linear-gradient(145deg, #2563eb 0%, #1d4ed8 45%, #1e3a8a 100%);
            padding: 28px 24px 60px;
            position: relative;
            overflow: hidden;
            color: #fff;
        }

        /* Decorative circles */
        .hero::before {
            content: '';
            position: absolute;
            width: 260px; height: 260px;
            border-radius: 50%;
            border: 40px solid rgba(255,255,255,.07);
            top: -80px; right: -70px;
            pointer-events: none;
        }
        .hero::after {
            content: '';
            position: absolute;
            width: 160px; height: 160px;
            border-radius: 50%;
            background: rgba(255,255,255,.05);
            bottom: -30px; left: -40px;
            pointer-events: none;
        }

        /* Brand bar */
        .brand-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            position: relative; z-index: 1;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo {
            width: 44px; height: 44px;
            border-radius: 12px;
            background: #ffffff;
            border: 2px solid rgba(255,255,255,.4);
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
            padding: 3px;
        }

        .brand-logo img {
            width: 100%; height: 100%;
            object-fit: contain;
            border-radius: 8px;
        }

        .brand-logo-text {
            font-size: 13px;
            font-weight: 800;
            color: var(--blue);
            letter-spacing: .5px;
        }

        .brand-info-name {
            font-size: 14px;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
        }

        .brand-info-sub {
            font-size: 11px;
            font-weight: 500;
            color: rgba(255,255,255,.7);
            margin-top: 2px;
        }

        .trx-badge {
            text-align: right;
        }

        .trx-badge-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .06em;
            color: rgba(255,255,255,.65);
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .trx-badge-code {
            font-size: 12px;
            font-weight: 700;
            color: #fff;
            background: rgba(255,255,255,.18);
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 6px;
            padding: 3px 8px;
            display: inline-block;
        }

        /* Status section */
        .hero-status {
            position: relative; z-index: 1;
        }

        .hero-status-title {
            font-size: 17px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .hero-status-icon {
            width: 22px; height: 22px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .hero-status-icon.success { background: rgba(255,255,255,.3); }
        .hero-status-icon.pending { background: rgba(255,255,255,.2); }

        .hero-status-date {
            font-size: 12.5px;
            font-weight: 500;
            color: rgba(255,255,255,.75);
        }

        /* ─────────── FLOATING BADGE ─────────── */
        .float-badge-wrap {
            display: flex;
            justify-content: center;
            padding: 0;
            margin-top: -34px;
            margin-bottom: 4px;
            position: relative;
            z-index: 10;
        }

        .float-badge {
            width: 66px; height: 66px;
            border-radius: 50%;
            border: 5px solid #fff;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 6px 20px rgba(0,0,0,.14);
        }

        .float-badge.success { background: var(--green); }
        .float-badge.pending { background: var(--amber); }

        /* ─────────── CARD BODY ─────────── */
        .card-body {
            padding: 8px 24px 28px;
            background: var(--white);
        }

        /* Amount */
        .amount-block {
            text-align: center;
            padding: 16px 0 20px;
            border-bottom: 1.5px solid var(--border);
        }

        .amount-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--text-mid);
            margin-bottom: 6px;
        }

        .amount-number {
            font-size: 38px;
            font-weight: 800;
            color: var(--text-dark);
            letter-spacing: -.02em;
            line-height: 1.1;
        }

        /* Detail rows */
        .rows {
            padding: 6px 0;
            border-bottom: 1.5px solid var(--border);
        }

        .row-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 13px 0;
            gap: 12px;
        }

        .row-item + .row-item {
            border-top: 1px solid #f3f4f6;
        }

        .row-label {
            font-size: 13.5px;
            color: var(--text-mid);
            font-weight: 500;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .row-value {
            font-size: 13.5px;
            font-weight: 700;
            color: var(--text-dark);
            text-align: right;
            word-break: break-word;
        }

        /* Assurance */
        .assurance {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            background: var(--blue-bg);
            border: 1px solid var(--blue-light);
            border-radius: 14px;
            padding: 14px 16px;
            margin-top: 22px;
        }

        .assurance-icon-wrap {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: var(--blue);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .assurance-text {
            font-size: 12px;
            color: var(--blue-deeper);
            font-weight: 500;
            line-height: 1.65;
        }

        /* ─────────── BOTTOM ACTION BUTTONS (Not in Image) ─────────── */
        .actions-card {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn-row {
            display: flex;
            gap: 10px;
        }

        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 18px;
            border-radius: 14px;
            font-family: inherit;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all .15s ease;
            flex: 1;
        }

        .btn:active { transform: scale(.97); opacity: .9; }

        .btn-wa {
            background: #ffffff;
            color: var(--blue-dark);
            border: 1.5px solid var(--blue-light);
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
        }

        .btn-wa:hover {
            background: var(--blue-bg);
        }

        .btn-download {
            background: var(--accent);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-download:hover {
            background: #1d4ed8;
        }

        .btn-download.loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .footer {
            text-align: center;
            font-size: 11.5px;
            color: var(--text-light);
            font-weight: 500;
            padding-bottom: 12px;
        }

        /* ─────────── PRINT ─────────── */
        @media print {
            body { background: #fff; padding: 0; }
            .page-container { max-width: 100%; }
            .no-print { display: none !important; }
            .invoice-receipt { box-shadow: none; border: 1px solid #ccc; }
            .hero { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .float-badge { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>
<body>

@php
    \Carbon\Carbon::setLocale('id');
    $code         = $transfer->code ?? 'TRF-'.$transfer->id;
    $isSuccess    = $transfer->status === 'success' || !empty($transfer->confirmation_date);
    $tglTransfer  = \Carbon\Carbon::parse($transfer->transfer_date)->translatedFormat('d F Y');
    $tglKonfirm   = $transfer->confirmation_date
                        ? \Carbon\Carbon::parse($transfer->confirmation_date)->translatedFormat('d F Y, H:i') . ' WIB'
                        : null;
@endphp

<div class="page-container">

    {{-- ═══════ INVOICE RECEIPT (AREA YANG AKAN DIUNDUH JADI GAMBAR) ═══════ --}}
    <div class="invoice-receipt" id="invoice-receipt">

        {{-- Hero Header --}}
        <div class="hero">
            {{-- Brand bar --}}
            <div class="brand-bar">
                <div class="brand">
                    <div class="brand-logo">
                        @if(file_exists(public_path('img/logo.jpg')))
                            <img src="{{ asset('img/logo.jpg') }}" alt="CIO">
                        @else
                            <span class="brand-logo-text">CIO</span>
                        @endif
                    </div>
                    <div>
                        <div class="brand-info-name">CIO Network Solution</div>
                        <div class="brand-info-sub">Investment Management Portal</div>
                    </div>
                </div>
                <div class="trx-badge">
                    <div class="trx-badge-label">No. Transaksi</div>
                    <div class="trx-badge-code">#{{ $code }}</div>
                </div>
            </div>

            {{-- Status --}}
            <div class="hero-status">
                <div class="hero-status-title">
                    <div class="hero-status-icon {{ $isSuccess ? 'success' : 'pending' }}">
                        @if($isSuccess)
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        @else
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        @endif
                    </div>
                    {{ $isSuccess ? 'Transaksi berhasil diproses' : 'Transaksi sedang diproses' }}
                </div>
                <div class="hero-status-date">{{ $tglTransfer }} WIB</div>
            </div>
        </div>

        {{-- Floating Badge --}}
        <div class="float-badge-wrap">
            <div class="float-badge {{ $isSuccess ? 'success' : 'pending' }}">
                @if($isSuccess)
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                @else
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                @endif
            </div>
        </div>

        {{-- Card Body --}}
        <div class="card-body">

            {{-- Amount --}}
            <div class="amount-block">
                <div class="amount-label">Total Dividen Diterima</div>
                <div class="amount-number">Rp {{ number_format($transfer->amount, 0, ',', '.') }}</div>
            </div>

            {{-- Detail rows --}}
            <div class="rows">
                <div class="row-item">
                    <span class="row-label">Penerima</span>
                    <span class="row-value">{{ $transfer->investor->name ?? '-' }}</span>
                </div>
                <div class="row-item">
                    <span class="row-label">Bank / Metode</span>
                    <span class="row-value">{{ $transfer->payment_method ?? 'Transfer Bank' }}</span>
                </div>
                <div class="row-item">
                    <span class="row-label">Pengirim</span>
                    <span class="row-value">PT CIO Network Solution</span>
                </div>
                <div class="row-item">
                    <span class="row-label">Tanggal Transfer</span>
                    <span class="row-value">{{ $tglTransfer }}</span>
                </div>
                <div class="row-item">
                    <span class="row-label">Keterangan / Periode</span>
                    <span class="row-value">{{ $transfer->notes ?: 'Distribusi Dividen Bagi Hasil' }}</span>
                </div>
                @if($tglKonfirm)
                <div class="row-item">
                    <span class="row-label">Dikonfirmasi</span>
                    <span class="row-value">{{ $tglKonfirm }}</span>
                </div>
                @endif
                <div class="row-item">
                    <span class="row-label">Diproses Oleh</span>
                    <span class="row-value">{{ $transfer->admin->name ?? 'Admin CIO' }}</span>
                </div>
            </div>

            {{-- Assurance --}}
            <div class="assurance">
                <div class="assurance-icon-wrap">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div class="assurance-text">
                    Bukti transfer sah yang diterbitkan otomatis oleh sistem resmi PT CIO Network Solution.
                </div>
            </div>

        </div>{{-- end card-body --}}

    </div>{{-- end invoice-receipt --}}

    {{-- ═══════ ACTION BUTTONS (DI LUAR STRUK, TIDAK IKUT TER-UNDUH) ═══════ --}}
    <div class="actions-card no-print">
        <div class="btn-row">
            <button type="button" onclick="downloadReceiptImage()" id="btn-download" class="btn btn-download">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                <span id="btn-download-text">Unduh Gambar</span>
            </button>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} PT CIO Network Solution &nbsp;·&nbsp; All rights reserved.
        </div>
    </div>

</div>

<script>
function loadHtml2Canvas(callback) {
    if (typeof html2canvas !== 'undefined') {
        callback();
        return;
    }
    const script = document.createElement('script');
    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js';
    script.onload = callback;
    script.onerror = function() {
        alert('Gagal memuat pustaka gambar. Periksa koneksi internet Anda.');
        const btn = document.getElementById('btn-download');
        const btnText = document.getElementById('btn-download-text');
        if (btn) btn.classList.remove('loading');
        if (btnText) btnText.innerText = 'Unduh Gambar';
    };
    document.head.appendChild(script);
}

function downloadReceiptImage() {
    const btn = document.getElementById('btn-download');
    const btnText = document.getElementById('btn-download-text');
    const receipt = document.getElementById('invoice-receipt');

    if (!receipt) return;

    btn.classList.add('loading');
    const originalText = btnText.innerText;
    btnText.innerText = 'Menyimpan...';

    loadHtml2Canvas(function() {
        html2canvas(receipt, {
            scale: 3,
            useCORS: true,
            allowTaint: true,
            backgroundColor: '#ffffff',
            logging: false,
            imageTimeout: 5000,
        }).then(function(canvas) {
            const image = canvas.toDataURL('image/png');
            const link = document.createElement('a');
            link.download = 'Bukti-Transfer-{{ $code }}.png';
            link.href = image;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            btn.classList.remove('loading');
            btnText.innerText = originalText;
        }).catch(function(err) {
            console.error('Error generating image:', err);
            alert('Gagal mengunduh gambar. Silakan coba kembali.');
            btn.classList.remove('loading');
            btnText.innerText = originalText;
        });
    });
}
</script>

</body>
</html>
