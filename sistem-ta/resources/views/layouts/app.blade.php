@props(['title' => 'Sistem Tugas Akhir'])

<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    
    <title>Sistem TA</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    {{-- HEADER GLOBAL (Hitam) --}}
    <div class="bg-neutral-900 text-white px-10 py-6 flex justify-between items-center shrink-0">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-neutral-700 rounded-xl flex items-center justify-center text-2xl">
                🛡️
            </div>
            <div>
                <p class="text-sm opacity-80">FMIPA</p>
                <p class="text-xl font-semibold">Universitas Udayana</p>
                <span class="inline-block mt-1 text-xs bg-neutral-700 px-3 py-1 rounded-full">
                    {{ $title }}
                </span>
            </div>
        </div>

        {{-- BAGIAN PROFIL USER (YANG DIPERBAIKI) --}}
        <div class="flex items-center gap-4">
            <div class="text-right">
                {{-- 1. Nama User Dinamis --}}
                <p class="font-semibold">{{ Auth::user()->name }}</p>
                
                {{-- 2. Role User Dinamis (Huruf Besar) --}}
                <p class="text-xs opacity-70 uppercase">{{ Auth::user()->role }}</p>
            </div>
            
            {{-- 3. Foto Profil Navigasi --}}
            <a href="{{ route('profile.index') }}" 
                class="w-10 h-10 rounded-full border-2 border-white/20 hover:border-white transition overflow-hidden cursor-pointer">
                
                <img src="{{ Auth::user()->profile_photo_url }}" 
                    alt="{{ Auth::user()->name }}" 
                    class="w-full h-full object-cover">
            </a>
        </div>
    </div>

    {{-- MAIN LAYOUT (SIDEBAR + CONTENT) --}}
    <div class="flex flex-1">
        
        {{-- SIDEBAR MENU --}}
        <aside class="w-64 bg-white shadow-lg flex flex-col shrink-0">
            <nav class="p-4 space-y-2">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Menu Utama</p>
                
                {{-- MENU UMUM --}}
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition">
                    <span>🏠</span>
                    <span class="font-medium">Dashboard</span>
                </a>

                {{-- ... (Link Proposal & Monitoring Umum biarkan saja atau hapus jika tidak perlu) ... --}}

                {{-- KHUSUS MAHASISWA --}}
                @if(Auth::user()->role == 'mahasiswa')
                    <div class="mt-4 mb-2 text-xs font-bold text-blue-500 uppercase">Mahasiswa</div>
                    
                    <a href="{{ route('mahasiswa.sidang.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition">
                        <span>📅</span> <span class="font-medium">Jadwal Sidang Saya</span>
                    </a>
                @endif

                {{-- KHUSUS KOORDINATOR (ADMIN) --}}
                @if(Auth::user()->role == 'koordinator')
                    <div class="mt-4 mb-2 text-xs font-bold text-red-500 uppercase">Koordinator</div>

                    {{-- 1. Penetapan Dosen (Untuk Proposal Baru) --}}
                    <a href="{{ route('koordinator.penetapan.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition">
                        <span>📝</span> <span class="font-medium">Penetapan Dosen/TA</span>
                    </a>
                    
                    {{-- 2. Buat Jadwal Sidang (INI JAWABANNYA) --}}
                    {{-- Koordinator yang menentukan kapan sidang dimulai --}}
                    <a href="{{ route('dosen.sidang.create') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition">
                        <span>📅</span> <span class="font-medium">Buat Jadwal Sidang</span>
                    </a>

                    {{-- 3. Approval Reschedule (Jika mahasiswa minta ganti jadwal) --}}
                    <a href="{{ route('koordinator.approval') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition">
                        <span>✅</span> <span class="font-medium">Approval Reschedule</span>
                    </a>
                @endif

                {{-- KHUSUS DOSEN --}}
                @if(Auth::user()->role == 'dosen')
                    <div class="mt-4 mb-2 text-xs font-bold text-green-600 uppercase">Dosen</div>

                    {{-- 1. Monitoring Bimbingan (Yang sudah ada) --}}
                    <a href="{{ route('dosen.monitoring.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition">
                        <span>👀</span> <span class="font-medium">Monitoring Bimbingan</span>
                    </a>

                    {{-- 2. Permintaan Kesediaan (TAMBAHKAN INI) --}}
                    {{-- Beri badge notifikasi jika ada request pending --}}
                    @php
                        $pendingCount = App\Models\DosenRequest::where('dosen_id', Auth::id())->where('status', 'pending')->count();
                    @endphp
                    
                    <a href="{{ route('dosen.request.index') }}" class="flex items-center justify-between px-4 py-3 text-gray-700 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-center gap-3">
                            <span>📩</span> <span class="font-medium">Permintaan Masuk</span>
                        </div>
                        
                        @if($pendingCount > 0)
                            <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full animate-pulse">
                                {{ $pendingCount }}
                            </span>
                        @endif
                    </a>
                @endif

            </nav>

            {{-- Logout Button --}}
            <div class="mt-auto p-4 border-t">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 px-4 py-3 text-red-600 rounded-lg hover:bg-red-50 transition">
                        <span>🚪</span>
                        <span class="font-medium">Log Out</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN CONTENT AREA --}}
        <main class="flex-1 p-8 overflow-y-auto h-[calc(100vh-100px)]">
            
            {{-- PERBAIKAN: Tampilkan Header Halaman jika ada --}}
            @if (isset($header))
                <header class="bg-white shadow mb-6 rounded-lg p-4">
                    {{ $header }}
                </header>
            @endif

            {{-- PERBAIKAN HYBRID LAYOUT --}}
            {{-- 1. Cek apakah ada $slot (Untuk halaman Component baru) --}}
            {{ $slot ?? '' }}

            {{-- 2. Jika tidak ada $slot, ambil dari @yield (Untuk halaman Dashboard lama) --}}
            @yield('content')
            
        </main>

    </div>

</body>
</html>