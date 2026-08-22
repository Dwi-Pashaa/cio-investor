@extends('layouts.app')

@section('title')
    Dashboard
@endsection

@push('css')
    <style>
        .custom-chart-tooltip {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
            padding: 10px 14px;
            border: 1px solid #e2e8f0;
            font-size: 13px;
        }
        .custom-chart-tooltip .tooltip-title {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }
        .custom-chart-tooltip .tooltip-value {
            font-weight: 600;
            color: #2563eb;
        }
        .status-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 6px;
        }
        .status-dot.paid { background-color: #10b981; }
        .status-dot.unpaid { background-color: #ef4444; }
    </style>
@endpush

@section('content')
    @php
        $totalNominalPendapatan = $grafikPendapatan->sum(function($item) {
            return $item->investors->sum('monthly_income');
        });
    @endphp

    <!-- Metric Cards Grid (3 Cards) -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-4">
            <div class="card metric-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="metric-avatar blue me-3 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                        </div>
                        <div>
                            <div class="metric-label mb-1">Total Investor Terdaftar</div>
                            <div class="metric-number">{{ $investorsCount }} <span class="fs-5 text-muted fw-normal">Orang</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="card metric-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="metric-avatar green me-3 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" /></svg>
                        </div>
                        <div>
                            <div class="metric-label mb-1">Total Dana Investasi Terkumpul</div>
                            <div class="metric-number">Rp {{ number_format($jumlahDanaInvestasi, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-4">
            <div class="card metric-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="metric-avatar amber me-3 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M3 10l18 0" /><path d="M5 6l7 -3l7 3" /><path d="M4 10l0 11" /><path d="M20 10l0 11" /><path d="M8 14l0 3" /><path d="M12 14l0 3" /><path d="M16 14l0 3" /></svg>
                        </div>
                        <div>
                            <div class="metric-label mb-1">Total Pendapatan (Bagi Hasil / Bln)</div>
                            <div class="metric-number text-success">Rp {{ number_format($totalNominalPendapatan, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Analytics & Transactions -->
    <div class="row g-3">
        <div class="{{ Auth::user()->hasRole('Investor') ? 'col-lg-7' : 'col-12' }}">
            <div class="card shadow-sm border-0">
                <!-- Header with Quick Badges -->
                <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 py-3 px-4 bg-white border-bottom">
                    <div>
                        <h4 class="card-title fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" /><path d="M12 9h.01" /><path d="M11 12h1v4h1" /></svg>
                            Grafik & Status Distribusi Pendapatan
                        </h4>
                        <div class="text-muted small">Sebaran porsi modal investor dan monitoring transfer periode ini</div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge" style="background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; font-size: 0.8rem; padding: 6px 12px;">
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
                <div class="px-4 pt-3 pb-0 d-flex flex-wrap align-items-center gap-3">
                    <div class="d-flex align-items-center gap-2 px-3 py-1.5 rounded-pill" style="background-color: #ecfdf5; border: 1px solid #a7f3d0;">
                        <span style="display:inline-flex; width: 10px; height: 10px; border-radius: 50%; background-color: #10b981;"></span>
                        <span class="small fw-bold" style="color: #065f46;">{{ $paidCount }} Investor Sudah Ditransfer</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 px-3 py-1.5 rounded-pill" style="background-color: #fef2f2; border: 1px solid #fecaca;">
                        <span style="display:inline-flex; width: 10px; height: 10px; border-radius: 50%; background-color: #ef4444;"></span>
                        <span class="small fw-bold" style="color: #991b1b;">{{ $unpaidCount }} Investor Belum Ditransfer</span>
                    </div>
                </div>

                <!-- Chart Container -->
                <div class="card-body p-4">
                    <div id="chart-income" style="min-height: 440px;"></div>
                </div>

                <!-- Table with Colorful Badges -->
                <div class="table-responsive">
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
                                    $investorTotalFunds = $dp->investors->sum('bussines_funds');
                                    $investorTotalIncome = $dp->investors->sum('monthly_income');
                                    $persentases = $dp->investors->pluck('persentase')->filter()->values();
                                    $effectivePercentage = $investorTotalFunds > 0 
                                        ? round(($investorTotalIncome / $investorTotalFunds) * 100, 2)
                                        : 0;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm rounded-circle fw-bold me-2" style="background-color: {{ $palette['bg'] }}; color: {{ $palette['text'] }}; width: 34px; height: 34px; font-size: 0.8rem; border: 2px solid #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.06); display: inline-flex; align-items: center; justify-content: center;">
                                                {{ strtoupper(substr($dp->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $dp->name }}</div>
                                                <div class="small text-muted">{{ $dp->email ?? '' }}</div>
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
                                    @if($dashboardColumns['status_pembayaran']['visible'])
                                        <td class="text-center">
                                            @if ($sudahDibayar)
                                                <span class="badge-status-success">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                                    Sudah Dibayar
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
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada data investor</td>
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
                        <h4 class="card-title fw-bold text-dark mb-0">Riwayat Pendapatan Tahun {{ date('Y') }}</h4>
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 12h6" /><path d="M9 16h6" /></svg>
                            Dokumen Perjanjian Investasi Anda
                        </h4>
                    </div>
                    <div class="card-body p-3">
                        @forelse($myInvestments as $idx => $inv)
                            <div class="p-3 mb-3 border rounded-3 bg-light-subtle">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <div class="fw-bold text-dark">Paket {{ $idx + 1 }}: {{ optional($inv->categorie)->name ?? 'Investasi' }}</div>
                                        <div class="text-muted small">Tipe: {{ optional($inv->type)->name ?? '-' }} | Bagi Hasil: {{ $inv->persentase }}%</div>
                                    </div>
                                    <span class="badge bg-primary text-white fw-bold">Rp {{ number_format($inv->bussines_funds, 0, ',', '.') }}</span>
                                </div>
                                @if($inv->file)
                                    <div class="d-flex gap-2 mt-2">
                                        <a href="{{ asset($inv->file) }}" target="_blank" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                            Lihat Dokumen PDF
                                        </a>
                                        <a href="{{ asset($inv->file) }}" download class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>
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
            
            $totalFunds = $item->investors->sum('bussines_funds');
            $totalIncome = $item->investors->sum('monthly_income');
            $percentages = $item->investors->pluck('persentase')->filter()->values();
            $effectivePct = $totalFunds > 0 ? round(($totalIncome / $totalFunds) * 100, 2) : 0;
            
            return [
                'name' => $item->name,
                'funds' => (int) $totalFunds,
                'income' => (int) $totalIncome,
                'percentage' => $effectivePct . '%' . ($percentages->count() > 1 ? ' (' . $percentages->count() . ' Paket)' : ''),
                'status' => $sudahDibayar ? 'paid' : 'unpaid'
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