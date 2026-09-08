@extends('layouts.app')

@section('title')
    Pengaturan Sistem
@endsection

@push('css')
<style>
    /* ==========================================================================
       MODERN SAAS SETTINGS SYSTEM
       ========================================================================== */
    .settings-nav-pills {
        display: flex;
        gap: 8px;
        background: #f1f5f9;
        padding: 6px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        margin-bottom: 1.5rem;
    }

    .settings-nav-link {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        color: #64748b;
        background: transparent;
        border: none;
        transition: all 0.2s ease;
        text-decoration: none;
        cursor: pointer;
    }

    .settings-nav-link:hover {
        color: #1e293b;
        background: rgba(255, 255, 255, 0.6);
    }

    .settings-nav-link.active {
        color: #2563eb !important;
        background: #ffffff !important;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08);
    }

    /* Radio Channel Option Cards */
    .channel-select-card {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        align-items: center;
        gap: 12px;
        height: 100%;
        position: relative;
    }

    .channel-select-card:hover {
        border-color: #93c5fd;
        background: #f8fafc;
        transform: translateY(-1px);
    }

    .form-selectgroup-input:checked + .channel-select-card {
        border-color: #2563eb !important;
        background: linear-gradient(180deg, #ffffff 0%, #eff6ff 100%) !important;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.12);
    }

    .channel-icon-wrap {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Developer Integration Box */
    .dev-integration-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem;
    }

    .code-copy-box {
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 8px 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-family: monospace;
        font-size: 0.85rem;
    }

    /* Dashboard Column Toggle Card */
    .col-toggle-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 18px;
        background: #ffffff;
        transition: all 0.2s ease;
    }

    .col-toggle-card:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }

    .form-check-input:checked {
        background-color: #2563eb !important;
        border-color: #2563eb !important;
    }

    .num-font {
        font-variant-numeric: tabular-nums;
    }

    @media (max-width: 768px) {
        .settings-nav-pills { flex-direction: column; }
        .settings-nav-link { justify-content: flex-start; }
    }
</style>
@endpush

