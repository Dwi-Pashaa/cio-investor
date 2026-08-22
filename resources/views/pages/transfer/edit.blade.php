@extends('layouts.app')

@section('title')
    Edit Transfer Pendapatan - {{ $transfers->code }}
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <style>
        .badge-code {
            font-family: var(--tblr-font-monospace);
            font-weight: 700;
            background: #eff6ff;
            color: #1d4ed8;
            padding: 4px 10px;
            border-radius: 6px;
            border: 1px solid #bfdbfe;
        }

        .wa-notify-box {
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
            border: 1.5px solid #a7f3d0;
            border-radius: 12px;
            padding: 1rem 1.25rem;
        }

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
            <div class="avatar avatar-md rounded-3 bg-primary text-white fw-bold shadow-sm" style="width: 44px; height: 44px; display: inline-flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
            </div>
            <div>
                <h3 class="card-title fw-bold text-dark mb-1">Edit Data Transfer Pendapatan</h3>
                <div class="text-muted small">Kode Transaksi: <span class="badge-code">{{ $transfers->code }}</span></div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <!-- Resend WhatsApp Notification Button -->
            <form action="{{ route('transfer.resendNotification', ['id' => $transfers->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Kirim notifikasi pesan WhatsApp ke investor sekarang?');">
                @csrf
                <button type="submit" class="btn btn-outline-success btn-sm d-inline-flex align-items-center gap-1 shadow-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" /><path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" /></svg>
                    Kirim Ulang WA
                </button>
            </form>
            <a href="{{ route('transfer.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1 shadow-none">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 14l-4 -4l4 -4" /><path d="M5 10h11a4 4 0 1 1 0 8h-1" /></svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Form Content -->
    <form action="{{ route('transfer.update', ['id' => $transfers->id]) }}" method="POST">
        @csrf
        @method("PUT")
        <div class="card-body p-4 p-md-5">
            
            <!-- Top Status Info -->
            <div class="p-3 mb-4 rounded-3 border bg-light-subtle d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-md rounded-circle bg-success text-white fw-bold shadow-xs" style="width: 44px; height: 44px; display: inline-flex; align-items: center; justify-content: center;">
                        {{ strtoupper(substr(optional($transfers->investor)->name ?? 'IN', 0, 2)) }}
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="fw-bold text-dark mb-0">{{ optional($transfers->investor)->name ?? '-' }}</h5>
                            <span class="badge bg-{{ $transfers->status == 'success' ? 'success' : 'warning' }} text-white rounded-pill px-2.5 py-0.5" style="font-size: 0.72rem;">
                                {{ ucfirst($transfers->status) }}
                            </span>
                        </div>
                        <div class="text-muted small">
                            {{ optional($transfers->investor)->email }}
                            @if(optional($transfers->investor)->phone)
                                &bull; <span class="text-success fw-medium">📱 {{ optional($transfers->investor)->phone }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div>
                    <span class="badge bg-white text-dark border px-3 py-2 fw-semibold rounded-pill shadow-xs font-monospace">
                        {{ $transfers->code }}
                    </span>
                </div>
            </div>

            <div class="row g-4">
                <!-- Pilih Investor -->
                <div class="col-md-6">
                    <label class="field-label" for="investors_id">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                        Pilih Investor Penerima <span class="text-danger">*</span>
                    </label>
                    <select name="investors_id" id="investors_id" class="form-select @error('investors_id') is-invalid @enderror" required>
                        <option value="">-- Pilih Investor --</option>
                        @foreach ($investors as $ivt)
                            @php
                                $totalIncome = $ivt->investors->sum('monthly_income');
                                $packageCount = $ivt->investors->count();
                            @endphp
                            <option value="{{ $ivt->id }}" 
                                    data-income="{{ $totalIncome }}"
                                    data-phone="{{ $ivt->phone ?? '' }}"
                                    data-name="{{ $ivt->name }}"
                                    {{ (old('investors_id', $transfers->investors_id) == $ivt->id) ? 'selected' : '' }}>
                                {{ $ivt->name }} (Rp {{ number_format($totalIncome, 0, ',', '.') }}/bln &bull; {{ $packageCount }} Paket){{ $ivt->phone ? ' - ' . $ivt->phone : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('investors_id')
                        <span class="invalid-feedback d-block">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Jumlah Pendapatan -->
                <div class="col-md-6">
                    <label class="field-label" for="amount">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3" /></svg>
                        Jumlah Pendapatan (Nominal) <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text fw-bold text-primary bg-white border-end-0">Rp</span>
                        <input type="text" name="amount" id="amount" class="form-control font-monospace fw-bold @error('amount') is-invalid @enderror" value="{{ old('amount', number_format($transfers->amount, 0, ',', '.')) }}" required autocomplete="off">
                    </div>
                    <div id="income-hint" class="small text-muted mt-1" style="display: none;">
                        <span>💡 Estimasi dividen bulanan: </span>
                        <span id="hint-amount" class="fw-bold text-primary"></span>
                    </div>
                    @error('amount')
                        <span class="invalid-feedback d-block">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Jenis Bank -->
                <div class="col-md-6">
                    <label class="field-label" for="payment_method">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l18 0" /><path d="M3 10l18 0" /><path d="M5 6l7 -3l7 3" /><path d="M4 10l0 11" /><path d="M20 10l0 11" /><path d="M8 14l0 3" /><path d="M12 14l0 3" /><path d="M16 14l0 3" /></svg>
                        Rekening / Jenis Bank <span class="text-danger">*</span>
                    </label>
                    <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                        <option value="">-- Pilih Bank / Saluran Transfer --</option>
                        @foreach ($banks as $bnk)
                            <option value="{{ $bnk->name }}" {{ (old('payment_method', $transfers->payment_method) == $bnk->name) ? 'selected' : '' }}>
                                {{ $bnk->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('payment_method')
                        <span class="invalid-feedback d-block">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Tanggal Transfer -->
                <div class="col-md-6">
                    <label class="field-label" for="transfer_date">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /><path d="M11 15h1v3" /></svg>
                        Tanggal Transfer <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="transfer_date" id="transfer_date" class="form-control @error('transfer_date') is-invalid @enderror" value="{{ old('transfer_date', $transfers->transfer_date) }}" required>
                    @error('transfer_date')
                        <span class="invalid-feedback d-block">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                <!-- Catatan -->
                <div class="col-12">
                    <label class="field-label" for="notes">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>
                        Catatan / Keterangan Transfer <span class="text-muted fw-normal">(Opsional)</span>
                    </label>
                    <textarea name="notes" id="notes" rows="2" class="form-control @error('notes') is-invalid @enderror" placeholder="Catatan transfer...">{{ old('notes', $transfers->notes) }}</textarea>
                    @error('notes')
                        <span class="invalid-feedback d-block">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Card Footer Actions -->
        <div class="card-footer bg-light-subtle d-flex justify-content-between align-items-center py-3 px-4 border-top">
            <a href="{{ route('transfer.index') }}" class="btn btn-outline-secondary px-3">
                Batal
            </a>
            <div class="d-flex gap-2">
                <button type="reset" class="btn btn-ghost-secondary px-3">
                    Reset
                </button>
                <button type="submit" class="btn btn-primary px-4 shadow-sm d-inline-flex align-items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
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

            $('#investors_id').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '-- Pilih Investor --',
                allowClear: true
            });

            $('#payment_method').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '-- Pilih Bank / Saluran Transfer --',
                allowClear: true
            });

            $('#investors_id').on('change', function() {
                let selected = $(this).find('option:selected');
                let income = selected.data('income');

                if (income !== undefined && income !== null && income !== '') {
                    let formatted = formatRupiah(income);
                    $('#amount').val(formatted);
                    $('#hint-amount').text('Rp ' + formatted);
                    $('#income-hint').fadeIn(200);
                } else {
                    $('#income-hint').fadeOut(150);
                }
            });

            let initialValue = $("#amount").val();
            if (initialValue) {
                $("#amount").val(formatRupiah(initialValue));
            }

            $("#amount").on("keyup", function() {
                let value = $(this).val();
                $(this).val(formatRupiah(value));
            });
        });
    </script>
@endpush