@extends('layouts.app')

@section('title')
    Data Investor
@endsection

@section('content')
@include('components.alert.success')

<div class="card shadow-sm border-0">
    <!-- Header -->
    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 py-3 px-4 bg-white border-bottom">
        <div>
            <h3 class="card-title fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M3 10l18 0" /><path d="M5 6l7 -3l7 3" /><path d="M4 10l0 11" /><path d="M20 10l0 11" /><path d="M8 14l0 3" /><path d="M12 14l0 3" /><path d="M16 14l0 3" /></svg>
                Daftar Rekapitulasi Investor
            </h3>
            <div class="text-muted small">Kelola data pemodal, akumulasi portofolio modal, dan dokumen perjanjian investasi</div>
        </div>
        <div class="d-flex align-items-center gap-2">
            @can('download excel')
                <a href="{{ route('investor.export') }}" class="btn btn-outline-success d-inline-flex align-items-center gap-1 shadow-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M8 11h8v7h-8z" /><path d="M8 15h8" /><path d="M11 11v7" /></svg>
                    Export Excel
                </a>
            @endcan
            @can('buat investor')
                <a href="{{ route('investor.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                    Tambah Investasi Baru
                </a>
            @endcan
        </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="filter-toolbar">
        <div class="row g-3 align-items-center justify-content-between">
            <div class="col-auto d-flex align-items-center gap-2">
                <span class="text-muted small fw-medium">Tampilkan:</span>
                <select name="sort" id="sort" class="form-select form-select-sm" style="width: 75px;">
                    @foreach ([10, 25, 50, 100] as $opt)
                        <option value="{{ $opt }}" {{ request('sort') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
                <span class="text-muted small fw-medium">data</span>
            </div>
            <div class="col-md-4 col-12">
                <form method="GET">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Cari nama pemodal, email, username...">
                        <button class="btn btn-primary px-3" type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Table Responsive -->
    <div class="table-responsive">
        <table class="table card-table table-vcenter">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th>Investor (Pemilik Akun)</th>
                    <th class="text-center" style="width: 150px;">Portofolio Paket</th>
                    <th>Total Modal Usaha</th>
                    <th>Total Bagi Hasil / Bln</th>
                    <th>Tgl Terdaftar</th>
                    <th class="text-center" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($investors as $item)
                    @php
                        $totalFunds = $item->investors->sum('bussines_funds');
                        $totalIncome = $item->investors->sum('monthly_income');
                        $packageCount = $item->investors->count();
                    @endphp
                    <tr>
                        <td class="text-center text-muted fw-semibold">
                            {{ $loop->iteration + ($investors->currentPage() - 1) * $investors->perPage() }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm rounded-circle bg-primary-subtle text-primary fw-bold me-2" style="width: 36px; height: 36px; font-size: 0.8rem; display: inline-flex; align-items: center; justify-content: center;">
                                    {{ strtoupper(substr($item->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="fw-bold text-dark fs-6">{{ $item->name }}</div>
                                    <div class="small text-muted">
                                        {{ $item->email }} (@<span>{{ $item->username }}</span>)
                                        @if($item->phone)
                                            • <span class="text-success fw-medium"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg> {{ $item->phone }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1 shadow-none px-2.5 py-1" data-bs-toggle="modal" data-bs-target="#modal-investor-{{ $item->id }}" title="Klik untuk melihat rincian paket & dokumen">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 12h6" /><path d="M9 16h6" /></svg>
                                <span>{{ $packageCount }} Paket Aktif</span>
                            </button>
                        </td>
                        <td>
                            <span class="fw-bold text-primary num-currency fs-6">
                                Rp {{ number_format($totalFunds, 0, ',', '.') }}
                            </span>
                        </td>
                        <td>
                            <span class="fw-bold text-success num-currency fs-6">
                                Rp {{ number_format($totalIncome, 0, ',', '.') }}
                            </span>
                        </td>
                        <td class="text-muted small">
                            {{ \Carbon\Carbon::parse($item->created_at)->format('d M Y') }}
                        </td>
                        <td class="text-center">
                            <div class="action-btn-group">
                                @can('buat investor')
                                    <a href="{{ route('investor.create', ['user_id' => $item->id]) }}" class="btn-action btn-action-primary" title="Tambah Paket Investasi Baru">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                                    </a>
                                @endcan
                                @can('lihat investor')
                                    <button type="button" class="btn-action btn-action-warning" data-bs-toggle="modal" data-bs-target="#modal-investor-{{ $item->id }}" title="Lihat Portofolio & Dokumen Perjanjian">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                    </button>
                                @endcan
                                @can('hapus investor')
                                    <button type="button" onclick="deleteUser('{{ $item->id }}')" class="btn-action btn-action-danger" title="Hapus Akun Investor">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                    </button>
                                @endcan
                            </div>
                        </td> 
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <p class="mb-0">Tidak ada data investor ditemukan.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Table Footer Pagination -->
    <div class="table-footer d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
        <p class="m-0 text-muted small">
            Menampilkan <span class="fw-semibold text-dark">{{ $investors->firstItem() ?? 0 }}</span> - <span class="fw-semibold text-dark">{{ $investors->lastItem() ?? 0 }}</span> dari <span class="fw-semibold text-dark">{{ $investors->total() }}</span> total investor
        </p>
        <div class="m-0">
            {{ $investors->links() }}
        </div>
    </div>
</div>

<!-- Modals Ditempatkan di Luar Table Responsive -->
@foreach ($investors as $item)
    @php
        $totalFunds = $item->investors->sum('bussines_funds');
        $totalIncome = $item->investors->sum('monthly_income');
        $packageCount = $item->investors->count();
    @endphp
    <div class="modal fade" id="modal-investor-{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0" style="border-radius: 14px; overflow: hidden;">
                <!-- Modal Header -->
                <div class="modal-header py-3 px-4 bg-white border-bottom d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar rounded-circle fw-bold text-white shadow-sm" style="width: 48px; height: 48px; display: inline-flex; align-items: center; justify-content: center; font-size: 1.1rem; background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);">
                            {{ strtoupper(substr($item->name, 0, 2)) }}
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h5 class="modal-title fw-bold text-dark mb-0 fs-5">{{ $item->name }}</h5>
                                <span class="badge badge-soft-primary px-2 py-0.5 small fw-bold">Investor</span>
                            </div>
                            <div class="text-muted small mt-0.5 d-flex align-items-center gap-2 flex-wrap">
                                <span><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg> {{ $item->email }}</span>
                                <span>•</span>
                                <span><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg> @<span>{{ $item->username }}</span></span>
                                @if($item->phone)
                                    <span>•</span>
                                    <span><svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-success"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg> {{ $item->phone }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body p-4 bg-light-subtle">
                    <!-- 3 KPI Summary Cards with Icons -->
                    <div class="row g-3 mb-4">
                        <div class="col-sm-4 col-12">
                            <div class="p-3 bg-white border rounded-3 d-flex align-items-center gap-3 shadow-none">
                                <div class="avatar rounded-3 bg-primary-subtle text-primary fw-bold" style="width: 42px; height: 42px; display: inline-flex; align-items: center; justify-content: center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" /><path d="M12 12l8 -4.5" /><path d="M12 12l0 9" /><path d="M12 12l-8 -4.5" /></svg>
                                </div>
                                <div>
                                    <div class="text-muted small fw-medium">Jumlah Paket</div>
                                    <div class="fw-bold text-dark fs-6">{{ $packageCount }} Paket Aktif</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-12">
                            <div class="p-3 bg-white border rounded-3 d-flex align-items-center gap-3 shadow-none">
                                <div class="avatar rounded-3 bg-success-subtle text-success fw-bold" style="width: 42px; height: 42px; display: inline-flex; align-items: center; justify-content: center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" /></svg>
                                </div>
                                <div>
                                    <div class="text-muted small fw-medium">Total Dana Modal</div>
                                    <div class="fw-bold text-primary fs-6">Rp {{ number_format($totalFunds, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-12">
                            <div class="p-3 bg-white border rounded-3 d-flex align-items-center gap-3 shadow-none">
                                <div class="avatar rounded-3 bg-warning-subtle text-warning fw-bold" style="width: 42px; height: 42px; display: inline-flex; align-items: center; justify-content: center;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M3 10l18 0" /><path d="M5 6l7 -3l7 3" /><path d="M4 10l0 11" /><path d="M20 10l0 11" /><path d="M8 14l0 3" /><path d="M12 14l0 3" /><path d="M16 14l0 3" /></svg>
                                </div>
                                <div>
                                    <div class="text-muted small fw-medium">Total Bagi Hasil / Bln</div>
                                    <div class="fw-bold text-success fs-6">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Package Breakdown Header -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="fw-bold text-dark d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h10l2 2l-2 2h-10a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1z" /><path d="M13 13h7l2 2l-2 2h-7a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1z" /><path d="M4 19h15l2 2l-2 2h-15a1 1 0 0 1 -1 -1v-2a1 1 0 0 1 1 -1z" /></svg>
                            Rincian Seluruh Paket Investasi:
                        </div>
                        @can('buat investor')
                            <a href="{{ route('investor.create', ['user_id' => $item->id]) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 shadow-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                                Tambah Paket Baru
                            </a>
                        @endcan
                    </div>

                    <!-- Card-based Package Items -->
                    <div class="d-flex flex-column gap-3">
                        @forelse($item->investors as $idx => $inv)
                            <div class="p-3 bg-white border rounded-3 shadow-none">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="avatar rounded-circle fw-bold text-white shadow-sm" style="width: 32px; height: 32px; font-size: 0.85rem; background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%); display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            {{ $idx + 1 }}
                                        </div>
                                        <div>
                                            <div class="d-flex align-items-center gap-2 flex-wrap mb-1.5">
                                                <span class="fw-bold text-dark fs-6">{{ optional($inv->categorie)->name ?? 'Paket ' . ($idx + 1) }}</span>
                                                <span class="badge-category d-inline-flex align-items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h6v6h-6z" /><path d="M14 4h6v6h-6z" /><path d="M4 14h6v6h-6z" /><path d="M17 17m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /></svg>
                                                    {{ optional($inv->type)->name ?? 'Standar' }}
                                                </span>
                                                <span class="badge badge-soft-primary fw-bold d-inline-flex align-items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M7 7m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M6 18l12 -12" /></svg>
                                                    {{ $inv->persentase }}% / bln
                                                </span>
                                            </div>
                                            <div class="d-flex align-items-center gap-3 text-muted small flex-wrap">
                                                <span class="d-inline-flex align-items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" /></svg>
                                                    Modal: <strong class="text-primary">Rp {{ number_format($inv->bussines_funds, 0, ',', '.') }}</strong>
                                                </span>
                                                <span>•</span>
                                                <span class="d-inline-flex align-items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" /></svg>
                                                    Bagi Hasil: <strong class="text-success">Rp {{ number_format($inv->monthly_income, 0, ',', '.') }} / bln</strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center gap-2">
                                        @if($inv->file)
                                            <a href="{{ asset($inv->file) }}" target="_blank" class="btn btn-sm btn-outline-primary py-1 px-2.5 d-inline-flex align-items-center gap-1 shadow-none" title="Lihat Berkas Perjanjian PDF">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 12h6" /><path d="M9 16h6" /></svg>
                                                Dokumen PDF
                                            </a>
                                        @endif
                                        @can('ubah investor')
                                            <a href="{{ route('investor.edit', ['id' => $inv->id]) }}" class="btn-action btn-action-warning" title="Edit Paket Investasi">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                            </a>
                                        @endcan
                                        @can('hapus investor')
                                            <button type="button" onclick="deleteInvestment('{{ $inv->id }}')" class="btn-action btn-action-danger" title="Hapus Paket Ini">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                            </button>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4 bg-white rounded-3 border">Belum ada paket investasi aktif</div>
                        @endforelse
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer bg-white py-2.5 px-4 border-top d-flex justify-content-between align-items-center">
                    <span class="text-muted small d-flex align-items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9v4" /><path d="M12 16v.01" /><path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" /></svg>
                        Dokumen PDF perjanjian dapat diakses kapan saja oleh investor
                    </span>
                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection

@push('js')
<script>
    const BASE = "{{ route('investor.index') }}";

    let params = new URLSearchParams(window.location.search);
    $("#sort").change(function() {
        params.set('sort', $(this).val());
        window.location.href = BASE + '?' + params.toString();
    });

    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });

    function deleteInvestment(id) {
        Swal.fire({
            title: "Hapus Paket Investasi?",
            text: "Apakah Anda yakin ingin menghapus paket investasi ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#1e40af",
            cancelButtonColor: "#ef4444",
            confirmButtonText: "Ya, Hapus Paket",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: BASE + '/investment/' + id + '/destroy',
                    method: "DELETE",
                    dataType: "json",
                    success: function(response) {
                        Toast.fire({
                            icon: response.status,
                            title: response.message
                        });

                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    },
                    error: function(err) {
                        Toast.fire({
                            icon: "error",
                            title: "Gagal menghapus data paket."
                        });
                    }
                });
            }
        });
    }

    function deleteUser(id) {
        Swal.fire({
            title: "Hapus Akun Investor?",
            text: "Tindakan ini akan menghapus akun investor beserta SELURUH paket investasi miliknya!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#1e40af",
            cancelButtonColor: "#ef4444",
            confirmButtonText: "Ya, Hapus Semua",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: BASE + '/' + id + '/destroy',
                    method: "DELETE",
                    dataType: "json",
                    success: function(response) {
                        Toast.fire({
                            icon: response.status,
                            title: response.message
                        });

                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    },
                    error: function(err) {
                        Toast.fire({
                            icon: "error",
                            title: "Gagal menghapus data dari server."
                        });
                    }
                });
            }
        });
    }
</script>
@endpush