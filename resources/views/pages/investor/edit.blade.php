@extends('layouts.app')

@section('title')
    Edit Investor - {{ $investor->user->name }}
@endsection

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <style>
        .edit-section-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
            transition: all 0.2s ease;
        }

        .edit-section-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }

        .edit-section-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px dashed #e2e8f0;
        }

        .edit-section-badge {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: #eff6ff;
            color: #2563eb;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .income-preview-card {
            background: linear-gradient(135deg, #eff6ff 0%, #e0e7ff 100%);
            border: 1.5px solid #bfdbfe;
            border-radius: 14px;
            padding: 1.25rem;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.06);
        }

        .file-upload-dropzone {
            border: 2px dashed #cbd5e1;
            border-radius: 14px;
            padding: 20px;
            text-align: center;
            background: #f8fafc;
            transition: all 0.25s ease;
            cursor: pointer;
        }

        .file-upload-dropzone:hover {
            border-color: #2563eb;
            background: #f8faff;
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
                <h3 class="card-title fw-bold text-dark mb-1">Edit Data & Paket Investasi</h3>
                <div class="text-muted small">Memperbarui informasi pemodal dan skema perjanjian investasi</div>
            </div>
        </div>
        <a href="{{ route('investor.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1 shadow-none">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 14l-4 -4l4 -4" /><path d="M5 10h11a4 4 0 1 1 0 8h-1" /></svg>
            Kembali
        </a>
    </div>

    <!-- Form Content -->
    <form action="{{ route('investor.update', ['id' => $investor->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method("PUT")
        <div class="card-body p-4 p-md-5">
            
            <!-- Top Investor Overview Badge -->
            <div class="p-3 mb-4 rounded-3 border bg-light-subtle d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar avatar-md rounded-circle bg-primary text-white fw-bold shadow-xs" style="width: 44px; height: 44px; display: inline-flex; align-items: center; justify-content: center;">
                        {{ strtoupper(substr($investor->user->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="fw-bold text-dark mb-0">{{ $investor->user->name }}</h5>
                            <span class="badge bg-primary-subtle text-primary fw-semibold rounded-pill px-2.5 py-0.5" style="font-size: 0.72rem;">ID Paket #{{ $investor->id }}</span>
                        </div>
                        <div class="text-muted small">
                            @<span>{{ $investor->user->username }}</span> • {{ $investor->user->email }}
                            @if($investor->user->phone)
                                • <span class="text-success fw-medium">📱 {{ $investor->user->phone }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div>
                    <span class="badge bg-white text-dark border px-3 py-2 fw-semibold rounded-pill shadow-xs">
                        🏢 {{ optional($investor->categorie)->name ?? 'Kategori' }} &bull; {{ optional($investor->type)->name ?? 'Tipe' }}
                    </span>
                </div>
            </div>

            <!-- Section 1: Profil Akun -->
            <div class="edit-section-card">
                <div class="edit-section-header">
                    <div class="edit-section-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-0">1. Informasi Akun Pemodal</h5>
                        <div class="text-muted small">Identitas akun login investor di portal CIO Investor</div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="field-label" for="username">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M16 12v1.5a2.5 2.5 0 0 0 5 0v-1.5a9 9 0 1 0 -5.5 8.28" /></svg>
                            Username <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $investor->user->username) }}" required>
                        @error('username')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="field-label" for="name">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 4m0 3a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v10a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3z" /><path d="M9 10m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M15 8l2 0" /><path d="M15 12l2 0" /><path d="M7 16l10 0" /></svg>
                            Nama Lengkap Sesuai KTP <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $investor->user->name) }}" required>
                        @error('name')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="field-label" for="email">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
                            Alamat Email <span class="text-danger">*</span>
                        </label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $investor->user->email) }}" required>
                        @error('email')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="field-label" for="phone">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2" /></svg>
                            Nomor Telepon / WhatsApp <span class="text-muted fw-normal fs-7">(Opsional)</span>
                        </label>
                        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $investor->user->phone) }}" placeholder="Contoh: 081234567890">
                        @error('phone')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Detail Investasi -->
            <div class="edit-section-card">
                <div class="edit-section-header">
                    <div class="edit-section-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M17 8v-3a1 1 0 0 0 -1 -1h-10a2 2 0 0 0 0 4h12a1 1 0 0 1 1 1v3m0 4v3a1 1 0 0 1 -1 1h-12a2 2 0 0 1 -2 -2v-12" /><path d="M20 12v4h-4a2 2 0 0 1 0 -4h4" /></svg>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-0">2. Rincian Paket & Skema Dividen</h5>
                        <div class="text-muted small">Kategori usaha, tipe pembagian, nominal modal, dan persentase bagi hasil</div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="field-label" for="categories_id">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /><path d="M8 7v-2a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v2" /></svg>
                            Kategori Kerjasama / Usaha <span class="text-danger">*</span>
                        </label>
                        <select name="categories_id" id="categories_id" class="form-select @error('categories_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori Kerjasama --</option>
                            @foreach ($categories as $ct)
                                <option value="{{ $ct->id }}" {{ (old('categories_id', $investor->categories_id) == $ct->id) ? 'selected' : '' }}>{{ $ct->name }}</option>
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
                                <option value="{{ $tp->id }}" {{ (old('types_id', $investor->types_id) == $tp->id) ? 'selected' : '' }}>{{ $tp->name }}</option>
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
                            <input type="text" name="bussines_funds" id="bussines_funds" class="form-control font-monospace fw-bold @error('bussines_funds') is-invalid @enderror" value="{{ old('bussines_funds', number_format($investor->bussines_funds, 0, ',', '.')) }}" required autocomplete="off">
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
                            <input type="number" step="any" name="persentase" id="persentase" class="form-control font-monospace fw-bold @error('persentase') is-invalid @enderror" value="{{ old('persentase', $investor->persentase) }}" required>
                            <span class="input-group-text fw-bold text-primary bg-white border-start-0">%</span>
                        </div>
                        @error('persentase')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="field-label" for="first_money_received_at">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /></svg>
                            Tanggal Pertama Penerimaan Uang
                        </label>
                        <input type="date" name="first_money_received_at" id="first_money_received_at" class="form-control @error('first_money_received_at') is-invalid @enderror" value="{{ old('first_money_received_at', $investor->first_money_received_at) }}">
                        @error('first_money_received_at')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="field-label" for="first_dividend_at">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /></svg>
                            Tanggal Pertama Kali Dapat Dividen
                        </label>
                        <input type="date" name="first_dividend_at" id="first_dividend_at" class="form-control @error('first_dividend_at') is-invalid @enderror" value="{{ old('first_dividend_at', $investor->first_dividend_at) }}">
                        @error('first_dividend_at')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="field-label" for="last_dividend_at">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-primary"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h16" /></svg>
                            Tanggal Terakhir Kali Dapat Dividen
                        </label>
                        <input type="date" name="last_dividend_at" id="last_dividend_at" class="form-control @error('last_dividend_at') is-invalid @enderror" value="{{ old('last_dividend_at', $investor->last_dividend_at) }}">
                        @error('last_dividend_at')
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
                            <div class="fw-bold text-primary fs-4" id="preview_income">Rp {{ number_format($investor->monthly_income, 0, ',', '.') }}</div>
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

            <!-- Section 3: Berkas Penandatanganan & Para Pihak -->
            <div class="edit-section-card">
                <div class="edit-section-header">
                    <div class="edit-section-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 8h.01" /><path d="M3 6a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-12z" /><path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5" /><path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l3 3" /></svg>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-0">3. Bukti Foto Penandatanganan & Para Saksi</h5>
                        <div class="text-muted small">Upload foto bukti penandatanganan baru dan data para penanda tangan serta saksi</div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-7">
                        <div class="file-upload-dropzone" onclick="$('#file').trigger('click');">
                            <div class="avatar avatar-md rounded-circle bg-primary-subtle text-primary mb-2 mx-auto" style="width: 44px; height: 44px; display: inline-flex; align-items: center; justify-content: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 9l5 -5l5 5" /><path d="M12 4l0 12" /></svg>
                            </div>
                            <div class="fw-bold text-dark" id="file_display_name">Klik untuk Unggah Foto Baru</div>
                            <div class="text-muted small">Mendukung .JPG, .JPEG, .PNG, .WEBP (Biarkan kosong jika tidak diubah)</div>
                        </div>
                        <input type="file" name="file" id="file" class="form-control d-none @error('file') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg,image/webp">
                        @error('file')
                            <span class="invalid-feedback d-block mt-2">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-5">
                        <div class="p-3 bg-light-subtle border rounded-3 text-center">
                            <div class="text-muted small fw-semibold mb-2">Foto Penandatanganan Saat Ini:</div>
                            @if($investor->file)
                                <div class="mb-2">
                                    <img src="{{ asset($investor->file) }}" alt="Bukti Penandatanganan" class="img-fluid rounded-3 border shadow-xs" style="max-height: 140px; object-fit: contain;">
                                </div>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ asset($investor->file) }}" target="_blank" class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                        Lihat Full Foto
                                    </a>
                                </div>
                            @else
                                <div class="text-muted small py-3">Belum ada foto yang diunggah.</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="field-label" for="party_1_name">
                            Pihak Pertama (Investor)
                        </label>
                        <input type="text" name="party_1_name" id="party_1_name" class="form-control @error('party_1_name') is-invalid @enderror" value="{{ old('party_1_name', $investor->party_1_name ?? $investor->user->name) }}">
                        @error('party_1_name')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="field-label" for="party_2_name">
                            Pihak Kedua (Pengelola)
                        </label>
                        <input type="text" name="party_2_name" id="party_2_name" class="form-control @error('party_2_name') is-invalid @enderror" value="{{ old('party_2_name', $investor->party_2_name ?? 'Cio Network') }}">
                        @error('party_2_name')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <label class="field-label" for="party_1_address">
                            Alamat Investor (Sesuai KTP)
                        </label>
                        <textarea name="party_1_address" id="party_1_address" rows="2" class="form-control @error('party_1_address') is-invalid @enderror" placeholder="Alamat lengkap sesuai KTP">{{ old('party_1_address', $investor->party_1_address) }}</textarea>
                        @error('party_1_address')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="field-label" for="party_1_bank">
                            Bank
                        </label>
                        <input type="text" name="party_1_bank" id="party_1_bank" class="form-control @error('party_1_bank') is-invalid @enderror" value="{{ old('party_1_bank', $investor->party_1_bank) }}" placeholder="Contoh: BCA">
                        @error('party_1_bank')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="field-label" for="party_1_account_number">
                            No. Rekening
                        </label>
                        <input type="text" name="party_1_account_number" id="party_1_account_number" class="form-control @error('party_1_account_number') is-invalid @enderror" value="{{ old('party_1_account_number', $investor->party_1_account_number) }}" placeholder="Contoh: 2810732183">
                        @error('party_1_account_number')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="field-label" for="witness_1_name">
                            Nama Saksi Pihak Pertama
                        </label>
                        <input type="text" name="witness_1_name" id="witness_1_name" class="form-control @error('witness_1_name') is-invalid @enderror" value="{{ old('witness_1_name', $investor->witness_1_name) }}" placeholder="Contoh: Nama Saksi 1">
                        @error('witness_1_name')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="field-label" for="witness_2_name">
                            Nama Saksi Pihak Kedua
                        </label>
                        <input type="text" name="witness_2_name" id="witness_2_name" class="form-control @error('witness_2_name') is-invalid @enderror" value="{{ old('witness_2_name', $investor->witness_2_name) }}" placeholder="Contoh: Nama Saksi 2">
                        @error('witness_2_name')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 4: Keamanan Sandi -->
            <div class="edit-section-card mb-0">
                <div class="edit-section-header">
                    <div class="edit-section-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 11m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" /><path d="M8 11v-4a4 4 0 0 1 8 0v4" /></svg>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-0">4. Ubah Kata Sandi Akun</h5>
                        <div class="text-muted small">Opsional: Kosongkan kedua kolom di bawah jika tidak ingin mengganti kata sandi pemodal</div>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="field-label" for="password">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 11m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" /><path d="M8 11v-4a4 4 0 0 1 8 0v4" /></svg>
                            Kata Sandi Baru
                        </label>
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 8 karakter (opsional)">
                        @error('password')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="field-label" for="password_confirmation">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-muted"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 11m0 2a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2z" /><path d="M8 11v-4a4 4 0 0 1 8 0v4" /></svg>
                            Ulangi Kata Sandi Baru
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Ulangi kata sandi baru">
                        @error('password_confirmation')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

        </div>

        <!-- Card Footer Actions -->
        <div class="card-footer bg-light-subtle d-flex justify-content-between align-items-center py-3 px-4 border-top">
            <a href="{{ route('investor.index') }}" class="btn btn-outline-secondary px-3">
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
            }

            $("#bussines_funds").on("keyup", function() {
                let value = $(this).val();
                $(this).val(formatRupiah(value));
                calculateLiveIncome();
            });

            $("#persentase").on("input keyup", function() {
                calculateLiveIncome();
            });

            $('#file').on('change', function(e) {
                if (e.target.files && e.target.files.length > 0) {
                    $('#file_display_name').html('📄 ' + e.target.files[0].name);
                }
            });
        });
    </script>
@endpush