@extends('layouts.app')

@section('title')
    Pratinjau Surat Perjanjian Kerjasama PDF
@endsection

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Top Action Bar -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 bg-white p-3.5 rounded-3 border shadow-sm">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('investor.index') }}" class="btn btn-outline-secondary btn-icon rounded-circle" title="Kembali ke Daftar Investor">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
            </a>
            <div>
                <h4 class="fw-bold text-dark mb-0">Surat Perjanjian Kerjasama Investasi (SPK)</h4>
                <div class="text-muted small">ID Investasi: #{{ $investor->id }} &bull; Pemodal: <strong>{{ $investor->party_1_name ?? $investor->user->name }}</strong></div>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('investor.pdf.download', $investor->id) }}" class="btn btn-primary d-inline-flex align-items-center gap-1.5 shadow-sm" target="_blank">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" /><path d="M7 11l5 5l5 -5" /><path d="M12 4l0 12" /></svg>
                Unduh PDF
            </a>
            <a href="{{ route('investor.index') }}" class="btn btn-success d-inline-flex align-items-center gap-1.5 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                Selesai
            </a>
        </div>
    </div>

    <!-- Alert Status -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <div class="d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-success"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M9 12l2 2l4 -4" /></svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- PDF Viewer Frame -->
    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="card-body p-0">
            <iframe src="{{ route('investor.pdf.stream', $investor->id) }}" style="width: 100%; height: 940px; border: none;" title="Pratinjau Dokumen PDF"></iframe>
        </div>
    </div>
</div>
@endsection