@section('content')
<div class="container-xl py-2">

    @include('components.alert.success')

    <!-- Modern Tab Navigation Bar -->
    <ul class="nav settings-nav-pills" id="settingsTab" role="tablist">
        <li class="nav-item flex-fill" role="presentation">
            <button class="settings-nav-link active w-100" id="tab-notif-btn" data-bs-toggle="tab" data-bs-target="#tab-notif" type="button" role="tab" aria-controls="tab-notif" aria-selected="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" /><path d="M9 17v1a3 3 0 0 0 6 0v-1" /></svg>
                <span>Notifikasi & Biaya Admin</span>
            </button>
        </li>
        <li class="nav-item flex-fill" role="presentation">
            <button class="settings-nav-link w-100" id="tab-xendit-btn" data-bs-toggle="tab" data-bs-target="#tab-xendit" type="button" role="tab" aria-controls="tab-xendit" aria-selected="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 5m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M3 10l18 0" /><path d="M7 15l.01 0" /><path d="M11 15l2 0" /></svg>
                <span>Integrasi Xendit & Multi-Web Router</span>
            </button>
        </li>
        <li class="nav-item flex-fill" role="presentation">
            <button class="settings-nav-link w-100" id="tab-columns-btn" data-bs-toggle="tab" data-bs-target="#tab-columns" type="button" role="tab" aria-controls="tab-columns" aria-selected="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h4v4h-4z" /><path d="M4 10h4v4h-4z" /><path d="M4 16h4v4h-4z" /><path d="M10 4h10" /><path d="M10 10h10" /><path d="M10 16h10" /></svg>
                <span>Visibilitas Kolom Dashboard</span>
            </button>
        </li>
    </ul>

    <!-- Tab Content Panels -->
    <div class="tab-content" id="settingsTabContent">
        {{-- =========================================================
             TAB 1: NOTIFIKASI & BIAYA ADMIN
        ========================================================== --}}
        <div class="tab-pane fade show active" id="tab-notif" role="tabpanel" aria-labelledby="tab-notif-btn">
            <form action="{{ route('setting.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $settings->id ?? 1 }}">
                <!-- Preserve Xendit fields when saving Tab 1 -->
                <input type="hidden" name="xendit_secret_key" value="{{ $settings->xendit_secret_key ?? '' }}">
                <input type="hidden" name="xendit_webhook_token" value="{{ $settings->xendit_webhook_token ?? '' }}">

                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header py-3.5 px-4 bg-white border-bottom">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="avatar rounded-3 bg-primary-subtle text-primary" style="width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" /><path d="M9 17v1a3 3 0 0 0 6 0v-1" /></svg>
                            </div>
                            <div>
                                <h4 class="card-title fw-bold text-dark mb-0">Saluran Notifikasi & Biaya Admin Transfer</h4>
                                <div class="text-muted small">Atur nomor WhatsApp resmi CS, kanal kirim bukti dividen via Mekari Qontak, dan default biaya admin.</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Nomor WhatsApp Official / CS -->
                            <div class="col-md-6">
                                <label for="telp" class="form-label fw-bold text-dark">
                                    Nomor WhatsApp CS / Layanan Pelanggan <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" /><path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" /></svg>
                                    </span>
                                    <input value="{{ old('telp', $settings->telp ?? '') }}" type="text" name="telp" id="telp" class="form-control font-monospace fw-semibold @error('telp') is-invalid @enderror" placeholder="Contoh: 628123456789" required>
                                </div>
                                <div class="form-text text-muted small mt-1">Nomor resmi kontak WhatsApp untuk pertanyaan investor & konfirmasi otomatis.</div>
                                @error('telp')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Biaya Admin Transfer (Admin Fee) -->
                            <div class="col-md-6">
                                <label for="admin_fee" class="form-label fw-bold text-dark">
                                    Biaya Admin Transfer Default (Per Transaksi)
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 fw-bold text-primary">Rp</span>
                                    <input value="{{ number_format($settings->admin_fee ?? 0, 0, ',', '.') }}" type="text" name="admin_fee" id="admin_fee" class="form-control font-monospace fw-bold fs-4 @error('admin_fee') is-invalid @enderror" placeholder="0">
                                </div>
                                <div class="form-text text-muted small mt-1">
                                    Biaya admin otomatis mengurangi <strong>nominal bersih transfer</strong> yang dikirim ke investor.
                                </div>
                            </div>

                            <!-- Saluran Notifikasi Dividen (Mekari Qontak) -->
                            <div class="col-12">
                                <label class="form-label fw-bold text-dark mb-2">
                                    Pilih Saluran Notifikasi Bukti Transfer (Mekari Qontak) <span class="text-danger">*</span>
                                </label>
                                @php
                                    $currentChannel = old('notification_channel', $settings->notification_channel ?? 'whatsapp');
                                @endphp
                                <div class="row g-3">
                                    <!-- WhatsApp -->
                                    <div class="col-md-3 col-sm-6">
                                        <label class="form-selectgroup-item w-100">
                                            <input type="radio" name="notification_channel" value="whatsapp" class="form-selectgroup-input" {{ $currentChannel === 'whatsapp' ? 'checked' : '' }}>
                                            <div class="channel-select-card">
                                                <div class="channel-icon-wrap" style="background: #ecfdf5; color: #059669;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" /><path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" /></svg>
                                                </div>
                                                <div>
                                                    <strong class="d-block text-dark">WhatsApp</strong>
                                                    <span class="text-muted" style="font-size: 0.75rem;">Kirim template WA</span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-3 col-sm-6">
                                        <label class="form-selectgroup-item w-100">
                                            <input type="radio" name="notification_channel" value="email" class="form-selectgroup-input" {{ $currentChannel === 'email' ? 'checked' : '' }}>
                                            <div class="channel-select-card">
                                                <div class="channel-icon-wrap" style="background: #eff6ff; color: #2563eb;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
                                                </div>
                                                <div>
                                                    <strong class="d-block text-dark">Email</strong>
                                                    <span class="text-muted" style="font-size: 0.75rem;">Kirim bukti PDF</span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>

                                    <!-- Both -->
                                    <div class="col-md-3 col-sm-6">
                                        <label class="form-selectgroup-item w-100">
                                            <input type="radio" name="notification_channel" value="both" class="form-selectgroup-input" {{ $currentChannel === 'both' ? 'checked' : '' }}>
                                            <div class="channel-select-card">
                                                <div class="channel-icon-wrap" style="background: #f5f3ff; color: #7c3aed;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 9h8" /><path d="M8 13h6" /><path d="M18 4a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-5l-5 3v-3h-2a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12z" /></svg>
                                                </div>
                                                <div>
                                                    <strong class="d-block text-dark">Keduanya</strong>
                                                    <span class="text-muted" style="font-size: 0.75rem;">WA & Email</span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>

                                    <!-- None -->
                                    <div class="col-md-3 col-sm-6">
                                        <label class="form-selectgroup-item w-100">
                                            <input type="radio" name="notification_channel" value="none" class="form-selectgroup-input" {{ $currentChannel === 'none' ? 'checked' : '' }}>
                                            <div class="channel-select-card">
                                                <div class="channel-icon-wrap" style="background: #f1f5f9; color: #64748b;">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                                                </div>
                                                <div>
                                                    <strong class="d-block text-dark">Nonaktif</strong>
                                                    <span class="text-muted" style="font-size: 0.75rem;">Tanpa notifikasi</span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white py-3 px-4 d-flex justify-content-end border-top">
                        <button type="submit" class="btn btn-primary px-4 py-2 d-inline-flex align-items-center gap-2 rounded-3 fw-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                            <span>Simpan Notifikasi & Biaya Admin</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- =========================================================
             TAB 2: INTEGRASI XENDIT & MULTI-WEB ROUTER
        ========================================================== --}}
        <div class="tab-pane fade" id="tab-xendit" role="tabpanel" aria-labelledby="tab-xendit-btn">
            <form action="{{ route('setting.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $settings->id ?? 1 }}">
                <!-- Preserve general fields when saving Tab 2 -->
                <input type="hidden" name="telp" value="{{ $settings->telp ?? '08123456789' }}">
                <input type="hidden" name="notification_channel" value="{{ $settings->notification_channel ?? 'whatsapp' }}">
                <input type="hidden" name="admin_fee" value="{{ $settings->admin_fee ?? 0 }}">

                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header py-3.5 px-4 bg-white border-bottom">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="avatar rounded-3 bg-azure-subtle text-azure" style="width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 5m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M3 10l18 0" /><path d="M7 15l.01 0" /><path d="M11 15l2 0" /></svg>
                            </div>
                            <div>
                                <h4 class="card-title fw-bold text-dark mb-0">Integrasi Xendit API & Central Webhook Router</h4>
                                <div class="text-muted small">Konfigurasi kunci API disbursement, token verifikasi callback, dan prefix router multi-website.</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Xendit Secret Key -->
                            <div class="col-md-6">
                                <label for="xendit_secret_key" class="form-label fw-bold text-dark">
                                    Xendit Secret API Key
                                </label>
                                <div class="input-group">
                                    <input type="password" name="xendit_secret_key" id="xendit_secret_key" value="{{ $settings->xendit_secret_key ?? '' }}" class="form-control font-monospace" placeholder="xnd_development_... atau xnd_production_...">
                                    <button class="btn btn-outline-secondary" type="button" id="btn-toggle-secret" title="Tampilkan/Sembunyikan Kunci">
                                        <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                    </button>
                                </div>
                                <div class="form-text text-muted small mt-1">
                                    Biarkan kosong jika sudah mengisi <code>XENDIT_SECRET_KEY</code> pada file <code>.env</code>.
                                </div>
                            </div>

                            <!-- Xendit Webhook Verification Token -->
                            <div class="col-md-6">
                                <label for="xendit_webhook_token" class="form-label fw-bold text-dark">
                                    Xendit Webhook Verification Token
                                </label>
                                <input type="text" name="xendit_webhook_token" id="xendit_webhook_token" value="{{ $settings->xendit_webhook_token ?? '' }}" class="form-control font-monospace" placeholder="Token verifikasi webhook Xendit">
                                <div class="form-text text-muted small mt-1">
                                    Token verifikasi header <code>x-callback-token</code> untuk memvalidasi keaslian webhook.
                                </div>
                            </div>

                            <!-- Standarisasi Router Multi-Website Box -->
                            <div class="col-12">
                                <div class="dev-integration-box">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-primary text-white px-2.5 py-1 rounded-pill fw-bold">STANDAR PREFIX</span>
                                            <strong class="text-dark">Router Forwarder Multi-Website</strong>
                                        </div>
                                        <span class="badge bg-blue-lt fw-bold font-monospace fs-5">INV-</span>
                                    </div>
                                    <p class="text-muted small mb-3">
                                        Seluruh ID transaksi, referensi mutasi finance, dan disbursement Xendit dari sistem Investor menggunakan prefix <code>INV-</code>. Router sentral Web Finance akan secara otomatis mem-forward webhook ke endpoint receiver di bawah ini:
                                    </p>
                                    <div class="code-copy-box">
                                        <span class="text-primary font-monospace" id="webhook-url">{{ url('/api/xendit/callback') }}</span>
                                        <button type="button" class="btn btn-sm btn-primary px-3 py-1 d-inline-flex align-items-center gap-1 rounded-2" id="btn-copy-webhook">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 8m0 2a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8a2 2 0 0 1 -2 2h-8a2 2 0 0 1 -2 -2z" /><path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2" /></svg>
                                            <span>Salin URL</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white py-3 px-4 d-flex justify-content-end border-top">
                        <button type="submit" class="btn btn-primary px-4 py-2 d-inline-flex align-items-center gap-2 rounded-3 fw-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                            <span>Simpan Kredensial Xendit</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- =========================================================
             TAB 3: VISIBILITAS KOLOM DASHBOARD
        ========================================================== --}}
        <div class="tab-pane fade" id="tab-columns" role="tabpanel" aria-labelledby="tab-columns-btn">
            <form action="{{ route('setting.dashboard.columns') }}" method="POST" id="form-columns">
                @csrf

                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header py-3.5 px-4 bg-white border-bottom">
                        <div class="d-flex align-items-center gap-2.5">
                            <div class="avatar rounded-3 bg-warning-subtle text-warning" style="width: 38px; height: 38px; display: inline-flex; align-items: center; justify-content: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h4v4h-4z" /><path d="M4 10h4v4h-4z" /><path d="M4 16h4v4h-4z" /><path d="M10 4h10" /><path d="M10 10h10" /><path d="M10 16h10" /></svg>
                            </div>
                            <div>
                                <h4 class="card-title fw-bold text-dark mb-0">Visibilitas Kolom Tabel Dashboard</h4>
                                <div class="text-muted small">Atur kolom yang tampil pada tabel monitoring distribusi dividen investor di halaman utama.</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <!-- Info Banner -->
                        <div class="alert alert-primary d-flex align-items-start gap-2 border-0 rounded-3 mb-4 py-2.5 px-3.5" style="background-color: #eff6ff; border-left: 4px solid #2563eb !important;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 flex-shrink-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9h.01" /><path d="M11 12h1v4h1" /><path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" /></svg>
                            <div class="small text-primary fw-medium">
                                Kolom <strong>Nama Investor</strong> selalu ditampilkan secara default sebagai identitas utama. Toggle di bawah mengatur kolom data tambahan lainnya.
                            </div>
                        </div>

                        <!-- Column Toggle List -->
                        <div class="row g-3">
                            @php
                                $columnIcons = [
                                    'dana_investasi'     => ['icon' => '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" />', 'color' => '#2563eb', 'bg' => '#eff6ff', 'desc' => 'Total modal investasi yang disetor oleh investor'],
                                    'persentase'         => ['icon' => '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M7 7m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M6 18l12 -12" />', 'color' => '#7c3aed', 'bg' => '#f5f3ff', 'desc' => 'Persentase bagi hasil bulanan investor'],
                                    'nominal_pendapatan' => ['icon' => '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" />', 'color' => '#059669', 'bg' => '#ecfdf5', 'desc' => 'Nominal rupiah dividen bulanan yang diterima'],
                                    'status_pembayaran'  => ['icon' => '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" />', 'color' => '#dc2626', 'bg' => '#fef2f2', 'desc' => 'Status apakah transfer bulan ini sudah dilakukan'],
                                ];
                            @endphp

                            @foreach($dashboardColumns as $key => $col)
                                @php $meta = $columnIcons[$key] ?? ['icon' => '', 'color' => '#2563eb', 'bg' => '#eff6ff', 'desc' => '']; @endphp
                                <div class="col-md-6">
                                    <div class="col-toggle-card d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar rounded-3 fw-bold flex-shrink-0" style="width: 42px; height: 42px; background-color: {{ $meta['bg'] }}; color: {{ $meta['color'] }}; display: inline-flex; align-items: center; justify-content: center;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="{{ $meta['color'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $meta['icon'] !!}</svg>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark fs-5">{{ $col['label'] }}</div>
                                                <div class="text-muted small">{{ $meta['desc'] }}</div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2.5">
                                            <span class="col-status-label small fw-bold {{ $col['visible'] ? 'text-success' : 'text-danger' }}" id="label-{{ $key }}">
                                                {{ $col['visible'] ? 'Publik' : 'Tersembunyi' }}
                                            </span>
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input col-toggle" type="checkbox" role="switch"
                                                    name="columns[{{ $key }}]"
                                                    id="col_{{ $key }}"
                                                    data-key="{{ $key }}"
                                                    style="width: 44px; height: 22px; cursor: pointer;"
                                                    {{ $col['visible'] ? 'checked' : '' }}>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="card-footer bg-white py-3 px-4 d-flex justify-content-between align-items-center border-top">
                        <button type="button" class="btn btn-outline-secondary px-3 py-2 d-inline-flex align-items-center gap-1.5 rounded-3 fw-semibold" id="btn-reset-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
                            <span>Reset Semua ke Publik</span>
                        </button>
                        <button type="submit" class="btn btn-primary px-4 py-2 d-inline-flex align-items-center gap-2 rounded-3 fw-bold">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                            <span>Simpan Visibilitas Kolom</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle label updates
        document.querySelectorAll('.col-toggle').forEach(function(toggle) {
            toggle.addEventListener('change', function() {
                const key = this.dataset.key;
                const label = document.getElementById('label-' + key);
                if (this.checked) {
                    label.textContent = 'Publik';
                    label.className = 'col-status-label small fw-bold text-success';
                } else {
                    label.textContent = 'Tersembunyi';
                    label.className = 'col-status-label small fw-bold text-danger';
                }
            });
        });

        // Reset all columns to public
        const btnReset = document.getElementById('btn-reset-all');
        if (btnReset) {
            btnReset.addEventListener('click', function() {
                document.querySelectorAll('.col-toggle').forEach(function(toggle) {
                    toggle.checked = true;
                    const key = toggle.dataset.key;
                    const label = document.getElementById('label-' + key);
                    if (label) {
                        label.textContent = 'Publik';
                        label.className = 'col-status-label small fw-bold text-success';
                    }
                });
            });
        }

        // Format admin_fee currency
        const adminFeeInput = document.getElementById('admin_fee');
        if (adminFeeInput) {
            adminFeeInput.addEventListener('input', function() {
                let val = this.value.replace(/\D/g, '');
                this.value = val ? new Intl.NumberFormat('id-ID').format(val) : '0';
            });
        }

        // Toggle show/hide password for Xendit secret key
        const btnToggleSecret = document.getElementById('btn-toggle-secret');
        const secretInput = document.getElementById('xendit_secret_key');
        if (btnToggleSecret && secretInput) {
            btnToggleSecret.addEventListener('click', function() {
                const isPassword = secretInput.type === 'password';
                secretInput.type = isPassword ? 'text' : 'password';
            });
        }

        // One-click copy webhook URL
        const btnCopyWebhook = document.getElementById('btn-copy-webhook');
        if (btnCopyWebhook) {
            btnCopyWebhook.addEventListener('click', function() {
                const url = document.getElementById('webhook-url').innerText;
                navigator.clipboard.writeText(url).then(() => {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'URL Webhook berhasil disalin!',
                        showConfirmButton: false,
                        timer: 2000
                    });
                });
            });
        }

        // Remember active tab after refresh
        const activeTabKey = 'cio_active_settings_tab';
        const savedTab = localStorage.getItem(activeTabKey);
        if (savedTab) {
            const triggerEl = document.querySelector(`[data-bs-target="${savedTab}"]`);
            if (triggerEl) {
                const tabInstance = new bootstrap.Tab(triggerEl);
                tabInstance.show();
            }
        }

        document.querySelectorAll('.settings-nav-link').forEach(function(tabBtn) {
            tabBtn.addEventListener('shown.bs.tab', function(e) {
                localStorage.setItem(activeTabKey, e.target.getAttribute('data-bs-target'));
            });
        });
    });
</script>
@endpush