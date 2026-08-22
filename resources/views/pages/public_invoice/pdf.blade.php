<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Dividen – {{ $transfer->code ?? 'TRF-'.$transfer->id }}</title>
    <style>
        @page { margin: 0; size: A5 portrait; }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            background: #ffffff;
            color: #111827;
            width: 148mm;
            min-height: 210mm;
        }

        /* ── HEADER ── */
        .header {
            background: #1d4ed8;
            padding: 20px 22px 14px;
            color: #fff;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }

        .brand-name {
            font-size: 15px;
            font-weight: bold;
        }

        .brand-sub {
            font-size: 10px;
            color: rgba(255,255,255,.7);
            margin-top: 2px;
        }

        .trx-id {
            font-size: 10px;
            text-align: right;
            color: rgba(255,255,255,.8);
        }

        .trx-code {
            font-size: 12px;
            font-weight: bold;
            color: #fff;
            margin-top: 2px;
        }

        .status-row {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .status-date {
            font-size: 10px;
            color: rgba(255,255,255,.7);
        }

        /* ── BADGE STRIP ── */
        .badge-strip {
            background: #eff6ff;
            border-bottom: 2px solid #bfdbfe;
            padding: 8px 22px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .badge-strip-label {
            font-size: 9px;
            font-weight: bold;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: #6b7280;
        }

        .badge-strip-status {
            font-size: 11px;
            font-weight: bold;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-pending { background: #fef3c7; color: #92400e; }

        /* ── AMOUNT ── */
        .amount-block {
            text-align: center;
            padding: 16px 0 14px;
            border-bottom: 1px solid #e5e7eb;
        }

        .amount-label {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #6b7280;
            margin-bottom: 6px;
        }

        .amount-value {
            font-size: 30px;
            font-weight: bold;
            color: #1e3a8a;
        }

        /* ── ROWS ── */
        .rows {
            padding: 4px 22px;
            border-bottom: 1px solid #e5e7eb;
        }

        .row-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .row-label {
            font-size: 11px;
            color: #6b7280;
        }

        .row-value {
            font-size: 11px;
            font-weight: bold;
            color: #111827;
            text-align: right;
        }

        /* ── ASSURANCE ── */
        .assurance {
            margin: 14px 22px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 9.5px;
            color: #1e3a8a;
            line-height: 1.6;
        }

        /* ── FOOTER ── */
        .footer {
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
            padding: 12px 22px 20px;
        }

        .footer-divider {
            border: none;
            border-top: 1px dashed #e5e7eb;
            margin: 12px 22px;
        }
    </style>
</head>
<body>
@php
    \Carbon\Carbon::setLocale('id');
    $code        = $transfer->code ?? 'TRF-'.$transfer->id;
    $isSuccess   = $transfer->status === 'success' || !empty($transfer->confirmation_date);
    $tglTransfer = \Carbon\Carbon::parse($transfer->transfer_date)->translatedFormat('d F Y');
    $tglKonfirm  = $transfer->confirmation_date
                    ? \Carbon\Carbon::parse($transfer->confirmation_date)->translatedFormat('d F Y, H:i') . ' WIB'
                    : null;
@endphp

{{-- HEADER --}}
<div class="header">
    <div class="header-top">
        <div>
            <div class="brand-name">CIO Network Solution</div>
            <div class="brand-sub">Investment Management Portal</div>
        </div>
        <div class="trx-id">
            <div>No. Transaksi</div>
            <div class="trx-code">#{{ $code }}</div>
        </div>
    </div>
    <div class="status-row">
        {{ $isSuccess ? 'Transaksi berhasil diproses' : 'Transaksi sedang diproses' }}
    </div>
    <div class="status-date">{{ $tglTransfer }} WIB</div>
</div>

{{-- Badge strip --}}
<div class="badge-strip">
    <div class="badge-strip-label">Status Pembayaran</div>
    <div class="badge-strip-status {{ $isSuccess ? 'badge-success' : 'badge-pending' }}">
        {{ $isSuccess ? '✔ Berhasil' : '⏳ Diproses' }}
    </div>
</div>

{{-- Amount --}}
<div class="amount-block">
    <div class="amount-label">Total Dividen Diterima</div>
    <div class="amount-value">Rp {{ number_format($transfer->amount, 0, ',', '.') }}</div>
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
    <div class="row-item" style="border-bottom:none;">
        <span class="row-label">Diproses Oleh</span>
        <span class="row-value">{{ $transfer->admin->name ?? 'Admin CIO' }}</span>
    </div>
</div>

{{-- Assurance --}}
<div class="assurance">
    <strong>Bukti Sah:</strong> Bukti transfer sah yang diterbitkan otomatis oleh sistem resmi PT CIO Network Solution.
</div>

<hr class="footer-divider">

<div class="footer">
    &copy; {{ date('Y') }} PT CIO Network Solution &nbsp;&middot;&nbsp; All rights reserved.
    <br>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB
</div>

</body>
</html>
