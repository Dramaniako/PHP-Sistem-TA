<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- TANPA VITE -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="font-sans text-gray-900 antialiased">

<div class="min-h-screen flex flex-col justify-center items-center bg-gray-100">
    
    <div style="margin-bottom: 20px; font-weight: bold;">
        Sistem Tugas Akhir
    </div>

    <div style="
        width: 100%;
        max-width: 400px;
        background: white;
        padding: 25px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    ">
        {{ $slot }}
    </div>

</div>

</body>
</html>
