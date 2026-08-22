<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <title>{{ $title ?? 'Akses Tidak Valid' }} - CIO Network</title>
    <link href="{{ asset('css/tabler.min.css') }}" rel="stylesheet" />
    <style>
        body {
            background-color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        .error-card {
            max-width: 480px;
            background: #ffffff;
            border-radius: 16px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .icon-wrapper {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background-color: #fef2f2;
            color: #ef4444;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="icon-wrapper">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        </div>
        <h3 class="font-weight-bold text-dark mb-2">{{ $title ?? 'Tautan Tidak Valid' }}</h3>
        <p class="text-muted mb-4">{{ $message ?? 'Tautan invoice yang Anda buka tidak valid atau tidak memiliki otorisasi yang sah.' }}</p>
        <a href="{{ url('/') }}" class="btn btn-outline-primary">
            Kembali ke Halaman Utama
        </a>
    </div>
</body>
</html>
