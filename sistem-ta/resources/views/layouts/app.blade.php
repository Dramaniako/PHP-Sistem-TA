<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem TA</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    {{-- HEADER GLOBAL --}}
    <div class="bg-neutral-900 text-white px-10 py-6 flex justify-between items-center">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-neutral-700 rounded-xl flex items-center justify-center">
                🛡️
            </div>
            <div>
                <p class="text-sm opacity-80">FMIPA</p>
                <p class="text-xl font-semibold">Universitas Udayana</p>
                <span class="inline-block mt-1 text-xs bg-neutral-700 px-3 py-1 rounded-full">
                    Laporan & Dokumentasi
                </span>
            </div>
        </div>

        <div class="flex items-center gap-4">
            <div class="text-right">
                <p class="font-semibold">I Gusti Contoh</p>
                <p class="text-xs opacity-70">TATA USAHA</p>
            </div>
            <div class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center font-bold">
                G
            </div>
        </div>
    </div>

    {{-- ISI HALAMAN --}}
    @yield('content')

</body>
</html>
