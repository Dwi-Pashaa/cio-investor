@extends('layouts.app')

@section('title')
    Dashboard
@endsection

@push('css')
    <style>
        /* Dashboard Executive Fintech Theme */
        .dash-header-card {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            position: relative;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .dash-header-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #2563eb 0%, #3b82f6 100%);
        }

        /* Unified Fintech Metric Card */
        .fintech-stat-card {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            padding: 1.25rem 1.35rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
            height: 100%;
        }
        .fintech-stat-card:hover {
            transform: translateY(-2px);
            border-color: #cbd5e1;
            box-shadow: 0 8px 20px -4px rgba(15, 23, 42, 0.08);
        }
        .fintech-stat-card.card-manual {
            border-top: 3px solid #2563eb;
        }
        .fintech-stat-card.card-xendit {
            border-top: 3px solid #10b981;
        }
        .fintech-stat-card.card-total {
            border-top: 3px solid #7c3aed;
            background: linear-gradient(180deg, #ffffff 0%, #faf8ff 100%);
        }
        .fintech-stat-card.card-investor {
            border-top: 3px solid #0284c7;
        }
        .fintech-stat-card.card-funds {
            border-top: 3px solid #059669;
        }
        .fintech-stat-card.card-income {
            border-top: 3px solid #d97706;
        }

        .fintech-icon-box {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* Pulse Status Dots */
        .status-dot-pulse {
            position: relative;
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 5px;
        }
        .status-dot-pulse.active {
            background-color: #10b981;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse-green 2s infinite;
        }
        .status-dot-pulse.inactive {
            background-color: #ef4444;
        }

        @keyframes pulse-green {
            0% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }
            70% {
                box-shadow: 0 0 0 5px rgba(16, 185, 129, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        /* Custom Chart Tooltip */
        .custom-chart-tooltip {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            font-size: 13px;
        }
        .custom-chart-tooltip .tooltip-title {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 6px;
            font-size: 14px;
        }

        /* Soft Badges */
        .badge-soft-success {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .badge-soft-danger {
            background-color: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .badge-soft-primary {
            background-color: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }
        .badge-soft-purple {
            background-color: #f5f3ff;
            color: #5b21b6;
            border: 1px solid #ddd6fe;
        }
        .badge-soft-amber {
            background-color: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .num-currency {
            font-variant-numeric: tabular-nums;
            font-feature-settings: "tnum";
            letter-spacing: -0.02em;
        }

        .section-header-pill {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #475569;
        }
    </style>
@endpush

@section('content')
    @php
        $totalNominalPendapatan = $grafikPendapatan->sum(function($item) {
            return $item->investors->sum('monthly_income');
        });
        $balanceData = $financeBalance['data'] ?? [];
        $channelStatus = $balanceData['channel_status'] ?? ['manual' => false, 'xendit' => false];
        $isApiConnected = $financeBalance['is_connected'] ?? false;
    @endphp

    <!-- 1. Executive Welcome & Quick Action Card -->
    <div class="dash-header-card p-4 mb-4 shadow-sm">
        <div class="row align-items-center g-3">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge badge-soft-primary px-2.5 py-1 rounded-pill fw-bold" style="font-size: 0.72rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                        {{ Auth::user()->roles->pluck('name')->implode(', ') ?: 'Administrator' }}
                    </span>
                    <span class="text-muted small">&bull;</span>
                    <span class="text-muted small fw-medium">{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
                <h1 class="h2 fw-bold text-dark mb-1" style="letter-spacing: -0.02em;">
                    Selamat Datang, {{ Auth::user()->name }}! 👋
                </h1>
                <p class="text-muted mb-0 small">
                    Ringkasan performa portofolio investasi, alokasi saldo finansial CIO, dan status distribusi bagi hasil investor secara real-time.
                </p>
            </div>
            <div class="col-lg-5 text-lg-end">
                <div class="d-inline-flex flex-wrap align-items-center justify-content-lg-end gap-2">
                    @if(Auth::user()->hasRole('Investor'))
                        <a href="{{ route('transfer.index') }}" class="btn btn-primary d-inline-flex align-items-center gap-1.5 shadow-sm px-3 py-2 fw-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 14l-4 -4l4 -4" /><path d="M5 10h11a4 4 0 1 1 0 8h-1" /></svg>
                            Riwayat Transfer
                        </a>
                    @else
                        <a href="{{ route('investor.index') }}" class="btn btn-primary d-inline-flex align-items-center gap-1.5 shadow-sm px-3 py-2 fw-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                            Kelola Investor
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @role('Admin')
    <!-- 2. Section Header: Saldo Finance CIO -->
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-2 mb-3">
        <div class="d-flex align-items-center gap-2">
            <div class="fintech-icon-box" style="background-color: #eff6ff; color: #2563eb; width: 32px; height: 32px; border-radius: 8px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" /></svg>
            </div>
            <div>
                <h3 class="fw-bold text-dark mb-0 fs-5">Likuiditas Saldo Finance CIO</h3>
                <div class="text-muted" style="font-size: 0.78rem;">
                    Klien: <strong class="text-dark">{{ $balanceData['client_name'] ?? 'Web_Investor' }}</strong>
                    @if(!empty($balanceData['retrieved_at']))
                        &bull; Update: {{ \Carbon\Carbon::parse($balanceData['retrieved_at'])->translatedFormat('d M Y, H:i:s') }}
                    @endif
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            @if($isApiConnected)
                <span class="badge badge-soft-success d-inline-flex align-items-center px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 0.75rem;">
                    <span class="status-dot-pulse active"></span> API Terhubung
                </span>
            @else
                <span class="badge badge-soft-danger d-inline-flex align-items-center px-2.5 py-1.5 rounded-pill fw-bold" style="font-size: 0.75rem;">
                    <span class="status-dot-pulse inactive"></span> 
                    {{ $financeBalance['status'] === 'not_configured' ? 'Belum Dikonfigurasi' : 'API Terputus' }}
                </span>
            @endif
            <a href="{{ route('dashboard', ['refresh_balance' => 1]) }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1 shadow-sm px-2.5 py-1" title="Sinkronkan & Perbarui Saldo">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
                <span>Sinkronkan</span>
            </a>
        </div>
    </div>

    @if(!$isApiConnected)
        <div class="alert alert-warning d-flex align-items-center mb-3 py-2.5 px-3 small border-0 shadow-sm" role="alert" style="background-color: #fffbeb; color: #92400e; border-left: 4px solid #f59e0b !important; border-radius: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2 text-warning flex-shrink-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v2m0 4v.01" /><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" /></svg>
            <div>
                <strong>Perhatian:</strong> {{ $financeBalance['message'] }}.
                @if($financeBalance['status'] === 'not_configured')
                    Pastikan konfigurasi <code>CIO_FINANCE_*</code> di file <code>.env</code> sudah terisi.
                @endif
            </div>
        </div>
    @endif

    <!-- 3. Balance 3-Cards Row -->
    <div class="row g-3 mb-4">
        <!-- Saldo Manual -->
        <div class="col-sm-6 col-lg-4">
            <div class="fintech-stat-card card-manual">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="fintech-icon-box" style="background-color: #eff6ff; color: #2563eb;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" /></svg>
                        </div>
                        <div>
                            <div class="text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.05em; color: #64748b;">Saldo Kas & Manual</div>
                            <div class="text-muted" style="font-size: 0.76rem;">Saluran Kas & Transfer</div>
                        </div>
                    </div>
                    @if(!empty($channelStatus['manual']))
                        <span class="badge badge-soft-success d-inline-flex align-items-center px-2 py-1 rounded-pill fw-bold" style="font-size: 0.72rem;">
                            <span class="status-dot-pulse active"></span> Aktif
                        </span>
                    @else
                        <span class="badge badge-soft-danger d-inline-flex align-items-center px-2 py-1 rounded-pill fw-bold" style="font-size: 0.72rem;">
                            <span class="status-dot-pulse inactive"></span> Nonaktif
                        </span>
                    @endif
                </div>
                <div class="fs-2 fw-bold text-dark my-2 num-currency">
                    Rp {{ number_format((float) ($balanceData['balance_manual'] ?? 0), 0, ',', '.') }}
                </div>
                <div class="d-flex align-items-center gap-1 text-muted" style="font-size: 0.78rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>Tersedia untuk alokasi manual</span>
                </div>
            </div>
        </div>

        <!-- Saldo Xendit -->
        <div class="col-sm-6 col-lg-4">
            <div class="fintech-stat-card card-xendit">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="fintech-icon-box" style="background-color: #ecfdf5; color: #059669;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 5m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M3 10l18 0" /><path d="M7 15l.01 0" /><path d="M11 15l2 0" /></svg>
                        </div>
                        <div>
                            <div class="text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.05em; color: #64748b;">Saldo Xendit</div>
                            <div class="text-muted" style="font-size: 0.76rem;">Payment Gateway & VA</div>
                        </div>
                    </div>
                    @if(!empty($channelStatus['xendit']))
                        <span class="badge badge-soft-success d-inline-flex align-items-center px-2 py-1 rounded-pill fw-bold" style="font-size: 0.72rem;">
                            <span class="status-dot-pulse active"></span> Aktif
                        </span>
                    @else
                        <span class="badge badge-soft-danger d-inline-flex align-items-center px-2 py-1 rounded-pill fw-bold" style="font-size: 0.72rem;">
                            <span class="status-dot-pulse inactive"></span> Nonaktif
                        </span>
                    @endif
                </div>
                <div class="fs-2 fw-bold text-success my-2 num-currency">
                    Rp {{ number_format((float) ($balanceData['balance_xendit'] ?? 0), 0, ',', '.') }}
                </div>
                <div class="d-flex align-items-center gap-1 text-muted" style="font-size: 0.78rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-success"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>Tersedia untuk auto-disbursement</span>
                </div>
            </div>
        </div>

        <!-- Total Saldo Gabungan -->
        <div class="col-sm-12 col-lg-4">
            <div class="fintech-stat-card card-total">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <div class="fintech-icon-box" style="background-color: #f5f3ff; color: #7c3aed;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3v18" /><path d="M16 7l-8 10" /></svg>
                        </div>
                        <div>
                            <div class="text-uppercase fw-bold" style="font-size: 0.72rem; letter-spacing: 0.05em; color: #6d28d9;">Total Saldo Gabungan</div>
                            <div class="text-muted" style="font-size: 0.76rem;">Akumulasi Semua Saluran</div>
                        </div>
                    </div>
                    <span class="badge badge-soft-purple px-2 py-1 rounded-pill fw-bold" style="font-size: 0.72rem;">
                        Semua Saluran
                    </span>
                </div>
                <div class="fs-2 fw-bold my-2 num-currency" style="color: #6d28d9 !important;">
                    Rp {{ number_format((float) ($balanceData['total_balance'] ?? ($balanceData['balance'] ?? 0)), 0, ',', '.') }}
                </div>
                <div class="d-flex align-items-center gap-1 text-muted" style="font-size: 0.78rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: #7c3aed;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    <span>Total likuiditas finansial CIO</span>
                </div>
            </div>
        </div>
    </div>
    @endrole

    <!-- 4. Section Header: Portofolio & Bagi Hasil -->
    <div class="mb-3">
        <h3 class="fw-bold text-dark mb-0 fs-5">Ringkasan Portofolio & Bagi Hasil</h3>
        <div class="text-muted" style="font-size: 0.78rem;">Statistik akumulasi modal investor dan kewajiban dividen bulanan</div>
    </div>

    <!-- 5. Core Portfolio KPI (3 Cards Row) -->
    <div class="row g-3 mb-4">
        <!-- Total Investor -->
        <div class="col-sm-6 col-lg-4">
            <div class="fintech-stat-card card-investor">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-uppercase fw-bold text-muted mb-1" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                            Total Investor Terdaftar
                        </div>
                        <div class="fs-2 fw-bold text-dark num-currency">
                            {{ $investorsCount }} <span class="fs-5 text-muted fw-normal">Orang</span>
                        </div>
                        <div class="small text-muted mt-2 d-flex align-items-center gap-1.5">
                            <span class="badge badge-soft-primary px-2 py-0.5 rounded-pill fw-bold" style="font-size: 0.7rem;">Aktif</span>
                            <span>Investor terverifikasi</span>
                        </div>
                    </div>
                    <div class="fintech-icon-box" style="background-color: #f0f9ff; color: #0284c7; width: 48px; height: 48px; border-radius: 12px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Modal Investasi -->
        <div class="col-sm-6 col-lg-4">
            <div class="fintech-stat-card card-funds">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-uppercase fw-bold text-muted mb-1" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                            Total Dana Investasi
                        </div>
                        <div class="fs-2 fw-bold text-primary num-currency">
                            Rp {{ number_format($jumlahDanaInvestasi, 0, ',', '.') }}
                        </div>
                        <div class="small text-muted mt-2 d-flex align-items-center gap-1.5">
                            <span class="badge badge-soft-success px-2 py-0.5 rounded-pill fw-bold" style="font-size: 0.7rem;">Modal</span>
                            <span>Akumulasi modal masuk</span>
                        </div>
                    </div>
                    <div class="fintech-icon-box" style="background-color: #ecfdf5; color: #059669; width: 48px; height: 48px; border-radius: 12px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" /></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagi Hasil Bulanan -->
        <div class="col-sm-12 col-lg-4">
            <div class="fintech-stat-card card-income">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-uppercase fw-bold text-muted mb-1" style="font-size: 0.72rem; letter-spacing: 0.05em;">
                            Bagi Hasil / Bulan
                        </div>
                        <div class="fs-2 fw-bold text-success num-currency">
                            Rp {{ number_format($totalNominalPendapatan, 0, ',', '.') }}
                        </div>
                        <div class="small text-muted mt-2 d-flex align-items-center gap-1.5">
                            <span class="badge badge-soft-amber px-2 py-0.5 rounded-pill fw-bold" style="font-size: 0.7rem;">Dividen</span>
                            <span>Estimasi kewajiban bulanan</span>
                        </div>
                    </div>
                    <div class="fintech-icon-box" style="background-color: #fffbeb; color: #d97706; width: 48px; height: 48px; border-radius: 12px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M3 10l18 0" /><path d="M5 6l7 -3l7 3" /><path d="M4 10l0 11" /><path d="M20 10l0 11" /><path d="M8 14l0 3" /><path d="M12 14l0 3" /><path d="M16 14l0 3" /></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Section: Analytics & Portfolio Details -->
    <div class="row g-3">
        <div class="{{ Auth::user()->hasRole('Investor') ? 'col-lg-7' : 'col-12' }}">
            <div class="card shadow-sm border-0">
                <!-- Header with Quick Badges -->
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 py-3 px-4 bg-white border-bottom">
                    <div>
                        <h3 class="card-title fw-bold text-dark mb-1 d-flex align-items-center gap-2" style="font-size: 1.1rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 9h.01" /><path d="M11 12h1v4h1" /></svg>
                            Grafik & Status Distribusi Pendapatan
                        </h3>
                        <div class="text-muted small">Sebaran porsi modal investor dan monitoring transfer periode ini</div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge badge-soft-primary px-3 py-1.5 rounded-pill fw-bold" style="font-size: 0.8rem;">
                            Bulan {{ now()->translatedFormat('F Y') }}
                        </span>
                    </div>
                </div>

                <!-- Sub-indicator summary chips -->
                @php
                    $paidCount = 0;
                    $unpaidCount = 0;
                    foreach ($grafikPendapatan as $item) {
                        $isPaid = $item->transfer()
                            ->whereMonth('transfer_date', now()->month)
                            ->whereYear('transfer_date', now()->year)
                            ->where('status', 'success')
                            ->exists();
                        if ($isPaid) $paidCount++; else $unpaidCount++;
                    }
                @endphp
                <div class="px-4 pt-3 pb-0 d-flex flex-wrap align-items-center gap-2">
                    <div class="d-flex align-items-center gap-2 px-3 py-1.5 rounded-pill badge-soft-success fw-bold small">
                        <span style="display:inline-flex; width: 8px; height: 8px; border-radius: 50%; background-color: #10b981;"></span>
                        <span>{{ $paidCount }} Investor Sudah Ditransfer</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 px-3 py-1.5 rounded-pill badge-soft-danger fw-bold small">
                        <span style="display:inline-flex; width: 8px; height: 8px; border-radius: 50%; background-color: #ef4444;"></span>
                        <span>{{ $unpaidCount }} Investor Belum Ditransfer</span>
                    </div>
                </div>

                <!-- Chart Container -->
                <div class="card-body p-4">
                    <div id="chart-income" style="min-height: 440px;"></div>
                </div>

                <!-- Table of Investors -->
                <div class="table-responsive border-top">
                    <table class="table card-table table-vcenter">
                        <thead>
                            <tr>
                                <th>Nama Investor</th>
                                @if($dashboardColumns['dana_investasi']['visible'])
                                    <th>Dana Investasi</th>
                                @endif
                                @if($dashboardColumns['persentase']['visible'])
                                    <th>Persentase</th>
                                @endif
                                @if($dashboardColumns['nominal_pendapatan']['visible'])
                                    <th>Nominal Pendapatan</th>
                                @endif
                                <th class="text-center">Sisa Dividen</th>
                                @if($dashboardColumns['status_pembayaran']['visible'])
                                    <th class="text-center" style="width: 170px;">Status Pembayaran</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $colorPalettes = [
                                    ['bg' => '#eff6ff', 'text' => '#1e40af'],
                                    ['bg' => '#ecfdf5', 'text' => '#059669'],
                                    ['bg' => '#fffbeb', 'text' => '#d97706'],
                                    ['bg' => '#f3e8ff', 'text' => '#7e22ce'],
                                    ['bg' => '#fdf2f8', 'text' => '#be185d'],
                                    ['bg' => '#e0f2fe', 'text' => '#0369a1'],
                                    ['bg' => '#fef3c7', 'text' => '#b45309'],
                                    ['bg' => '#ede9fe', 'text' => '#6d28d9'],
                                    ['bg' => '#ccfbf1', 'text' => '#0f766e'],
                                    ['bg' => '#ffe4e6', 'text' => '#be123c'],
                                    ['bg' => '#f1f5f9', 'text' => '#334155'],
                                    ['bg' => '#dcfce7', 'text' => '#15803d'],
                                ];
                            @endphp

                            @forelse ($grafikPendapatan as $index => $dp)
                                @php
                                    $palette = $colorPalettes[$index % count($colorPalettes)];
                                    $sudahDibayar = $dp->transfer()
                                        ->whereMonth('transfer_date', now()->month)
                                        ->whereYear('transfer_date', now()->year)
                                        ->where('status', 'success')
                                        ->exists();
                                    $sedangPending = !$sudahDibayar && $dp->transfer()
                                        ->whereMonth('transfer_date', now()->month)
                                        ->whereYear('transfer_date', now()->year)
                                        ->where('status', 'pending')
                                        ->exists();
                                    $investorTotalFunds = $dp->investors->sum('bussines_funds');
                                    $investorTotalIncome = $dp->investors->sum('monthly_income');
                                    $persentases = $dp->investors->pluck('persentase')->filter()->values();
                                    $effectivePercentage = $investorTotalFunds > 0 
                                        ? round(($investorTotalIncome / $investorTotalFunds) * 100, 2)
                                        : 0;
                                    $totalDividendPeriods = 0;
                                    foreach ($dp->investors as $inv) {
                                        if ($inv->first_dividend_at && $inv->last_dividend_at) {
                                              $firstDiv = \Carbon\Carbon::parse($inv->first_dividend_at)->startOfDay();
                                              $lastDiv = \Carbon\Carbon::parse($inv->last_dividend_at)->startOfDay();
                                              if ($lastDiv->greaterThanOrEqualTo($firstDiv)) {
                                                  $totalDividendPeriods += (int) round($firstDiv->diffInMonths($lastDiv)) + 1;
                                              }
                                        }
                                    }
                                    $transferCount = $dp->transfer->where('status', 'success')->count();
                                    $sisaDividen = $totalDividendPeriods > 0 
                                        ? max(0, $totalDividendPeriods - $transferCount) 
                                        : null;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm rounded-circle fw-bold me-2.5" style="background-color: {{ $palette['bg'] }}; color: {{ $palette['text'] }}; width: 36px; height: 36px; font-size: 0.82rem; border: 2px solid #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.06); display: inline-flex; align-items: center; justify-content: center;">
                                                {{ strtoupper(substr($dp->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $dp->name }}</div>
                                                <div class="small text-muted">{{ $dp->email ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    @if($dashboardColumns['dana_investasi']['visible'])
                                        <td>
                                            <span class="fw-bold text-primary num-currency fs-6">
                                                Rp {{ number_format($investorTotalFunds, 0, ',', '.') }}
                                            </span>
                                        </td>
                                    @endif
                                    @if($dashboardColumns['persentase']['visible'])
                                        <td>
                                            <span class="badge badge-soft-primary px-2.5 py-1 fw-bold fs-7">
                                                {{ $effectivePercentage }}%
                                            </span>
                                            @if($persentases->count() > 1)
                                                <div class="text-muted fw-semibold" style="font-size: 0.72rem; margin-top: 2px;">
                                                    ({{ $persentases->count() }} Paket Investasi)
                                                </div>
                                            @endif
                                        </td>
                                    @endif
                                    @if($dashboardColumns['nominal_pendapatan']['visible'])
                                        <td>
                                            <span class="fw-bold text-dark num-currency fs-6">
                                                Rp {{ number_format($investorTotalIncome, 0, ',', '.') }}
                                            </span>
                                        </td>
                                    @endif
                                    <td class="text-center">
                                        @if(!is_null($sisaDividen))
                                            @if($sisaDividen > 0)
                                                <span class="badge badge-soft-primary px-2.5 py-1 fw-bold fs-7">{{ $sisaDividen }}x Lagi</span>
                                            @else
                                                <span class="badge-status-success">Selesai</span>
                                            @endif
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                    @if($dashboardColumns['status_pembayaran']['visible'])
                                        <td class="text-center">
                                            @if ($sudahDibayar)
                                                <span class="badge-status-success">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                    Sudah Dibayar
                                                </span>
                                            @elseif ($sedangPending)
                                                <span class="badge-status-warning">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><polyline points="12 7 12 12 15 15"></polyline></svg>
                                                    Pending
                                                </span>
                                            @else
                                                <span class="badge-status-danger">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                    Belum Dibayar
                                                </span>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-5">
                                        <div class="d-flex flex-column align-items-center justify-content-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-muted mb-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16v.01" /></svg>
                                            <div class="fw-semibold">Belum Ada Data Investor</div>
                                            <div class="small text-muted">Data investor yang terdaftar akan ditampilkan di sini.</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if (Auth::user()->hasRole('Investor'))
            <div class="col-lg-5">
                <!-- Pendapatan Tahunan Card -->
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header py-3 px-4 bg-white border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h4 class="card-title fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3l0 4" /><path d="M8 3l0 4" /><path d="M4 11l16 0" /><path d="M8 15h2v2h-2z" /></svg>
                            Riwayat Pendapatan Tahun {{ date('Y') }}
                        </h4>
                        <a href="{{ route('transfer.index') }}" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 17h6" /><path d="M9 13h6" /></svg>
                            Daftar Invoice
                        </a>
                    </div>
                    <div class="table-responsive" style="max-height: 250px;">
                        <table class="table card-table table-vcenter">
                            <thead>
                                <tr>
                                    <th>Bulan</th>
                                    <th class="text-end">Nominal Diterima</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listPendapatanBulanan as $data)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold text-dark">{{ $data['bulan'] }}</span>
                                        </td>
                                        <td class="text-end fw-bold text-success num-currency">
                                            Rp {{ number_format($data['nominal'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Multi-Investment Agreements Card -->
                <div class="card shadow-sm border-0">
                    <div class="card-header py-3 px-4 bg-white border-bottom">
                        <h4 class="card-title fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 12h6" /><path d="M9 16h6" /></svg>
                            Dokumen Perjanjian Investasi Anda
                        </h4>
                    </div>
                    <div class="card-body p-3">
                        @forelse($myInvestments as $idx => $inv)
                            <div class="p-3 mb-3 border rounded-3 bg-white shadow-sm" style="border-left: 4px solid #2563eb !important;">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <div class="fw-bold text-dark">Paket {{ $idx + 1 }}: {{ optional($inv->categorie)->name ?? 'Investasi' }}</div>
                                        <div class="text-muted small">Tipe: {{ optional($inv->type)->name ?? '-' }} | Bagi Hasil: <strong class="text-primary">{{ $inv->persentase }}%</strong></div>
                                    </div>
                                    <span class="badge bg-primary text-white fw-bold px-2.5 py-1.5 num-currency">Rp {{ number_format($inv->bussines_funds, 0, ',', '.') }}</span>
                                </div>
                                @if($inv->file)
                                    <div class="d-flex gap-2 mt-2">
                                        <a href="{{ asset($inv->file) }}" target="_blank" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                            Lihat Dokumen PDF
                                        </a>
                                        <a href="{{ asset($inv->file) }}" download class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>
                                            Unduh
                                        </a>
                                    </div>
                                @else
                                    <span class="text-muted small">Dokumen belum diunggah</span>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">Belum ada paket investasi aktif.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('js')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var data = {!! json_encode($grafikPendapatan->map(function($item) {
            $sudahDibayar = $item->transfer()
                ->whereMonth('transfer_date', now()->month)
                ->whereYear('transfer_date', now()->year)
                ->where('status', 'success')
                ->exists();

            $sedangPending = !$sudahDibayar && $item->transfer()
                ->whereMonth('transfer_date', now()->month)
                ->whereYear('transfer_date', now()->year)
                ->where('status', 'pending')
                ->exists();
            
            $totalFunds = $item->investors->sum('bussines_funds');
            $totalIncome = $item->investors->sum('monthly_income');
            $percentages = $item->investors->pluck('persentase')->filter()->values();
            $effectivePct = $totalFunds > 0 ? round(($totalIncome / $totalFunds) * 100, 2) : 0;
            
            return [
                'name' => $item->name,
                'funds' => (int) $totalFunds,
                'income' => (int) $totalIncome,
                'percentage' => $effectivePct . '%' . ($percentages->count() > 1 ? ' (' . $percentages->count() . ' Paket)' : ''),
                'status' => $sudahDibayar ? 'paid' : ($sedangPending ? 'pending' : 'unpaid')
            ];
        })) !!};

        // Palet warna modern, harmonis & variatif
        var chartColors = [
            '#2563eb', // Royal Blue
            '#10b981', // Emerald
            '#f59e0b', // Amber
            '#8b5cf6', // Purple
            '#ec4899', // Pink
            '#06b6d4', // Cyan
            '#3b82f6', // Blue Light
            '#14b8a6', // Teal
            '#f97316', // Orange
            '#a855f7', // Violet
            '#6366f1', // Indigo
            '#84cc16'  // Lime
        ];
    
        var options = {
            chart: {
                type: "donut",
                height: 440,
                fontFamily: "Plus Jakarta Sans, Inter, sans-serif",
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                },
                dropShadow: {
                    enabled: true,
                    blur: 6,
                    left: 0,
                    top: 3,
                    opacity: 0.08
                }
            },
            colors: chartColors,
            series: data.length > 0 ? data.map(item => item.funds) : [1],
            labels: data.length > 0 ? data.map(item => item.name) : ["Belum ada data"],
            stroke: {
                width: 3,
                colors: ['#ffffff']
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '60%',
                        background: 'transparent',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '13px',
                                fontFamily: 'Plus Jakarta Sans, Inter, sans-serif',
                                fontWeight: 600,
                                color: '#64748b',
                                offsetY: -4
                            },
                            value: {
                                show: true,
                                fontSize: '20px',
                                fontFamily: 'Plus Jakarta Sans, Inter, sans-serif',
                                fontWeight: 800,
                                color: '#1e293b',
                                offsetY: 4,
                                formatter: function (val) {
                                    return "Rp " + new Intl.NumberFormat('id-ID').format(val);
                                }
                            },
                            total: {
                                show: true,
                                showAlways: true,
                                label: 'Total Investasi',
                                fontSize: '12px',
                                fontFamily: 'Plus Jakarta Sans, Inter, sans-serif',
                                fontWeight: 600,
                                color: '#94a3b8',
                                formatter: function (w) {
                                    var total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                    return "Rp " + new Intl.NumberFormat('id-ID').format(total);
                                }
                            }
                        }
                    }
                }
            },
            legend: {
                position: 'right',
                horizontalAlign: 'center',
                floating: false,
                fontSize: '13px',
                fontFamily: 'Plus Jakarta Sans, Inter, sans-serif',
                fontWeight: 500,
                markers: {
                    width: 10,
                    height: 10,
                    radius: 12,
                    offsetX: -4
                },
                itemMargin: {
                    horizontal: 8,
                    vertical: 6
                },
                formatter: function(seriesName, opts) {
                    if (!data[opts.seriesIndex]) return seriesName;
                    var item = data[opts.seriesIndex];
                    var isPaid = item.status === 'paid';
                    var statusBadge = isPaid
                        ? '<span style="display:inline-flex; align-items:center; justify-content:center; width:16px; height:16px; border-radius:50%; background-color:#10b981; color:#ffffff; font-size:10px; font-weight:bold; margin-left:6px; vertical-align:middle;">✓</span>'
                        : '<span style="display:inline-flex; align-items:center; justify-content:center; width:16px; height:16px; border-radius:50%; background-color:#ef4444; color:#ffffff; font-size:10px; font-weight:bold; margin-left:6px; vertical-align:middle;">✕</span>';
                    
                    return seriesName + statusBadge;
                }
            },
            tooltip: {
                custom: function({series, seriesIndex, dataPointIndex, w}) {
                    if (!data[seriesIndex]) return '';
                    var item = data[seriesIndex];
                    var isPaid = item.status === 'paid';
                    var statusText = isPaid
                        ? '<span style="color:#059669; font-weight:700;">✓ Sudah Ditransfer Bulan Ini</span>'
                        : '<span style="color:#dc2626; font-weight:700;">✕ Belum Ditransfer Bulan Ini</span>';
                    
                    return '<div class="custom-chart-tooltip">' +
                        '<div class="tooltip-title">' + item.name + '</div>' +
                        '<div style="color: #64748b; font-size: 12px; margin-bottom: 2px;">Dana Modal: <strong style="color: #2563eb;">Rp ' + new Intl.NumberFormat('id-ID').format(item.funds) + '</strong></div>' +
                        '<div style="color: #64748b; font-size: 12px; margin-bottom: 2px;">Bagi Hasil: <strong style="color: #4f46e5;">' + item.percentage + '</strong></div>' +
                        '<div style="color: #64748b; font-size: 12px; margin-bottom: 6px;">Nominal/Bln: <strong style="color: #10b981;">Rp ' + new Intl.NumberFormat('id-ID').format(item.income) + '</strong></div>' +
                        '<div style="font-size: 11px; padding-top: 4px; border-top: 1px dashed #e2e8f0;">' + statusText + '</div>' +
                        '</div>';
                }
            },
            responsive: [{
                breakpoint: 992,
                options: {
                    chart: {
                        height: 380
                    },
                    legend: {
                        position: 'bottom',
                        horizontalAlign: 'center',
                        itemMargin: {
                            horizontal: 10,
                            vertical: 4
                        }
                    }
                }
            }]
        };
    
        var chart = new ApexCharts(document.querySelector("#chart-income"), options);
        chart.render();
    });
</script>
@endpush