@extends('layouts.app')

@section('title')
    Buat Transfer Pendapatan Dividen
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <style>
        /* ==========================================================================
           FINTECH WIZARD THEME & STEPPER
           ========================================================================== */
        :root {
            --ft-primary: #1e40af;
            --ft-primary-light: #eff6ff;
            --ft-secondary: #0f172a;
            --ft-success: #059669;
            --ft-success-light: #ecfdf5;
            --ft-border: #e2e8f0;
            --ft-card-bg: #ffffff;
        }

        .wizard-card-wrapper {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.05), 0 0 0 1px rgba(226, 232, 240, 0.8);
            overflow: hidden;
        }

        .wizard-stepper-header {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 1.5rem 2.5rem;
            position: relative;
        }

        .stepper-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            gap: 1rem;
            max-width: 900px;
            margin: 0 auto;
        }

        .stepper-track-bg {
            position: absolute;
            top: 50%;
            left: 5%;
            right: 5%;
            height: 3px;
            background: #e2e8f0;
            transform: translateY(-50%);
            z-index: 1;
        }

        .stepper-track-fill {
            height: 100%;
            background: linear-gradient(90deg, #2563eb, #3b82f6);
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            width: 0%;
        }

        .step-item {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 12px;
            background: #ffffff;
            padding: 8px 18px;
            border-radius: 50px;
            border: 2px solid #e2e8f0;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 2px 6px rgba(0,0,0,0.02);
            user-select: none;
        }

        .step-item:hover {
            border-color: #cbd5e1;
            transform: translateY(-1px);
        }

        .step-badge {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #f1f5f9;
            color: #64748b;
            font-weight: 700;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.25s ease;
            flex-shrink: 0;
        }

        .step-text {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .step-title {
            font-size: 0.85rem;
            font-weight: 700;
            color: #475569;
            line-height: 1.2;
        }

        .step-subtitle {
            font-size: 0.72rem;
            color: #94a3b8;
            line-height: 1.2;
        }

        /* Active State */
        .step-item.active {
            border-color: #2563eb;
            background: #ffffff;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.15);
        }
        .step-item.active .step-badge {
            background: #2563eb;
            color: #ffffff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.18);
        }
        .step-item.active .step-title {
            color: #1e40af;
        }

        /* Completed State */
        .step-item.completed {
            border-color: #10b981;
            background: #ffffff;
        }
        .step-item.completed .step-badge {
            background: #10b981;
            color: #ffffff;
        }
        .step-item.completed .step-title {
            color: #065f46;
        }

        /* ==========================================================================
           FINTECH BALANCE SELECTION CARDS (STEP 1)
           ========================================================================== */
        .balance-selection-card {
            border: 2px solid #e2e8f0;
            border-radius: 18px;
            padding: 1.75rem;
            background: #ffffff;
            cursor: pointer;
            position: relative;
            transition: all 0.28s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            overflow: hidden;
        }

        .balance-selection-card:hover:not(.is-disabled) {
            border-color: #3b82f6;
            transform: translateY(-3px);
            box-shadow: 0 14px 28px -4px rgba(59, 130, 246, 0.12);
        }

        .balance-selection-card.is-selected {
            border-color: #2563eb;
            background: linear-gradient(180deg, #ffffff 0%, #f0f7ff 100%);
            box-shadow: 0 8px 25px -2px rgba(37, 99, 235, 0.2);
        }

        .balance-selection-card.is-selected::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #2563eb, #3b82f6);
        }

        .selected-corner-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #2563eb;
            color: #ffffff;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
        }

        .balance-selection-card.is-selected .selected-corner-badge {
            display: flex;
        }

        .balance-selection-card.is-disabled {
            opacity: 0.6;
            background: #f8fafc;
            border-color: #e2e8f0;
            cursor: not-allowed;
        }

        .channel-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .pulse-status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }
        .pulse-status-dot.online {
            background-color: #10b981;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse-glow 2s infinite;
        }
        .pulse-status-dot.offline {
            background-color: #ef4444;
        }

        @keyframes pulse-glow {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* ==========================================================================
           STEP 2 FORM & SUMMARY CARDS
           ========================================================================== */
        .source-summary-banner {
            background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 50%, #2563eb 100%);
            color: #ffffff;
            border-radius: 16px;
            padding: 1.25rem 1.75rem;
            box-shadow: 0 8px 20px -4px rgba(30, 64, 175, 0.25);
            position: relative;
            overflow: hidden;
        }

        .source-summary-banner::after {
            content: '';
            position: absolute;
            right: -30px;
            bottom: -30px;
            width: 140px;
            height: 140px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
        }

        .form-panel-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.75rem;
            height: 100%;
            box-shadow: 0 2px 6px rgba(15, 23, 42, 0.02);
            transition: border-color 0.2s ease;
        }

        .form-panel-card:hover {
            border-color: #cbd5e1;
        }

        .panel-header-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.95rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #f1f5f9;
            width: 100%;
        }

        .panel-icon-wrap {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .payout-calculation-box {
            background: #f0fdf4;
            border: 1.5px solid #86efac;
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            position: relative;
        }

        .num-tabular {
            font-variant-numeric: tabular-nums;
            letter-spacing: -0.01em;
        }

        /* Select2 Styling Enhancements */
        .select2-container--bootstrap-5 .select2-selection {
            border-radius: 10px !important;
            border: 1.5px solid #cbd5e1 !important;
            min-height: 46px !important;
            padding: 0.5rem 0.75rem !important;
            font-size: 0.9rem !important;
            transition: all 0.2s ease !important;
        }

        .select2-container--bootstrap-5.select2-container--focus .select2-selection,
        .select2-container--bootstrap-5.select2-container--open .select2-selection {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15) !important;
        }

        .select2-container--bootstrap-5 .select2-dropdown {
            border-radius: 12px !important;
            border: 1px solid #cbd5e1 !important;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.12) !important;
            overflow: hidden !important;
        }

        .select2-container--bootstrap-5 .select2-results__group {
            font-size: 0.78rem !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            color: #2563eb !important;
            background: #f8fafc !important;
            padding: 6px 12px !important;
            border-top: 1px solid #f1f5f9 !important;
        }

        .form-control-custom {
            border-radius: 10px;
            border: 1.5px solid #cbd5e1;
            padding: 0.6rem 0.9rem;
            font-size: 0.92rem;
            transition: all 0.2s ease;
        }

        .form-control-custom:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        /* ==========================================================================
           STEP 3 CONFIRMATION RECEIPT VOUCHER
           ========================================================================== */
        .voucher-receipt-card {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 20px;
            box-shadow: 0 14px 35px -5px rgba(15, 23, 42, 0.08);
            overflow: hidden;
            position: relative;
        }

        .voucher-top-banner {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #ffffff;
            padding: 1.75rem 2rem;
            position: relative;
        }

        .voucher-top-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #10b981, #3b82f6);
        }

        .voucher-body {
            padding: 2.25rem 2rem;
        }

        .voucher-item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.85rem 0;
            border-bottom: 1px dashed #e2e8f0;
            font-size: 0.92rem;
        }

        .voucher-item-row.net-highlight {
            border-top: 2px solid #0f172a;
            border-bottom: none;
            padding-top: 1.25rem;
            margin-top: 0.5rem;
        }

        @media (max-width: 768px) {
            .stepper-track-bg { display: none; }
            .step-subtitle { display: none; }
            .step-item { padding: 6px 12px; }
            .wizard-stepper-header { padding: 1rem 1.25rem; }
        }
    </style>
