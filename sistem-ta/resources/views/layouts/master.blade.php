<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem TA - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    <nav class="navbar">
        <div class="brand">
            <div class="logo-placeholder">
                <img src="{{ asset('img/image.svg') }}" alt="Logo" style="width: 24px;">
            </div>
            <div class="brand-text">
                <h1>FMIPA</h1>
                <h2>Universitas Udayana</h2>
            </div>
        </div>
        <div class="user-profile">
            <div style="text-align: right; display: none; @media(min-width: 768px){display:block;}">
                <div style="font-weight: 700; font-size: 14px;">I Dewa Contoh</div>
                <div style="font-size: 12px; opacity: 0.7;">DOSEN</div>
            </div>
            <div class="avatar">D</div>
        </div>
    </nav>

    <div class="main-container">
        @yield('content')
    </div>

</body>
</html>