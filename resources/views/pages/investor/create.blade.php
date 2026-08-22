@extends('layouts.app')

@section('title')
    Tambah Data Investasi
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <style>
        /* Wizard Steps Container */
        .wizard-steps-wrapper {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 24px 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            margin-bottom: 2rem;
            position: relative;
        }

        .wizard-steps-track {
            position: absolute;
            top: 48px;
            left: calc(12.5% + 20px);
            right: calc(12.5% + 20px);
            height: 4px;
            background: #e2e8f0;
            border-radius: 4px;
            z-index: 1;
        }

        .wizard-progress-bar {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            background: linear-gradient(90deg, #2563eb, #3b82f6);
            border-radius: 4px;
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 0 10px rgba(37, 99, 235, 0.4);
        }

        .wizard-step-item {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
            cursor: pointer;
            transition: all 0.3s ease;
            user-select: none;
            padding: 0 6px;
        }

        .wizard-step-circle-wrap {
            position: relative;
            display: inline-block;
            margin-bottom: 12px;
        }

        .wizard-step-circle {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            position: relative;
        }

        .wizard-step-badge {
            position: absolute;
            bottom: -4px;
            right: -4px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #64748b;
            color: #ffffff;
            font-size: 11px;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #ffffff;
            transition: all 0.3s ease;
        }

        /* Active State */
        .wizard-step-item.active .wizard-step-circle {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border-color: #2563eb;
            color: #ffffff;
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.28), 0 0 0 5px rgba(37, 99, 235, 0.12);
        }

        .wizard-step-item.active .wizard-step-badge {
            background: #f59e0b;
            color: #ffffff;
            transform: scale(1.1);
        }

        .wizard-step-item.active .wizard-step-title {
            color: #1e40af;
            font-weight: 700;
        }

        /* Completed State */
        .wizard-step-item.completed .wizard-step-circle {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-color: #10b981;
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.25);
        }

        .wizard-step-item.completed .wizard-step-badge {
            background: #10b981;
            color: #ffffff;
        }

        .wizard-step-item.completed .wizard-step-title {
            color: #065f46;
            font-weight: 700;
        }

        .wizard-step-title {
            font-weight: 600;
            font-size: 0.88rem;
            color: #475569;
            margin-bottom: 3px;
            transition: color 0.3s ease;
            white-space: nowrap;
        }

        .wizard-step-desc {
            font-size: 0.75rem;
            color: #94a3b8;
        }

        .step-pill-tag {
            font-size: 0.72rem;
            padding: 3px 10px;
            border-radius: 20px;
            display: inline-block;
            margin-top: 6px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        /* Selection Cards */
        .selection-card {
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            padding: 1.5rem;
            transition: all 0.25s ease;
            cursor: pointer;
            background: #ffffff;
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .selection-card:hover {
            border-color: #93c5fd;
            transform: translateY(-3px);
            box-shadow: 0 10px 22px rgba(37, 99, 235, 0.09);
        }

        .selection-card.selected {
            border-color: #2563eb;
            background: #f8faff;
            box-shadow: 0 10px 24px rgba(37, 99, 235, 0.14);
        }

        .selection-card.selected::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: #2563eb;
        }

        /* Step Content Animations */
        .wizard-content-pane {
            display: none;
            opacity: 0;
            transform: translateY(10px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .wizard-content-pane.active {
            display: block;
            opacity: 1;
            transform: translateY(0);
        }

        /* Live Calculation Box */
        .income-preview-card {
            background: linear-gradient(135deg, #eff6ff 0%, #e0e7ff 100%);
            border: 1.5px solid #bfdbfe;
            border-radius: 14px;
            padding: 1.25rem;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.06);
        }

        /* Investor Detail Card */
        .investor-detail-card {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            border: 1.5px solid #a7f3d0;
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.08);
            margin-top: 1.25rem;
        }

        /* Upload Box */
        .file-upload-dropzone {
            border: 2px dashed #cbd5e1;
            border-radius: 14px;
            padding: 24px;
            text-align: center;
            background: #ffffff;
            transition: all 0.25s ease;
            cursor: pointer;
        }

        .file-upload-dropzone:hover {
            border-color: #2563eb;
            background: #f8faff;
        }

        /* Form Labels with icons */
        .field-label {
            font-weight: 600;
            font-size: 0.875rem;
            color: #334155;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 6px;
        }
    </style>
@endpush

@section('content')
<div class="card shadow-sm border-0">
    <!-- Card Header -->
    <div class="card-header d-flex justify-content-between align-items-center py-3 px-4 bg-white border-bottom">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar avatar-md rounded-3 bg-primary-subtle text-primary fw-bold" style="width: 44px; height: 44px; display: inline-flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" /><path d="M12 8v8" /><path d="M8 12h8" /></svg>
            </div>
            <div>
                <h3 class="card-title fw-bold text-dark mb-1">Pendaftaran & Penambahan Investasi</h3>
                <div class="text-muted small">Alur bertahap 4 langkah untuk registrasi pemodal dan pembagian paket dividen</div>
            </div>
        </div>
        <a href="{{ route('investor.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1 shadow-none">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 14l-4 -4l4 -4" /><path d="M5 10h11a4 4 0 1 1 0 8h-1" /></svg>
            Kembali
        </a>
    </div>

    <!-- Wizard Form Body -->
    <form action="{{ route('investor.store') }}" method="POST" enctype="multipart/form-data" id="wizard-form">
        @csrf
        <div class="card-body p-4 p-md-5">
            
            <!-- Modern 4-Step Wizard Indicators -->
            <div class="wizard-steps-wrapper">
                <div class="wizard-steps-track">
                    <div class="wizard-progress-bar" id="wizardProgressBar" style="width: 0%;"></div>
                </div>
                
                <div class="d-flex justify-content-between align-items-start position-relative">
                    <!-- Step 1 Indicator -->
                    <div class="wizard-step-item active" id="step-nav-1" onclick="goToStep(1)">
                        <div class="wizard-step-circle-wrap">
                            <div class="wizard-step-circle">
                                <span class="step-icon-state" id="step-icon-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h6v6h-6z" /><path d="M14 4h6v6h-6z" /><path d="M4 14h6v6h-6z" /><path d="M14 14h6v6h-6z" /></svg>
                                </span>
                            </div>
                            <span class="wizard-step-badge">1</span>
                        </div>
                        <div class="wizard-step-title">1. Tipe Pemodal</div>
                        <div class="wizard-step-desc d-none d-md-block">Pilih Mode Pendaftaran</div>
                        <span class="step-pill-tag bg-primary-subtle text-primary" id="step-tag-1">Sedang Diisi</span>
                    </div>

                    <!-- Step 2 Indicator -->
                    <div class="wizard-step-item" id="step-nav-2" onclick="goToStep(2)">
                        <div class="wizard-step-circle-wrap">
                            <div class="wizard-step-circle">
                                <span class="step-icon-state" id="step-icon-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 11h6m-3 -3v6" /></svg>
                                </span>
                            </div>
                            <span class="wizard-step-badge">2</span>
                        </div>
                        <div class="wizard-step-title">2. Identitas Pemodal</div>
                        <div class="wizard-step-desc d-none d-md-block" id="step-desc-2">Akun / Profil Investor</div>
                        <span class="step-pill-tag bg-light text-muted" id="step-tag-2">Menunggu</span>
                    </div>

                    <!-- Step 3 Indicator -->
                    <div class="wizard-step-item" id="step-nav-3" onclick="goToStep(3)">
                        <div class="wizard-step-circle-wrap">
                            <div class="wizard-step-circle">
                                <span class="step-icon-state" id="step-icon-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" /></svg>
                                </span>
                            </div>
                            <span class="wizard-step-badge">3</span>
                        </div>
                        <div class="wizard-step-title">3. Paket Investasi</div>
                        <div class="wizard-step-desc d-none d-md-block">Modal & Persentase</div>
                        <span class="step-pill-tag bg-light text-muted" id="step-tag-3">Menunggu</span>
                    </div>

                    <!-- Step 4 Indicator -->
                    <div class="wizard-step-item" id="step-nav-4" onclick="goToStep(4)">
                        <div class="wizard-step-circle-wrap">
                            <div class="wizard-step-circle">
                                <span class="step-icon-state" id="step-icon-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 15l2 2l4 -4" /></svg>
                                </span>
                            </div>
                            <span class="wizard-step-badge">4</span>
                        </div>
                        <div class="wizard-step-title">4. Dokumen & Review</div>
                        <div class="wizard-step-desc d-none d-md-block">Surat Perjanjian & Final</div>
                        <span class="step-pill-tag bg-light text-muted" id="step-tag-4">Menunggu</span>
                    </div>
                </div>
            </div>

            @php
                $defaultMode = (isset($selectedUserId) && $selectedUserId) || old('investor_mode') == 'existing' ? 'existing' : 'new';
                $activeUserId = old('users_id', $selectedUserId ?? null);
            @endphp

            <!-- ========================================================================= -->
            <!-- STEP 1: PILIH TIPE / MODE PEMODAL -->
            <!-- ========================================================================= -->
            <div class="wizard-content-pane active" id="step-pane-1">
                <div class="text-center mb-4">
                    <span class="badge bg-primary-subtle text-primary px-3 py-1.5 rounded-pill mb-2 fw-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h6v6h-6z" /><path d="M14 4h6v6h-6z" /><path d="M4 14h6v6h-6z" /><path d="M14 14h6v6h-6z" /></svg>
                        Langkah 1 dari 4
                    </span>
                    <h4 class="fw-bold text-dark mb-1">Pilih Tipe Pemodal</h4>
                    <p class="text-muted small">Tentukan apakah ingin mendaftarkan pemodal baru atau menambahkan paket ke investor yang sudah ada</p>
                </div>

                <!-- Option Selection Cards -->
                <div class="row g-4 mb-4 justify-content-center">
                    <div class="col-md-6 col-lg-5">
                        <div class="selection-card {{ $defaultMode == 'new' ? 'selected' : '' }}" id="card-mode-new" onclick="selectMode('new')">
                            <div class="d-flex align-items-start gap-3">
                                <div class="avatar avatar-lg rounded-3 bg-primary text-white fw-bold shadow-sm" style="width: 52px; height: 52px; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 11h6m-3 -3v6" /></svg>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h5 class="fw-bold text-dark mb-0">Investor Baru</h5>
                                        <input type="radio" name="investor_mode" id="mode_new" value="new" {{ $defaultMode == 'new' ? 'checked' : '' }} style="accent-color: #2563eb; transform: scale(1.3);">
                                    </div>
                                    <div class="text-muted small mb-2">Buat akun login baru dan data identitas pemodal pertama kali</div>
                                    <span class="badge bg-primary-subtle text-primary fw-semibold px-2.5 py-1">Registrasi Akun Baru</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-5">
                        <div class="selection-card {{ $defaultMode == 'existing' ? 'selected' : '' }}" id="card-mode-existing" onclick="selectMode('existing')">
                            <div class="d-flex align-items-start gap-3">
                                <div class="avatar avatar-lg rounded-3 bg-success text-white fw-bold shadow-sm" style="width: 52px; height: 52px; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h5 class="fw-bold text-dark mb-0">Investor Terdaftar</h5>
                                        <input type="radio" name="investor_mode" id="mode_existing" value="existing" {{ $defaultMode == 'existing' ? 'checked' : '' }} style="accent-color: #2563eb; transform: scale(1.3);">
                                    </div>
                                    <div class="text-muted small mb-2">Tambahkan paket investasi baru untuk investor yang sudah terdaftar di sistem</div>
                                    <span class="badge bg-success-subtle text-success fw-semibold px-2.5 py-1">Multi-Paket Investasi</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- STEP 2: IDENTITAS / PEMILIHAN PEMODAL -->
            <!-- ========================================================================= -->
            <div class="wizard-content-pane" id="step-pane-2">
                
                <!-- Sub-step A: Jika Mode Investor Baru -->
                <div id="section_new_account" style="{{ $defaultMode == 'new' ? '' : 'display: none;' }}">
                    <div class="text-center mb-4">
                        <span class="badge bg-primary-subtle text-primary px-3 py-1.5 rounded-pill mb-2 fw-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                            Langkah 2 dari 4 (Investor Baru)
                        </span>
                        <h4 class="fw-bold text-dark mb-1">Informasi Akun & Data Pemodal Baru</h4>
                        <p class="text-muted small">Lengkapi data akun login pemodal agar dapat mengakses portal investor</p>
                    </div>

                    <div class="p-4 border rounded-3 bg-light-subtle mb-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="field-label" for="username">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M16 12v1.5a2.5 2.5 0 0 0 5 0v-1.5a9 9 0 1 0 -5.5 8.28" /></svg>
                                    Username <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" placeholder="Contoh: hendra_wijaya">
                                @error('username')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="field-label" for="name">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M15 8l2 0" /><path d="M15 12l2 0" /><path d="M7 16l10 0" /></svg>
                                    Nama Lengkap (KTP) <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Nama lengkap sesuai identitas">
                                @error('name')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="field-label" for="email">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
                                    Alamat Email <span class="text-danger">*</span>
                                </label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="email@contoh.com">
                                @error('email')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="field-label" for="phone">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg>
                                    Nomor Telepon / WhatsApp <span class="text-muted fw-normal fs-7">(Opsional)</span>
                                </label>
                                <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="Contoh: 081234567890">
                                @error('phone')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="field-label" for="password">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 11m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" /><path d="M8 11v-4a4 4 0 0 1 8 0v4" /></svg>
                                    Kata Sandi Akun <span class="text-danger">*</span>
                                </label>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter">
                                @error('password')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="field-label" for="password_confirmation">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 11m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" /><path d="M8 11v-4a4 4 0 0 1 8 0v4" /></svg>
                                    Ulangi Kata Sandi <span class="text-danger">*</span>
                                </label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Konfirmasi sandi">
                                @error('password_confirmation')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sub-step B: Jika Mode Investor Terdaftar -->
                <div id="section_existing_investor" style="{{ $defaultMode == 'existing' ? '' : 'display: none;' }}">
                    <div class="text-center mb-4">
                        <span class="badge bg-success-subtle text-success px-3 py-1.5 rounded-pill mb-2 fw-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                            Langkah 2 dari 4 (Investor Terdaftar)
                        </span>
                        <h4 class="fw-bold text-dark mb-1">Pilih Pemodal & Tinjau Profil</h4>
                        <p class="text-muted small">Cari dan pilih investor yang akan ditambahkan paket investasi barunya</p>
                    </div>

                    <div class="p-4 border rounded-3 bg-light-subtle mb-4">
                        <label class="field-label text-primary fs-6 mb-2" for="users_id">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                            Pilih Investor Terdaftar <span class="text-danger">*</span>
                        </label>
                        <select name="users_id" id="users_id" class="form-select @error('users_id') is-invalid @enderror">
                            <option value="">-- Cari Nama, Email, Phone, atau Username Investor --</option>
                            @foreach ($existingInvestors as $exist)
                                @php
                                    $pkgCount = $exist->investors->count();
                                    $totalFunds = $exist->investors->sum('bussines_funds');
                                    $totalIncome = $exist->investors->sum('monthly_income');
                                @endphp
                                <option value="{{ $exist->id }}" 
                                        data-name="{{ $exist->name }}"
                                        data-email="{{ $exist->email }}"
                                        data-phone="{{ $exist->phone ?? '' }}"
                                        data-username="{{ $exist->username }}"
                                        data-packages="{{ $pkgCount }}"
                                        data-funds="{{ number_format($totalFunds, 0, ',', '.') }}"
                                        data-income="{{ number_format($totalIncome, 0, ',', '.') }}"
                                        {{ $activeUserId == $exist->id ? 'selected' : '' }}>
                                    {{ $exist->name }} (@<span>{{ $exist->username }}</span>) - {{ $exist->email }}{{ $exist->phone ? ' • ' . $exist->phone : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('users_id')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror

                        <!-- Live Selected Investor Details Box -->
                        <div id="investor_profile_box" class="investor-detail-card" style="display: none;">
                            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 border-bottom pb-3 mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar avatar-lg rounded-circle bg-success text-white fw-bold shadow-sm" id="detail_avatar" style="width: 50px; height: 50px; font-size: 1.1rem; display: inline-flex; align-items: center; justify-content: center;">
                                        IN
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center gap-2">
                                            <h5 class="fw-bold text-dark mb-0" id="detail_name">-</h5>
                                            <span class="badge bg-success text-white fw-semibold rounded-pill px-2.5 py-0.5" style="font-size: 0.72rem;">Investor Aktif</span>
                                        </div>
                                        <div class="text-muted small" id="detail_user_email">-</div>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge bg-white text-dark border px-3 py-2 fw-semibold rounded-pill shadow-xs" id="detail_packages_badge">
                                        📦 0 Paket Aktif
                                    </span>
                                </div>
                            </div>

                            <div class="row g-3 text-center text-md-start">
                                <div class="col-sm-6 col-md-6">
                                    <div class="text-muted small mb-1">Total Dana Investasi Terdaftar:</div>
                                    <div class="fw-bold text-primary fs-5" id="detail_funds">Rp 0</div>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <div class="text-muted small mb-1">Total Dividen / Bagi Hasil Bulanan Saat Ini:</div>
                                    <div class="fw-bold text-success fs-5" id="detail_income">Rp 0</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <!-- ========================================================================= -->
            <!-- STEP 3: DETAIL PAKET & MODAL INVESTASI -->
            <!-- ========================================================================= -->
            <div class="wizard-content-pane" id="step-pane-3">
                <div class="text-center mb-4">
                    <span class="badge bg-primary-subtle text-primary px-3 py-1.5 rounded-pill mb-2 fw-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /></svg>
                        Langkah 3 dari 4
                    </span>
                    <h4 class="fw-bold text-dark mb-1">Rincian Paket & Skema Investasi</h4>
                    <p class="text-muted small">Tentukan unit usaha kerjasama, tipe pembagian, modal investasi dan persentase dividen</p>
                </div>

                <div class="p-4 border rounded-3 bg-light-subtle mb-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="field-label" for="categories_id">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2" /></svg>
                                Kategori Kerjasama / Usaha <span class="text-danger">*</span>
                            </label>
                            <select name="categories_id" id="categories_id" class="form-select @error('categories_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori Kerjasama --</option>
                                @foreach ($categories as $ct)
                                    <option value="{{ $ct->id }}" {{ old('categories_id') == $ct->id ? 'selected' : '' }}>{{ $ct->name }}</option>
                                @endforeach
                            </select>
                            @error('categories_id')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="field-label" for="types_id">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 7v5l3 3" /></svg>
                                Tipe / Periode Pembagian <span class="text-danger">*</span>
                            </label>
                            <select name="types_id" id="types_id" class="form-select @error('types_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Tipe Investasi --</option>
                                @foreach ($type as $tp)
                                    <option value="{{ $tp->id }}" {{ old('types_id') == $tp->id ? 'selected' : '' }}>{{ $tp->name }}</option>
                                @endforeach
                            </select>
                            @error('types_id')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="field-label" for="bussines_funds">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" /></svg>
                                Total Modal Investasi <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text fw-bold text-primary bg-white border-end-0">Rp</span>
                                <input type="text" name="bussines_funds" id="bussines_funds" class="form-control font-monospace fw-bold @error('bussines_funds') is-invalid @enderror" value="{{ old('bussines_funds') }}" placeholder="0" required autocomplete="off">
                            </div>
                            @error('bussines_funds')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="field-label" for="persentase">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 15l6 -6" /><path d="M9 9m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M15 15m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>
                                Persentase Bagi Hasil Bulanan <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" step="any" name="persentase" id="persentase" class="form-control font-monospace fw-bold @error('persentase') is-invalid @enderror" value="{{ old('persentase') }}" placeholder="Contoh: 14" required>
                                <span class="input-group-text fw-bold text-primary bg-white border-start-0">%</span>
                            </div>
                            @error('persentase')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Live Calculation Preview Card -->
                    <div class="mt-4 income-preview-card d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar avatar-md rounded-circle bg-primary text-white shadow-sm" style="width: 44px; height: 44px; display: inline-flex; align-items: center; justify-content: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12h1m8 -9v1m8 8h1m-15.4 -6.4l.7 .7m12.1 -.7l-.7 .7" /><path d="M9 16a5 5 0 1 1 6 0a3.5 3.5 0 0 0 -1 3a2 2 0 0 1 -4 0a3.5 3.5 0 0 0 -1 -3" /><path d="M9.7 17l4.6 0" /></svg>
                            </div>
                            <div>
                                <div class="text-muted small fw-semibold">Estimasi Dividen / Bagi Hasil Bulanan:</div>
                                <div class="fw-bold text-primary fs-4" id="preview_income">Rp 0</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success text-white px-3 py-2 rounded-pill fw-bold shadow-sm d-inline-flex align-items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                Kalkulasi Otomatis
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ========================================================================= -->
            <!-- STEP 4: UPLOAD DOKUMEN & REVIEW KONFIRMASI -->
            <!-- ========================================================================= -->
            <div class="wizard-content-pane" id="step-pane-4">
                <div class="text-center mb-4">
                    <span class="badge bg-primary-subtle text-primary px-3 py-1.5 rounded-pill mb-2 fw-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 15l2 2l4 -4" /></svg>
                        Langkah 4 dari 4
                    </span>
                    <h4 class="fw-bold text-dark mb-1">Unggah Surat Perjanjian & Konfirmasi Data</h4>
                    <p class="text-muted small">Upload berkas akad investasi (PDF) dan cek ulang semua informasi sebelum disimpan ke sistem</p>
                </div>

                <div class="row g-4 mb-4">
                    <!-- File Upload Column -->
                    <div class="col-lg-6 col-12">
                        <div class="p-4 border rounded-3 bg-light-subtle h-100 d-flex flex-column justify-content-between">
                            <div>
                                <h6 class="fw-bold text-primary mb-3 d-flex align-items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 15l2 2l4 -4" /></svg>
                                    Berkas Perjanjian Investasi (PDF) <span class="text-danger">*</span>
                                </h6>
                                <div class="file-upload-dropzone mb-3" onclick="$('#file').trigger('click');">
                                    <div class="avatar avatar-lg rounded-circle bg-primary-subtle text-primary mb-2 mx-auto" style="width: 48px; height: 48px; display: inline-flex; align-items: center; justify-content: center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 9l5 -5l5 5" /><path d="M12 4l0 12" /></svg>
                                    </div>
                                    <div class="fw-bold text-dark" id="file_display_name">Klik atau Tarik File Perjanjian ke Sini</div>
                                    <div class="text-muted small">Mendukung format .PDF, .DOC, .DOCX (Maksimal 10MB)</div>
                                </div>
                                <input type="file" name="file" id="file" class="form-control d-none @error('file') is-invalid @enderror" accept=".pdf,.doc,.docx" required>
                                @error('file')
                                    <span class="invalid-feedback d-block mb-3">{{ $message }}</span>
                                @enderror
                            </div>
                            
                            <div class="p-3 bg-white border rounded-3 small text-muted">
                                <div class="fw-bold text-dark mb-1 d-flex align-items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 9h.01" /><path d="M11 12h1v4h1" /></svg>
                                    Ketentuan & Keamanan Berkas:
                                </div>
                                <ul class="mb-0 ps-3">
                                    <li>Setiap paket investasi memiliki dokumen tersendiri.</li>
                                    <li>Investor dapat mengunduh berkas ini melalui portal dashboard mereka.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Review Column -->
                    <div class="col-lg-6 col-12">
                        <div class="p-4 border border-primary-subtle rounded-3 bg-white h-100 shadow-sm d-flex flex-column justify-content-between">
                            <div>
                                <h6 class="fw-bold text-primary mb-3 d-flex align-items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M9 14l2 2l4 -4" /></svg>
                                    Ringkasan Pendaftaran Investasi
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-borderless align-middle m-0">
                                        <tbody>
                                            <tr class="border-bottom">
                                                <td class="text-muted py-2" style="width: 42%;">
                                                    <span class="d-flex align-items-center gap-1.5">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                                                        Nama Pemodal:
                                                    </span>
                                                </td>
                                                <td class="fw-bold text-dark py-2" id="review_investor_name">-</td>
                                            </tr>
                                            <tr class="border-bottom">
                                                <td class="text-muted py-2">
                                                    <span class="d-flex align-items-center gap-1.5">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg>
                                                        No. WhatsApp / Telp:
                                                    </span>
                                                </td>
                                                <td class="fw-semibold text-dark py-2" id="review_phone">-</td>
                                            </tr>
                                            <tr class="border-bottom">
                                                <td class="text-muted py-2">
                                                    <span class="d-flex align-items-center gap-1.5">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /></svg>
                                                        Kategori Usaha:
                                                    </span>
                                                </td>
                                                <td class="fw-semibold text-dark py-2" id="review_category">-</td>
                                            </tr>
                                            <tr class="border-bottom">
                                                <td class="text-muted py-2">
                                                    <span class="d-flex align-items-center gap-1.5">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted"><path stroke="none" d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /></svg>
                                                        Tipe Investasi:
                                                    </span>
                                                </td>
                                                <td class="fw-semibold text-dark py-2" id="review_type">-</td>
                                            </tr>
                                            <tr class="border-bottom">
                                                <td class="text-muted py-2">
                                                    <span class="d-flex align-items-center gap-1.5">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted"><path stroke="none" d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3" /></svg>
                                                        Total Modal:
                                                    </span>
                                                </td>
                                                <td class="fw-bold text-primary py-2" id="review_funds">Rp 0</td>
                                            </tr>
                                            <tr class="border-bottom">
                                                <td class="text-muted py-2">
                                                    <span class="d-flex align-items-center gap-1.5">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted"><path stroke="none" d="M9 15l6 -6" /></svg>
                                                        Persentase:
                                                    </span>
                                                </td>
                                                <td class="fw-bold text-dark py-2" id="review_persentase">0%</td>
                                            </tr>
                                            <tr class="bg-light-subtle rounded-3">
                                                <td class="text-muted py-2.5 ps-2">
                                                    <span class="d-flex align-items-center gap-1.5 fw-semibold text-dark">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-success"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" /><path d="M9 12l2 2l4 -4" /></svg>
                                                        Dividen Bulanan:
                                                    </span>
                                                </td>
                                                <td class="fw-bold text-success fs-6 py-2.5 pe-2" id="review_income">Rp 0 / bln</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Wizard Navigation Footer -->
        <div class="card-footer bg-light-subtle d-flex justify-content-between align-items-center py-3 px-4 border-top">
            <button type="button" class="btn btn-outline-secondary px-4 d-none" id="btnPrev" onclick="navigateStep(-1)">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
                Sebelumnya
            </button>
            <div class="ms-auto d-flex gap-2 align-items-center">
                <a href="{{ route('investor.index') }}" class="btn btn-ghost-secondary px-3">Batal</a>
                
                <button type="button" class="btn btn-primary px-4 shadow-sm" id="btnNext" onclick="navigateStep(1)">
                    Lanjutkan
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon ms-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
                </button>
                
                <button type="submit" class="btn btn-success px-4 shadow-sm d-none" id="btnSubmit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                    Simpan & Daftarkan Investasi
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        var currentStep = 1;
        var totalSteps = 4;

        var stepIcons = {
            1: `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h6v6h-6z" /><path d="M14 4h6v6h-6z" /><path d="M4 14h6v6h-6z" /><path d="M14 14h6v6h-6z" /></svg>`,
            2: `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 11h6m-3 -3v6" /></svg>`,
            3: `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" /></svg>`,
            4: `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 15l2 2l4 -4" /></svg>`,
            check: `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>`
        };

        function selectMode(mode) {
            if (mode === 'new') {
                $('#mode_new').prop('checked', true);
                $('#card-mode-new').addClass('selected');
                $('#card-mode-existing').removeClass('selected');
                $('#section_new_account').show();
                $('#section_existing_investor').hide();
                $('#step-desc-2').text('Buat Akun Pemodal');
                $('#users_id').prop('required', false);
                $('#username, #name, #email, #password, #password_confirmation').prop('required', true);
            } else {
                $('#mode_existing').prop('checked', true);
                $('#card-mode-existing').addClass('selected');
                $('#card-mode-new').removeClass('selected');
                $('#section_new_account').hide();
                $('#section_existing_investor').show();
                $('#step-desc-2').text('Pilih Investor Terdaftar');
                $('#users_id').prop('required', true);
                $('#username, #name, #email, #password, #password_confirmation').prop('required', false);
            }
            updateReviewSummary();
        }

        function updateExistingInvestorDetail() {
            let selectedOpt = $('#users_id').find('option:selected');
            if (selectedOpt.val()) {
                let name = selectedOpt.data('name') || '-';
                let email = selectedOpt.data('email') || '-';
                let phone = selectedOpt.data('phone') || '';
                let username = selectedOpt.data('username') || '-';
                let packages = selectedOpt.data('packages') || 0;
                let funds = selectedOpt.data('funds') || '0';
                let income = selectedOpt.data('income') || '0';

                let initials = name.split(' ').map(w => w.charAt(0)).slice(0, 2).join('').toUpperCase();

                $('#detail_avatar').text(initials || 'IN');
                $('#detail_name').text(name);
                $('#detail_user_email').text('@' + username + ' • ' + email + (phone ? ' • 📱 ' + phone : ''));
                $('#detail_packages_badge').html('📦 ' + packages + ' Paket Aktif Sebelumnya');
                $('#detail_funds').text('Rp ' + funds);
                $('#detail_income').text('Rp ' + income + ' / bln');

                $('#investor_profile_box').fadeIn(250);
            } else {
                $('#investor_profile_box').fadeOut(200);
            }
        }

        function goToStep(step) {
            if (step < 1 || step > totalSteps) return;

            let mode = $('input[name="investor_mode"]:checked').val() || 'new';

            // Validation before going forward
            if (step > currentStep) {
                if (currentStep === 1) {
                    // Mode is always valid since default is checked
                } else if (currentStep === 2) {
                    if (mode === 'existing') {
                        if (!$('#users_id').val()) {
                            alert('Silakan pilih investor terdaftar terlebih dahulu.');
                            return;
                        }
                    } else {
                        if (!$('#username').val() || !$('#name').val() || !$('#email').val() || !$('#password').val()) {
                            alert('Silakan lengkapi informasi akun pemodal baru.');
                            return;
                        }
                        if ($('#password').val().length < 8) {
                            alert('Kata sandi minimal 8 karakter.');
                            return;
                        }
                        if ($('#password').val() !== $('#password_confirmation').val()) {
                            alert('Konfirmasi kata sandi tidak cocok.');
                            return;
                        }
                    }
                } else if (currentStep === 3) {
                    if (!$('#categories_id').val() || !$('#types_id').val() || !$('#bussines_funds').val() || !$('#persentase').val()) {
                        alert('Silakan lengkapi kategori usaha, tipe investasi, nominal modal, dan persentase.');
                        return;
                    }
                }
            }

            currentStep = step;

            // Update step indicators
            for (let i = 1; i <= totalSteps; i++) {
                let stepNav = $('#step-nav-' + i);
                let stepPane = $('#step-pane-' + i);
                let stepIcon = $('#step-icon-' + i);
                let stepTag = $('#step-tag-' + i);

                stepNav.removeClass('active completed');
                stepPane.removeClass('active');

                if (i < currentStep) {
                    stepNav.addClass('completed');
                    stepIcon.html(stepIcons.check);
                    stepTag.text('Selesai')
                           .removeClass('bg-primary-subtle text-primary bg-light text-muted')
                           .addClass('bg-success-subtle text-success');
                } else if (i === currentStep) {
                    stepNav.addClass('active');
                    stepIcon.html(stepIcons[i]);
                    stepTag.text('Sedang Diisi')
                           .removeClass('bg-success-subtle text-success bg-light text-muted')
                           .addClass('bg-primary-subtle text-primary');
                    stepPane.addClass('active');
                } else {
                    stepIcon.html(stepIcons[i]);
                    stepTag.text('Menunggu')
                           .removeClass('bg-primary-subtle text-primary bg-success-subtle text-success')
                           .addClass('bg-light text-muted');
                }
            }

            // Update progress line
            var progressPct = ((currentStep - 1) / (totalSteps - 1)) * 100;
            $('#wizardProgressBar').css('width', progressPct + '%');

            // Button visibility
            if (currentStep === 1) {
                $('#btnPrev').addClass('d-none');
                $('#btnNext').removeClass('d-none');
                $('#btnSubmit').addClass('d-none');
            } else if (currentStep === totalSteps) {
                $('#btnPrev').removeClass('d-none');
                $('#btnNext').addClass('d-none');
                $('#btnSubmit').removeClass('d-none');
                updateReviewSummary();
            } else {
                $('#btnPrev').removeClass('d-none');
                $('#btnNext').removeClass('d-none');
                $('#btnSubmit').addClass('d-none');
            }

            window.scrollTo({ top: 120, behavior: 'smooth' });
        }

        function navigateStep(direction) {
            goToStep(currentStep + direction);
        }

        function formatRupiah(angka) {
            if (!angka && angka !== 0) return "";
            let numberString = angka.toString().replace(/\D/g, ""),
                sisa = numberString.length % 3,
                rupiah = numberString.substr(0, sisa),
                ribuan = numberString.substr(sisa).match(/\d{3}/g);

            if (ribuan) {
                let separator = sisa ? "." : "";
                rupiah += separator + ribuan.join(".");
            }
            return rupiah;
        }

        function calculateLiveIncome() {
            let fundsRaw = $("#bussines_funds").val().replace(/\D/g, "");
            let funds = parseFloat(fundsRaw) || 0;
            let persentase = parseFloat($("#persentase").val()) || 0;
            let income = funds * (persentase / 100);

            let formatted = "Rp " + formatRupiah(Math.round(income));
            $("#preview_income").text(formatted);
            $("#review_income").text(formatted + " / bln");
        }

        function updateReviewSummary() {
            let mode = $('input[name="investor_mode"]:checked').val();
            if (mode === 'existing') {
                let selectedOpt = $('#users_id').find('option:selected');
                let name = selectedOpt.data('name') || '-';
                let email = selectedOpt.data('email') || '';
                let phone = selectedOpt.data('phone') || '';
                $('#review_investor_name').text(name + (email ? ' (' + email + ')' : ''));
                $('#review_phone').text(phone ? phone : '-');
            } else {
                let name = $('#name').val() || '-';
                let email = $('#email').val() || '';
                let phone = $('#phone').val() || '';
                $('#review_investor_name').text(name + (email ? ' (' + email + ')' : ''));
                $('#review_phone').text(phone ? phone : '-');
            }

            let catText = $('#categories_id').find('option:selected').text();
            $('#review_category').text($('#categories_id').val() ? catText : '-');

            let typeText = $('#types_id').find('option:selected').text();
            $('#review_type').text($('#types_id').val() ? typeText : '-');

            let funds = $("#bussines_funds").val() || '0';
            $('#review_funds').text('Rp ' + (funds ? funds : '0'));

            let pct = $("#persentase").val() || '0';
            $('#review_persentase').text(pct + '%');

            calculateLiveIncome();
        }

        $(document).ready(function() {
            $('#users_id').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '-- Cari Nama, Email, Phone, atau Username Investor --',
                allowClear: true
            });

            $('#categories_id').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '-- Pilih Kategori Kerjasama --',
                allowClear: true
            });

            $('#types_id').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '-- Pilih Tipe Investasi --',
                allowClear: true
            });

            $("#bussines_funds").on("keyup", function() {
                let value = $(this).val();
                $(this).val(formatRupiah(value));
                calculateLiveIncome();
            });

            $("#persentase").on("input keyup", function() {
                calculateLiveIncome();
            });

            $('#users_id').on('change', function() {
                updateExistingInvestorDetail();
                updateReviewSummary();
            });

            $('#categories_id, #types_id').on('change', function() {
                updateReviewSummary();
            });

            $('#name, #email, #phone').on('input', function() {
                updateReviewSummary();
            });

            $('#file').on('change', function(e) {
                if (e.target.files && e.target.files.length > 0) {
                    $('#file_display_name').html('📄 ' + e.target.files[0].name);
                }
            });

            // Initial check for existing selected investor
            if ($('#users_id').val()) {
                updateExistingInvestorDetail();
            }
        });
    </script>
@endpush