@endpush

@section('content')
@php
    $balanceData = $financeBalance['data'] ?? [];
    $channelStatus = $balanceData['channel_status'] ?? ['manual' => false, 'xendit' => false];
    $manualBalance = (float) ($balanceData['balance_manual'] ?? 0);
    $xenditBalance = (float) ($balanceData['balance_xendit'] ?? 0);
    $defaultAdminFee = (float) ($settings->admin_fee ?? 0);
@endphp

<div>
    <!-- Top Action Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1 small">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('transfer.index') }}" class="text-muted text-decoration-none">Transfer Pendapatan</a></li>
                    <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">Buat Transfer Baru</li>
                </ol>
            </nav>
            <h2 class="text-dark fw-bold mb-0">Penyaluran Bagi Hasil Dividen</h2>
        </div>
        <a href="{{ route('transfer.index') }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1.5 shadow-none rounded-3 px-3 py-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 14l-4 -4l4 -4" /><path d="M5 10h11a4 4 0 1 1 0 8h-1" /></svg>
            <span>Daftar Riwayat Transfer</span>
        </a>
    </div>

    <!-- Error Alert Display (Only rendered on server validation error) -->
    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger d-flex align-items-start gap-3 border-0 shadow-sm rounded-3 mb-4" role="alert" style="border-left: 5px solid #dc2626 !important; background-color: #fef2f2;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 mt-0.5"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M12 17h.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>
            <div>
                <strong class="text-danger fs-5">Terdapat Kesalahan Validasi:</strong>
                <ul class="mb-0 ps-3 mt-1 small text-danger">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Main Wizard Card -->
    <div class="wizard-card-wrapper mb-5">
        <!-- Stepper Navigation Header -->
        <div class="wizard-stepper-header">
            <div class="stepper-nav">
                <div class="stepper-track-bg">
                    <div class="stepper-track-fill" id="stepper-progress-fill"></div>
                </div>

                <!-- Step 1 Nav -->
                <div class="step-item active" id="step-nav-1" onclick="handleStepNavClick(1)">
                    <span class="step-badge" id="badge-step-1">1</span>
                    <div class="step-text">
                        <span class="step-title">1. Sumber Saldo</span>
                        <span class="step-subtitle">Finance CIO Channel</span>
                    </div>
                </div>

                <!-- Step 2 Nav -->
                <div class="step-item" id="step-nav-2" onclick="handleStepNavClick(2)">
                    <span class="step-badge" id="badge-step-2">2</span>
                    <div class="step-text">
                        <span class="step-title">2. Detail Investor</span>
                        <span class="step-subtitle">Penerima & Nominal</span>
                    </div>
                </div>

                <!-- Step 3 Nav -->
                <div class="step-item" id="step-nav-3" onclick="handleStepNavClick(3)">
                    <span class="step-badge" id="badge-step-3">3</span>
                    <div class="step-text">
                        <span class="step-title">3. Konfirmasi</span>
                        <span class="step-subtitle">Review & Eksekusi</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Transfer -->
        <form action="{{ route('transfer.store') }}" method="POST" id="form-wizard-transfer">
            @csrf

            <div class="p-4 p-md-5">
                {{-- =========================================================
                     STEP 1: PILIHAN SALURAN SALDO
                ========================================================== --}}
                <div class="wizard-pane" id="wizard-pane-1">
                    <div class="text-center mb-5" style="max-width: 650px; margin: 0 auto;">
                        <span class="badge bg-primary-subtle text-primary px-3 py-1.5 rounded-pill fw-bold mb-2">LANGKAH 1 DARI 3</span>
                        <h3 class="fw-bold text-dark mb-1">Pilih Saluran Saldo Pendanaan</h3>
                        <p class="text-muted small">Tentukan sumber saldo Central Finance CIO yang akan digunakan untuk mendanai pencairan dividen ini.</p>
                    </div>

                    <div class="row g-4 justify-content-center mb-5">
                        <!-- Opsi 1: Saldo Kas / Manual -->
                        @php
                            $manualActive = !empty($channelStatus['manual']);
                            $manualSelected = old('balance_type', $manualActive ? 'manual' : ($channelStatus['xendit'] ?? false ? 'xendit' : '')) === 'manual';
                        @endphp
                        <div class="col-md-6 col-lg-5">
                            <div class="balance-selection-card {{ $manualActive ? '' : 'is-disabled' }} {{ $manualSelected && $manualActive ? 'is-selected' : '' }}" 
                                 id="card-balance-manual" 
                                 data-type="manual" 
                                 data-active="{{ $manualActive ? '1' : '0' }}" 
                                 data-balance="{{ $manualBalance }}">
                                
                                <div class="selected-corner-badge">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                </div>

                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="channel-icon-box" style="background: #eff6ff; color: #2563eb;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" /></svg>
                                            </div>
                                            <div>
                                                <h5 class="fw-bold text-dark mb-0">Saldo Kas / Manual</h5>
                                                <span class="text-muted small">Pencatatan Kas & Mutasi Manual</span>
                                            </div>
                                        </div>
                                        @if($manualActive)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.72rem;">
                                                <span class="pulse-status-dot online"></span> Aktif
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.72rem;">
                                                <span class="pulse-status-dot offline"></span> Nonaktif
                                            </span>
                                        @endif
                                    </div>

                                    <div class="text-muted small mb-1">Sisa Saldo Tersedia:</div>
                                    <div class="fs-2 fw-bold text-dark mb-3 num-tabular">
                                        Rp {{ number_format($manualBalance, 0, ',', '.') }}
                                    </div>
                                </div>

                                <div>
                                    @if(!$manualActive)
                                        <div class="alert alert-warning py-2 px-3 small mb-0 rounded-3 border-0 d-flex align-items-center gap-2" style="background-color: #fffbeb; color: #92400e; font-size: 0.78rem;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>
                                            <span>Saluran dinonaktifkan oleh Admin Finance.</span>
                                        </div>
                                    @else
                                        <div class="small text-muted d-flex align-items-center gap-1.5 pt-2 border-top">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            <span>Pemotongan instan dari Saldo Kas Finance CIO</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Opsi 2: Saldo Xendit (Auto-Payout API) -->
                        @php
                            $xenditActive = !empty($channelStatus['xendit']);
                            $xenditSelected = old('balance_type', !$manualActive && $xenditActive ? 'xendit' : '') === 'xendit';
                        @endphp
                        <div class="col-md-6 col-lg-5">
                            <div class="balance-selection-card {{ $xenditActive ? '' : 'is-disabled' }} {{ $xenditSelected && $xenditActive ? 'is-selected' : '' }}" 
                                 id="card-balance-xendit" 
                                 data-type="xendit" 
                                 data-active="{{ $xenditActive ? '1' : '0' }}" 
                                 data-balance="{{ $xenditBalance }}">
                                
                                <div class="selected-corner-badge">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                </div>

                                <div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="channel-icon-box" style="background: #ecfdf5; color: #059669;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 5m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M3 10l18 0" /><path d="M7 15l.01 0" /><path d="M11 15l2 0" /></svg>
                                            </div>
                                            <div>
                                                <h5 class="fw-bold text-dark mb-0">Saldo Xendit API</h5>
                                                <span class="text-muted small">Auto-Disbursement Real-Time</span>
                                            </div>
                                        </div>
                                        @if($xenditActive)
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.72rem;">
                                                <span class="pulse-status-dot online"></span> Aktif
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.72rem;">
                                                <span class="pulse-status-dot offline"></span> Nonaktif
                                            </span>
                                        @endif
                                    </div>

                                    <div class="text-muted small mb-1">Sisa Saldo Tersedia:</div>
                                    <div class="fs-2 fw-bold text-success mb-3 num-tabular">
                                        Rp {{ number_format($xenditBalance, 0, ',', '.') }}
                                    </div>
                                </div>

                                <div>
                                    @if(!$xenditActive)
                                        <div class="alert alert-warning py-2 px-3 small mb-0 rounded-3 border-0 d-flex align-items-center gap-2" style="background-color: #fffbeb; color: #92400e; font-size: 0.78rem;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>
                                            <span>Saluran dinonaktifkan oleh Admin Finance.</span>
                                        </div>
                                    @else
                                        <div class="small text-muted d-flex align-items-center gap-1.5 pt-2 border-top">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                            <span>Disbursement live ke rekening + Proteksi auto-refund</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="balance_type" id="balance_type" value="{{ old('balance_type', $manualActive ? 'manual' : ($xenditActive ? 'xendit' : '')) }}">

                    <div class="d-flex justify-content-end pt-4 border-top">
                        <button type="button" class="btn btn-primary btn-lg px-4 d-inline-flex align-items-center gap-2 rounded-3 fw-bold" id="btn-goto-step-2">
                            <span>Lanjut ke Detail Penerima</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M13 18l6 -6" /><path d="M13 6l6 6" /></svg>
                        </button>
                    </div>
                </div>

                {{-- =========================================================
                     STEP 2: DETAIL INVESTOR & KALKULATOR DIVIDEN
                ========================================================== --}}
                <div class="wizard-pane d-none" id="wizard-pane-2">
                    <!-- Selected Balance Banner -->
                    <div class="source-summary-banner d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-2.5 rounded-3 bg-white text-primary shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 5m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M3 10l18 0" /><path d="M7 15l.01 0" /><path d="M11 15l2 0" /></svg>
                            </div>
                            <div>
                                <span class="text-white-50 small d-block">Sumber Saldo Pendanaan Terpilih:</span>
                                <strong class="text-white fs-4" id="display-selected-balance-name">Saldo Kas / Manual</strong>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="text-end">
                                <span class="text-white-50 small d-block">Sisa Saldo Tersedia:</span>
                                <span class="fw-bold text-white fs-4 num-tabular" id="display-selected-balance-amount">Rp 0</span>
                            </div>
                            <button type="button" class="btn btn-light btn-sm text-primary fw-bold rounded-pill px-3 py-1.5 shadow-sm" id="btn-change-balance">Ganti Saldo</button>
                        </div>
                    </div>

                    <!-- Insufficient Balance Alert (Hidden by default, shown ONLY when gross > available) -->
                    <div id="balance-warning-alert" class="alert alert-danger align-items-start gap-2.5 mb-4 border-0 rounded-3 shadow-sm d-none" style="border-left: 5px solid #dc2626 !important; background-color: #fef2f2; color: #991b1b;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 mt-0.5"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>
                        <div id="balance-warning-text" class="fw-semibold"></div>
                    </div>

                    <!-- Form Layout 2 Columns -->
                    <div class="row g-4 mb-4">
                        <!-- Left Panel: Data Investor & Rekening Bank -->
                        <div class="col-lg-6">
                            <div class="form-panel-card">
                                <div class="panel-header-badge">
                                    <div class="panel-icon-wrap bg-primary-subtle text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                                    </div>
                                    <span>Informasi Penerima Dividen</span>
                                </div>

                                <!-- Pilih Investor -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small mb-1" for="investors_id">
                                        Investor Penerima <span class="text-danger">*</span>
                                    </label>
                                    <select name="investors_id" id="investors_id" class="form-select" required>
                                        <option value="">-- Cari Nama, Email, atau No HP Investor --</option>
                                        @foreach ($investors as $ivt)
                                            @php
                                                $investorRecords = $ivt->investors ?? collect([$ivt->investor])->filter();
                                                $totalIncome = (float) $investorRecords->sum('monthly_income');
                                                $packageCount = $investorRecords->count();
                                                $firstInv = $investorRecords->first();
                                                $bankName = $firstInv->party_1_bank ?? '';
                                                $accNumber = $firstInv->party_1_account_number ?? '';
                                                $accHolder = $firstInv->party_1_name ?? $ivt->name;
                                            @endphp
                                            <option value="{{ $ivt->id }}" 
                                                    data-income="{{ $totalIncome }}"
                                                    data-phone="{{ $ivt->phone ?? '' }}"
                                                    data-email="{{ $ivt->email ?? '' }}"
                                                    data-name="{{ $ivt->name }}"
                                                    data-bank="{{ $bankName }}"
                                                    data-account="{{ $accNumber }}"
                                                    data-holder="{{ $accHolder }}"
                                                    {{ old('investors_id') == $ivt->id ? 'selected' : '' }}>
                                                {{ $ivt->name }} (Rp {{ number_format($totalIncome, 0, ',', '.') }}/bln &bull; {{ $packageCount }} Paket){{ $ivt->phone ? ' - ' . $ivt->phone : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Bank / Saluran Transfer Resmi Xendit -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="form-label fw-bold text-dark small mb-0" for="payment_method">
                                            Bank / Saluran Transfer Xendit <span class="text-danger">*</span>
                                        </label>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill" style="font-size: 0.7rem;">
                                            Disbursement Supported
                                        </span>
                                    </div>
                                    <select name="payment_method" id="payment_method" class="form-select" required>
                                        <option value="">-- Pilih Bank / Saluran Transfer --</option>
                                        @if(isset($bankGroups) && is_array($bankGroups))
                                            @foreach ($bankGroups as $groupTitle => $banksInGroup)
                                                <optgroup label="{{ $groupTitle }}">
                                                    @foreach ($banksInGroup as $bankItem)
                                                        @php
                                                            $code = $bankItem['code'] ?? '';
                                                            $name = $bankItem['name'] ?? '';
                                                            $isSelected = old('payment_method') == $code || old('payment_method') == $name;
                                                        @endphp
                                                        <option value="{{ $code }}" 
                                                                data-name="{{ $name }}" 
                                                                data-code="{{ $code }}"
                                                                {{ $isSelected ? 'selected' : '' }}>
                                                            [{{ $code }}] {{ $name }}
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        @else
                                            <option value="BCA">BCA - Bank Central Asia</option>
                                            <option value="MANDIRI">MANDIRI - Bank Mandiri</option>
                                            <option value="BRI">BRI - Bank Rakyat Indonesia</option>
                                            <option value="BNI">BNI - Bank Negara Indonesia</option>
                                            <option value="BSI">BSI - Bank Syariah Indonesia</option>
                                        @endif
                                    </select>
                                </div>

                                <!-- Nomor Rekening Tujuan -->
                                <div>
                                    <label class="form-label fw-bold text-dark small mb-1" for="account_number">
                                        Nomor Rekening / No. HP E-Wallet
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="account_number" id="account_number" class="form-control form-control-custom font-monospace" placeholder="Contoh: 1234567890" value="{{ old('account_number') }}">
                                    </div>
                                    <div class="form-text text-muted small" style="font-size: 0.78rem;">
                                        Pastikan nomor rekening sesuai dengan identitas investor penerima.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Panel: Nominal Dividen & Kalkulator Bersih -->
                        <div class="col-lg-6">
                            <div class="form-panel-card">
                                <div class="panel-header-badge">
                                    <div class="panel-icon-wrap bg-success-subtle text-success">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" /></svg>
                                    </div>
                                    <span>Nominal Dividen & Jadwal</span>
                                </div>

                                <!-- Nominal Dividen Kotor (Gross) -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="form-label fw-bold text-dark small mb-0" for="amount">
                                            Nominal Dividen Kotor (Gross) <span class="text-danger">*</span>
                                        </label>
                                        <button type="button" class="btn btn-link btn-sm text-primary p-0 text-decoration-none fw-bold d-none" id="btn-use-estimate" style="font-size: 0.78rem;">
                                            ⚡ Gunakan Estimasi Dividen
                                        </button>
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text fw-bold text-primary bg-light border-end-0 px-3">Rp</span>
                                        <input type="text" name="amount" id="amount" class="form-control form-control-custom font-monospace fw-bold fs-3 text-dark" placeholder="0" value="{{ old('amount') }}" required autocomplete="off">
                                    </div>
                                    <div id="income-hint" class="small text-muted mt-1.5 d-none">
                                        <span>💡 Estimasi bagi hasil bulanan: </span>
                                        <strong id="hint-amount" class="text-primary num-tabular">Rp 0</strong>
                                    </div>
                                </div>

                                <!-- Net Payout Breakdown Box -->
                                <div class="payout-calculation-box mb-3">
                                    <div class="d-flex justify-content-between align-items-center text-muted small mb-1">
                                        <span>Potongan Biaya Admin Sistem:</span>
                                        <span class="fw-semibold text-danger num-tabular" id="display-admin-fee-text">
                                            - Rp {{ number_format($defaultAdminFee, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top border-success-subtle">
                                        <div>
                                            <div class="text-success small fw-bold">Nominal Bersih Diterima (Net):</div>
                                            <div class="fs-2 fw-bold text-success num-tabular" id="display-net-amount">Rp 0</div>
                                        </div>
                                        <span class="badge bg-success text-white px-3 py-1.5 rounded-pill shadow-sm fw-bold">Net Payout</span>
                                    </div>
                                </div>

                                <div class="row g-2">
                                    <div class="col-sm-6">
                                        <label class="form-label fw-bold text-dark small mb-1" for="transfer_date">
                                            Tanggal Transfer <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" name="transfer_date" id="transfer_date" class="form-control form-control-custom form-control-sm" value="{{ old('transfer_date', date('Y-m-d')) }}" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label fw-bold text-dark small mb-1" for="notes">
                                            Periode / Catatan
                                        </label>
                                        <input type="text" name="notes" id="notes" class="form-control form-control-custom form-control-sm" placeholder="Contoh: Bagi hasil {{ now()->translatedFormat('F Y') }}" value="{{ old('notes') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between pt-4 border-top">
                        <button type="button" class="btn btn-outline-secondary btn-lg px-4 rounded-3" id="btn-back-to-step-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                            Kembali
                        </button>
                        <button type="button" class="btn btn-primary btn-lg px-4 d-inline-flex align-items-center gap-2 rounded-3 fw-bold" id="btn-goto-step-3">
                            <span>Lanjut ke Ringkasan Voucher</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M13 18l6 -6" /><path d="M13 6l6 6" /></svg>
                        </button>
                    </div>
                </div>

                {{-- =========================================================
                     STEP 3: RINGKASAN DIGITAL VOUCHER & EKSEKUSI
                ========================================================== --}}
                <div class="wizard-pane d-none" id="wizard-pane-3">
                    <div class="text-center mb-4" style="max-width: 650px; margin: 0 auto;">
                        <span class="badge bg-success-subtle text-success px-3 py-1.5 rounded-pill fw-bold mb-2">LANGKAH TERAKHIR</span>
                        <h3 class="fw-bold text-dark mb-1">Konfirmasi & Eksekusi Penyaluran</h3>
                        <p class="text-muted small">Periksa kembali detail transaksi transfer dividen sebelum dieksekusi secara live.</p>
                    </div>

                    <div class="row justify-content-center mb-4">
                        <div class="col-lg-8">
                            <!-- Digital Voucher Card -->
                            <div class="voucher-receipt-card">
                                <div class="voucher-top-banner d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="badge bg-success text-white px-2.5 py-0.5 rounded-pill" style="font-size: 0.7rem;">LIVE TRANSACTION</span>
                                            <span class="text-white-50 small">CIO Network Solution &bull; Investor System</span>
                                        </div>
                                        <h4 class="fw-bold text-white mb-0">VOUCHER DISTRIBUSI BAGI HASIL</h4>
                                    </div>
                                    <span class="badge bg-white text-primary fw-bold px-3 py-1.5 rounded-pill shadow-sm" id="sum-badge-type">SALDO KAS</span>
                                </div>

                                <div class="voucher-body">
                                    <!-- Recipient & Bank Details -->
                                    <div class="row g-3 mb-4 pb-3 border-bottom">
                                        <div class="col-sm-6">
                                            <div class="text-muted small">Investor Penerima:</div>
                                            <div class="fw-bold text-dark fs-5" id="sum-investor-name">-</div>
                                            <div class="text-muted small" id="sum-investor-contact">-</div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="text-muted small">Tujuan Transfer / Bank:</div>
                                            <div class="fw-bold text-dark fs-5" id="sum-bank-name">-</div>
                                            <div class="font-monospace text-primary fw-bold fs-6" id="sum-bank-account">-</div>
                                        </div>
                                    </div>

                                    <!-- Transaction Details -->
                                    <div class="voucher-item-row">
                                        <span class="text-muted">Tanggal Efektif Transfer:</span>
                                        <span class="fw-bold text-dark" id="sum-transfer-date">-</span>
                                    </div>
                                    <div class="voucher-item-row">
                                        <span class="text-muted">Periode / Catatan:</span>
                                        <span class="fw-medium text-dark text-end" id="sum-notes">-</span>
                                    </div>
                                    <div class="voucher-item-row">
                                        <span class="text-muted">Nominal Dividen Kotor (Gross):</span>
                                        <span class="fw-bold text-dark num-tabular fs-5" id="sum-gross-amount">Rp 0</span>
                                    </div>
                                    <div class="voucher-item-row">
                                        <span class="text-muted">Potongan Biaya Admin Sistem:</span>
                                        <span class="fw-bold text-danger num-tabular" id="sum-admin-fee">- Rp 0</span>
                                    </div>

                                    <!-- Net Amount Highlight -->
                                    <div class="voucher-item-row net-highlight">
                                        <div>
                                            <div class="fw-bold text-dark fs-4">Total Bersih Diterima (Net):</div>
                                            <span class="text-muted small">Dana yang masuk ke rekening investor</span>
                                        </div>
                                        <span class="fw-bold text-success num-tabular fs-2" id="sum-net-amount">Rp 0</span>
                                    </div>

                                    <!-- Remaining Balance Indicator -->
                                    <div class="d-flex justify-content-between align-items-center mt-3 p-3 rounded-3" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                                        <span class="text-muted small">Estimasi Sisa Saldo <strong id="sum-balance-source-label">Kas</strong> Setelah Transfer:</span>
                                        <span class="fw-bold text-primary num-tabular" id="sum-remaining-balance">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between pt-4 border-top">
                        <button type="button" class="btn btn-outline-secondary btn-lg px-4 rounded-3" id="btn-back-to-step-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                            Kembali ke Detail
                        </button>
                        <button type="submit" class="btn btn-success btn-lg px-5 py-2.5 fw-bold d-inline-flex align-items-center gap-2 rounded-3 shadow-sm" id="btn-submit-transfer">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <span>Eksekusi & Kirim Transfer</span>
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            let currentStep = 1;
            let manualBalance = {{ $manualBalance }};
            let xenditBalance = {{ $xenditBalance }};
            let defaultAdminFee = {{ $defaultAdminFee }};

            // Format numbers to Indonesian Rupiah representation
            function formatRupiah(num) {
                if (num === null || num === undefined || num === '') return '0';
                let numStr = num.toString().replace(/\D/g, '');
                if (!numStr) return '0';
                return new Intl.NumberFormat('id-ID').format(numStr);
            }

            // Parse currency string to float
            function parseRupiah(str) {
                if (!str) return 0;
                return parseFloat(str.toString().replace(/\D/g, '')) || 0;
            }

            // Initialize Select2 with Bootstrap 5 Theme
            $('#investors_id').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '-- Cari Nama, Email, atau No HP Investor --',
                allowClear: true
            });

            $('#payment_method').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '-- Pilih Bank / Saluran Transfer --',
                allowClear: true
            });

            // Card Balance Selector Click (Step 1)
            $('.balance-selection-card').on('click', function() {
                if ($(this).hasClass('is-disabled')) return;
                $('.balance-selection-card').removeClass('is-selected');

                $(this).addClass('is-selected');

                let type = $(this).data('type');
                $('#balance_type').val(type);
            });

            function getSelectedBalance() {
                let type = $('#balance_type').val() || 'manual';
                let amount = (type === 'manual') ? manualBalance : xenditBalance;
                let name = (type === 'manual') ? 'Saldo Kas / Manual' : 'Saldo Xendit API';
                return { type: type, amount: amount, name: name };
            }

            // Real-time calculation updater (Safe from empty red alert box bug)
            function updateCalculations() {
                let rawGross = $('#amount').val();
                let gross = parseRupiah(rawGross);
                let adminFee = defaultAdminFee;
                let net = Math.max(0, gross - adminFee);
                let selectedBal = getSelectedBalance();

                $('#display-net-amount').text('Rp ' + formatRupiah(net));

                // ONLY show warning IF user entered an amount greater than available balance
                if (gross > 0 && gross > selectedBal.amount) {
                    let shortage = gross - selectedBal.amount;
                    $('#balance-warning-text').html('<strong>Peringatan Saldo Tidak Mencukupi:</strong> Nominal transfer dividen (Rp ' + formatRupiah(gross) + ') melebihi sisa ' + selectedBal.name + ' yang tersedia (Rp ' + formatRupiah(selectedBal.amount) + '). Kekurangan dana: <strong>Rp ' + formatRupiah(shortage) + '</strong>.');
                    $('#balance-warning-alert').removeClass('d-none').addClass('d-flex');
                    $('#btn-goto-step-3').prop('disabled', true);
                } else {
                    $('#balance-warning-alert').removeClass('d-flex').addClass('d-none');
                    $('#btn-goto-step-3').prop('disabled', false);
                }
            }

            // Currency input formatter
            $('#amount').on('input', function() {
                let raw = $(this).val().replace(/\D/g, '');
                $(this).val(raw ? formatRupiah(raw) : '');
                updateCalculations();
            });

            // Match investor bank string with Xendit bank codes
            function matchBankOption(bankStr) {
                if (!bankStr) return null;
                let clean = bankStr.toUpperCase().trim();
                
                // 1. Direct match by option value
                let directMatch = $(`#payment_method option[value="${clean}"]`);
                if (directMatch.length) return clean;

                // 2. Fuzzy search through option data-code or text
                let foundVal = null;
                $('#payment_method option').each(function() {
                    let val = $(this).val();
                    let text = $(this).text().toUpperCase();
                    let code = ($(this).data('code') || '').toString().toUpperCase();
                    let name = ($(this).data('name') || '').toString().toUpperCase();

                    if (val && (clean === code || text.includes(clean) || clean.includes(code) || (name && name.includes(clean)))) {
                        foundVal = val;
                        return false; // break loop
                    }
                });
                return foundVal;
            }

            // Investor change handler with intelligent auto-fill
            $('#investors_id').on('change', function() {
                let opt = $(this).find('option:selected');
                let income = opt.data('income');
                let bank = opt.data('bank');
                let account = opt.data('account');

                if (opt.val()) {
                    if (income !== undefined && income !== null && income !== '' && income > 0) {
                        let fmtIncome = formatRupiah(income);
                        $('#hint-amount').text('Rp ' + fmtIncome);
                        $('#income-hint').removeClass('d-none');
                        $('#btn-use-estimate').removeClass('d-none');

                        // If amount is empty or 0, auto-populate with monthly estimate
                        if (!$('#amount').val() || $('#amount').val() === '0') {
                            $('#amount').val(fmtIncome);
                        }
                    } else {
                        $('#income-hint').addClass('d-none');
                        $('#btn-use-estimate').addClass('d-none');
                    }

                    if (bank) {
                        let matchedCode = matchBankOption(bank);
                        if (matchedCode) {
                            $('#payment_method').val(matchedCode).trigger('change');
                        }
                    }
                    if (account) {
                        $('#account_number').val(account);
                    }
                } else {
                    $('#income-hint').addClass('d-none');
                    $('#btn-use-estimate').addClass('d-none');
                }
                updateCalculations();
            });

            // Use estimated dividend button click
            $('#btn-use-estimate').on('click', function() {
                let opt = $('#investors_id').find('option:selected');
                let income = opt.data('income');
                if (income !== undefined && income !== null) {
                    $('#amount').val(formatRupiah(income));
                    updateCalculations();
                }
            });

            // Only digits allowed for bank account number
            $('#account_number').on('input', function() {
                let clean = $(this).val().replace(/\D/g, '');
                $(this).val(clean);
            });

            // Wizard Stepper Switcher
            window.handleStepNavClick = function(targetStep) {
                if (targetStep < currentStep) {
                    goToStep(targetStep);
                } else if (targetStep === 2 && currentStep === 1) {
                    $('#btn-goto-step-2').trigger('click');
                } else if (targetStep === 3 && currentStep === 2) {
                    $('#btn-goto-step-3').trigger('click');
                }
            };

            function goToStep(step) {
                $('.wizard-pane').addClass('d-none');
                $('.step-item').removeClass('active completed');

                // Update Progress bar width
                let progressPercent = (step === 1) ? 0 : ((step === 2) ? 50 : 100);
                $('#stepper-progress-fill').css('width', progressPercent + '%');

                for (let i = 1; i < step; i++) {
                    $('#step-nav-' + i).addClass('completed');
                    $('#badge-step-' + i).html('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>');
                }

                $('#step-nav-' + step).addClass('active');
                $('#badge-step-' + step).text(step);
                $('#wizard-pane-' + step).removeClass('d-none');
                currentStep = step;
                window.scrollTo({ top: 120, behavior: 'smooth' });
            }

            // Step 1 -> Step 2
            $('#btn-goto-step-2').on('click', function() {
                let selectedBal = getSelectedBalance();
                let selectedCard = $('.balance-selection-card.is-selected');

                if (!selectedCard.length || selectedCard.hasClass('is-disabled')) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pilih Sumber Saldo',
                        text: 'Silakan pilih saluran saldo yang aktif terlebih dahulu sebelum melanjutkan.',
                        confirmButtonColor: '#2563eb'
                    });
                    return;
                }

                $('#display-selected-balance-name').text(selectedBal.name);
                $('#display-selected-balance-amount').text('Rp ' + formatRupiah(selectedBal.amount));
                updateCalculations();
                goToStep(2);
            });

            $('#btn-change-balance, #btn-back-to-step-1').on('click', function() {
                goToStep(1);
            });

            // Step 2 -> Step 3
            $('#btn-goto-step-3').on('click', function() {
                let investorId = $('#investors_id').val();
                let gross = parseRupiah($('#amount').val());
                let adminFee = defaultAdminFee;
                let net = Math.max(0, gross - adminFee);
                let paymentMethod = $('#payment_method').val();
                let paymentMethodText = $('#payment_method').find('option:selected').text();
                let transferDate = $('#transfer_date').val();
                let selectedBal = getSelectedBalance();

                if (!investorId) {
                    Swal.fire({ icon: 'warning', title: 'Investor Belum Dipilih', text: 'Silakan tentukan investor penerima dividen.', confirmButtonColor: '#2563eb' });
                    return;
                }
                if (gross <= 0) {
                    Swal.fire({ icon: 'warning', title: 'Nominal Tidak Valid', text: 'Nominal dividen kotor harus lebih besar dari Rp 0.', confirmButtonColor: '#2563eb' });
                    return;
                }
                if (gross > selectedBal.amount) {
                    Swal.fire({ icon: 'error', title: 'Saldo Tidak Cukup', text: 'Nominal dividen kotor melebihi sisa ' + selectedBal.name + ' yang tersedia.', confirmButtonColor: '#dc2626' });
                    return;
                }
                if (!paymentMethod) {
                    Swal.fire({ icon: 'warning', title: 'Pilih Bank / Saluran Transfer', text: 'Silakan tentukan saluran bank atau e-wallet tujuan transfer penerima.', confirmButtonColor: '#2563eb' });
                    return;
                }

                // Populate Step 3 Digital Voucher Receipt
                let investorOpt = $('#investors_id').find('option:selected');
                let accountNum = $('#account_number').val() || '-';
                let remaining = Math.max(0, selectedBal.amount - gross);
                let notes = $('#notes').val() || 'Distribusi bagi hasil dividen reguler';
                let investorPhone = investorOpt.data('phone') ? ('Telp: ' + investorOpt.data('phone')) : '';
                let investorEmail = investorOpt.data('email') ? (' &bull; ' + investorOpt.data('email')) : '';

                $('#sum-badge-type').text(selectedBal.type.toUpperCase() + ' BALANCE');
                $('#sum-investor-name').text(investorOpt.data('name') || investorOpt.text());
                $('#sum-investor-contact').html(investorPhone + investorEmail || 'Data kontak tidak tersedia');
                $('#sum-bank-name').text(paymentMethodText.replace(/^\[.*?\]\s*/, ''));
                $('#sum-bank-account').text(accountNum);
                $('#sum-transfer-date').text(transferDate);
                $('#sum-notes').text(notes);
                $('#sum-gross-amount').text('Rp ' + formatRupiah(gross));
                $('#sum-admin-fee').text('- Rp ' + formatRupiah(adminFee));
                $('#sum-net-amount').text('Rp ' + formatRupiah(net));
                $('#sum-balance-source-label').text(selectedBal.type === 'manual' ? 'Kas' : 'Xendit');
                $('#sum-remaining-balance').text('Rp ' + formatRupiah(remaining));

                goToStep(3);
            });

            $('#btn-back-to-step-2').on('click', function() {
                goToStep(2);
            });

            // Prevent double-submit with SweetAlert confirmation & loading spinner
            $('#form-wizard-transfer').on('submit', function(e) {
                e.preventDefault();
                let form = this;
                let grossStr = $('#sum-gross-amount').text();
                let netStr = $('#sum-net-amount').text();
                let investorName = $('#sum-investor-name').text();
                let source = getSelectedBalance().name;

                Swal.fire({
                    title: 'Konfirmasi Eksekusi Transfer?',
                    html: `Anda akan mengeksekusi transfer dividen sebesar <strong>${netStr}</strong> (Gross: ${grossStr}) kepada <strong>${investorName}</strong> menggunakan <strong>${source}</strong>.<br><br><span class="text-muted small">Saldo Central Finance CIO akan langsung dipotong secara real-time.</span>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Eksekusi Sekarang!',
                    cancelButtonText: 'Batal / Periksa Kembali'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let btn = $('#btn-submit-transfer');
                        btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status"></span> Memproses Transfer...');
                        form.submit();
                    }
                });
            });

            // Initial calculation
            updateCalculations();
        });
    </script>
@endpush