@extends('layouts.app')

@section('title')
    Transfer Pendapatan Bulanan
@endsection

@section('content')
@include('components.alert.success')

<div class="card shadow-sm border-0">
    <!-- Header -->
    <div class="card-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 py-3 px-4 bg-white border-bottom">
        <div>
            <h3 class="card-title fw-bold text-dark mb-1">Log & Distribusi Transfer Pendapatan</h3>
            <div class="text-muted small">Catatan riwayat pencairan dana hasil investasi secara transparan dan akurat</div>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            @can('download excel')
                <a href="{{ route('transfer.export') }}" class="btn btn-outline-success d-inline-flex align-items-center gap-1 shadow-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M10 12l4 4m0 -4l-4 4" /></svg>
                    Export Excel
                </a>
            @endcan
            @can('buat transfer')
                <a href="{{ route('transfer.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                    Input Transfer Baru
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
                    @php
                        $opts = [10, 25, 50, 100];
                    @endphp 
                    @foreach ($opts as $opt)
                        <option value="{{ $opt }}" {{ request('sort') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
                <span class="text-muted small fw-medium">data</span>
            </div>
            <div class="col-md-4 col-12">
                <form method="GET">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Cari kode transaksi, bank, nama...">
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
                    <th style="width: 140px;">Kode Transaksi</th>
                    <th>Admin Pengirim</th>
                    @if (!Auth::user()->hasRole('Investor'))
                        <th>Penerima (Investor)</th>
                    @endif
                    <th>Jumlah Transfer</th>
                    <th>Metode / Bank</th>
                    <th>Tgl Transfer</th>
                    <th>Tgl Konfirmasi</th>
                    <th class="text-center" style="width: 130px;">Status</th>
                    <th class="text-center" style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transfers as $item)
                    <tr>
                        <td>
                            <a href="{{ $item->getInvoiceUrl() }}" target="_blank" class="badge-code text-decoration-none d-inline-flex align-items-center gap-1" title="Buka Faktur / Invoice">
                                {{ $item->code }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                            </a>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $item->admin->name }}</div>
                        </td>
                        @if (!Auth::user()->hasRole('Investor'))
                            <td>
                                <div class="fw-bold text-dark">{{ $item->investor->name }}</div>
                            </td>
                        @endif
                        <td>
                            <span class="fw-bold text-primary num-currency fs-6">Rp {{ number_format($item->amount, 0, ',', '.') }}</span>
                        </td>
                        <td>
                            <span class="badge-category">{{ $item->payment_method }}</span>
                        </td>
                        <td class="text-muted small">
                            {{ \Carbon\Carbon::parse($item->transfer_date)->format('d M Y') }}
                        </td>
                        <td class="text-muted small">
                            {{ $item->confirmation_date ? \Carbon\Carbon::parse($item->confirmation_date)->format('d M Y') : '-' }}
                        </td>
                        <td class="text-center">
                            @if ($item->status === 'success')
                                <span class="badge-status-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    BERHASIL
                                </span>
                            @elseif ($item->status === 'pending')
                                <span class="badge-status-warning">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"></circle><polyline points="12 7 12 12 15 15"></polyline></svg>
                                    PENDING
                                </span>
                            @else
                                <span class="badge-status-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    GAGAL
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="action-btn-group">
                                {{-- Tombol Lihat Invoice / Bukti Transfer (Dapat diakses Admin & Investor) --}}
                                <a href="{{ $item->getInvoiceUrl() }}" target="_blank" class="btn-action btn-action-cyan" title="Lihat Faktur / Bukti Transfer">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 17h6" /><path d="M9 13h6" /></svg>
                                </a>

                                @can('edit transfer')
                                    @if (!Auth::user()->hasRole('Investor'))
                                        {{-- Tombol Kirim Ulang Notifikasi WA --}}
                                        @if ($item->investor && $item->investor->phone)
                                            <form action="{{ route('transfer.resendNotification', ['id' => $item->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Kirim notifikasi WhatsApp dividen ke {{ addslashes($item->investor->name ?? '') }}?')">
                                                @csrf
                                                <button type="submit" class="btn-action btn-action-success" title="Kirim Notifikasi WA ke Investor">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" /><path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" /></svg>
                                                </button>
                                            </form>
                                        @endif
                                        @if ($item->status == 'pending')
                                            <a href="{{ route('transfer.edit', ['id' => $item->id]) }}" class="btn-action btn-action-warning" title="Edit Transfer">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                            </a>
                                        @endif
                                    @else
                                        @if ($item->status == 'pending')
                                            <form action="{{ route('transfer.confirmation', ['id' => $item->id]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method("PUT")
                                                <button type="submit" class="btn-action btn-action-primary" title="Konfirmasi Penerimaan Dana">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                @endcan
                                @can('hapus transfer')
                                    <button type="button" onclick="return deleteTransfer('{{ $item->id }}')" class="btn-action btn-action-danger" title="Hapus Data Transfer">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                    </button>
                                @endcan
                            </div>
                        </td> 
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ Auth::user()->hasRole('Investor') ? '8' : '9' }}" class="text-center py-5 text-muted">
                            Tidak Ada Data Transfer Pendapatan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Footer Pagination -->
    <div class="table-footer d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
        <p class="m-0 text-muted small">
            Menampilkan <span class="fw-semibold text-dark">{{ $transfers->firstItem() ?? 0 }}</span> - <span class="fw-semibold text-dark">{{ $transfers->lastItem() ?? 0 }}</span> dari <span class="fw-semibold text-dark">{{ $transfers->total() }}</span> total transaksi
        </p>
        <div class="m-0">
            {{ $transfers->links() }}
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    const BASE = "{{ route('transfer.index') }}";

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

    function deleteTransfer(id) {
        Swal.fire({
            title: "Konfirmasi Hapus",
            text: "Apakah Anda yakin ingin menghapus data transfer ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#1e40af",
            cancelButtonColor: "#ef4444",
            confirmButtonText: "Ya, Hapus",
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
                        }, 1500);
                    },
                    error: function(err) {
                        Toast.fire({
                            icon: "error",
                            title: "Gagal menghapus data dari server."
                        });
                    }
                })
            }
        });
    }
</script>
@endpush