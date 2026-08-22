@extends('layouts.app')

@section('title')
    Pengaturan Sistem
@endsection

@section('content')
    @include('components.alert.success')

    <div class="row g-4">

        {{-- ========================
             CARD: Kontak & Notifikasi
        ========================= --}}
        <div class="col-lg-5 col-12">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header py-3 px-4 bg-white border-bottom d-flex align-items-center gap-2">
                    <div class="avatar rounded-3 bg-primary-subtle text-primary" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg>
                    </div>
                    <div>
                        <h5 class="card-title fw-bold text-dark mb-0">Kontak & Notifikasi</h5>
                        <div class="text-muted small">Nomor WhatsApp resmi sistem</div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('setting.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $settings->id ?? '' }}">
                        <div class="mb-4">
                            <label for="telp" class="form-label fw-semibold text-dark">
                                Nomor WhatsApp Official / CS
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-success"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 21l1.65 -3.8a9 9 0 1 1 3.4 2.9l-5.05 .9" /><path d="M9 10a.5 .5 0 0 0 1 0v-1a.5 .5 0 0 0 -1 0v1a5 5 0 0 0 5 5h1a.5 .5 0 0 0 0 -1h-1a.5 .5 0 0 0 0 1" /></svg>
                                </span>
                                <input value="{{ $settings->telp ?? '' }}" type="text" name="telp" id="telp" class="form-control border-start-0 @error('telp') is-invalid @enderror" placeholder="Contoh: 628123456789">
                            </div>
                            <div class="form-text text-muted mt-1">Nomor ini digunakan sebagai kontak resmi tujuan konsultasi atau verifikasi transfer.</div>
                            @error('telp')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4 d-inline-flex align-items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                                Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ========================
             CARD: Visibilitas Kolom Dashboard
        ========================= --}}
        <div class="col-lg-7 col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header py-3 px-4 bg-white border-bottom d-flex align-items-center gap-2">
                    <div class="avatar rounded-3 bg-warning-subtle text-warning" style="width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 4h4v4h-4z" /><path d="M4 10h4v4h-4z" /><path d="M4 16h4v4h-4z" /><path d="M10 4h10" /><path d="M10 10h10" /><path d="M10 16h10" /></svg>
                    </div>
                    <div>
                        <h5 class="card-title fw-bold text-dark mb-0">Visibilitas Kolom Dashboard</h5>
                        <div class="text-muted small">Atur kolom yang tampil pada tabel distribusi pendapatan di dashboard</div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('setting.dashboard.columns') }}" method="POST" id="form-columns">
                        @csrf

                        {{-- Info banner --}}
                        <div class="alert alert-primary d-flex align-items-start gap-2 border-0 rounded-3 mb-4 py-2.5 px-3" style="background-color: #eff6ff; border-left: 4px solid #2563eb !important; border-left-width: 4px !important;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon mt-0.5 flex-shrink-0"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 9h.01" /><path d="M11 12h1v4h1" /><path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" /></svg>
                            <div class="small text-primary fw-medium">
                                Kolom <strong>Nama Investor</strong> selalu tampil dan tidak dapat disembunyikan. Pengaturan hanya berlaku untuk kolom data tambahan di bawah ini.
                            </div>
                        </div>

                        {{-- Column toggle list --}}
                        <div class="d-flex flex-column gap-3 mb-4">
                            @php
                                $columnIcons = [
                                    'dana_investasi'     => ['icon' => '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" />', 'color' => '#2563eb', 'bg' => '#eff6ff', 'desc' => 'Total modal investasi yang disetor oleh pemodal'],
                                    'persentase'         => ['icon' => '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 17m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M7 7m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M6 18l12 -12" />', 'color' => '#7c3aed', 'bg' => '#f5f3ff', 'desc' => 'Persentase bagi hasil efektif investor per bulan'],
                                    'nominal_pendapatan' => ['icon' => '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 17l6 -6l4 4l8 -8" /><path d="M14 7l7 0l0 7" />', 'color' => '#059669', 'bg' => '#ecfdf5', 'desc' => 'Nominal rupiah bagi hasil yang diterima per bulan'],
                                    'status_pembayaran'  => ['icon' => '<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" />', 'color' => '#dc2626', 'bg' => '#fef2f2', 'desc' => 'Status apakah transfer bulan ini sudah dilakukan'],
                                ];
                            @endphp

                            @foreach($dashboardColumns as $key => $col)
                                @php $meta = $columnIcons[$key]; @endphp
                                <div class="col-toggle-card d-flex align-items-center justify-content-between p-3 bg-light border rounded-3" style="transition: all 0.2s;">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar rounded-3 fw-bold flex-shrink-0" style="width: 40px; height: 40px; background-color: {{ $meta['bg'] }}; color: {{ $meta['color'] }}; display: inline-flex; align-items: center; justify-content: center;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="{{ $meta['color'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">{!! $meta['icon'] !!}</svg>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $col['label'] }}</div>
                                            <div class="text-muted small">{{ $meta['desc'] }}</div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="col-status-label small fw-semibold {{ $col['visible'] ? 'text-success' : 'text-danger' }}" id="label-{{ $key }}">
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
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <button type="button" class="btn btn-outline-secondary btn-sm px-3 d-inline-flex align-items-center gap-1" id="btn-reset-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" /><path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" /></svg>
                                Reset Semua ke Publik
                            </button>
                            <button type="submit" class="btn btn-primary px-4 d-inline-flex align-items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                                Simpan Pengaturan Kolom
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('js')
<style>
    .col-toggle-card:hover {
        background-color: #f8fafc !important;
        border-color: #cbd5e1 !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }
    .form-check-input:checked {
        background-color: #2563eb !important;
        border-color: #2563eb !important;
    }
</style>
<script>
    // Live label update on toggle
    document.querySelectorAll('.col-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const key = this.dataset.key;
            const label = document.getElementById('label-' + key);
            if (this.checked) {
                label.textContent = 'Publik';
                label.className = 'col-status-label small fw-semibold text-success';
            } else {
                label.textContent = 'Tersembunyi';
                label.className = 'col-status-label small fw-semibold text-danger';
            }
        });
    });

    // Reset all to public
    document.getElementById('btn-reset-all').addEventListener('click', function() {
        document.querySelectorAll('.col-toggle').forEach(function(toggle) {
            toggle.checked = true;
            const key = toggle.dataset.key;
            const label = document.getElementById('label-' + key);
            label.textContent = 'Publik';
            label.className = 'col-status-label small fw-semibold text-success';
        });
    });
</script>
@endpush