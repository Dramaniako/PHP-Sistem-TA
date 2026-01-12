@props(['title' => 'Sistem Tugas Akhir'])

<!DOCTYPE html>
<html lang="id">
<head>
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    
    <title>{{ $title }} - Sistem TA</title>

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Alpine.js (Wajib untuk fitur interaktif) --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col font-sans antialiased">

    {{-- HEADER GLOBAL (Hitam) --}}
    <div class="bg-neutral-900 text-white px-6 md:px-10 py-4 flex justify-between items-center shrink-0 shadow-md z-50">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 md:w-12 md:h-12 bg-neutral-700 rounded-xl flex items-center justify-center text-xl md:text-2xl shadow-inner">
                🛡️
            </div>
            <div>
                <p class="text-xs md:text-sm opacity-80 font-medium tracking-wide">FMIPA</p>
                <p class="text-lg md:text-xl font-bold leading-tight">Universitas Udayana</p>
            </div>
        </div>

        {{-- BAGIAN PROFIL USER --}}
        <div class="flex items-center gap-4">
            <div class="hidden md:block text-right">
                <p class="font-semibold text-sm">{{ Auth::user()->name }}</p>
                <span class="inline-block bg-neutral-800 border border-neutral-600 px-2 py-0.5 rounded text-[10px] uppercase tracking-wider font-bold text-gray-300">
                    {{ Auth::user()->role }}
                </span>
            </div>
            
            <a href="{{ route('profile.index') }}" 
               class="w-10 h-10 rounded-full border-2 border-white/20 hover:border-blue-500 transition overflow-hidden cursor-pointer shadow-lg relative group">
                <img src="{{ Auth::user()->profile_photo_url }}" 
                     alt="{{ Auth::user()->name }}" 
                     class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
            </a>
        </div>
    </div>

    {{-- MAIN LAYOUT --}}
    <div class="flex flex-1 overflow-hidden">
        
        {{-- SIDEBAR MENU --}}
        <aside class="w-64 bg-white shadow-xl flex flex-col shrink-0 border-r border-gray-200 overflow-y-auto z-40">
            <nav class="p-4 space-y-1">
                
                <div class="mb-4 px-4 pt-2">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Menu Utama</p>
                </div>
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-xl hover:bg-gray-50 hover:text-blue-600 transition group {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 font-bold' : '' }}">
                    <span class="text-lg group-hover:scale-110 transition">🏠</span>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>

                {{-- AREA MAHASISWA --}}
                @if(Auth::user()->role == 'mahasiswa')
                    <div class="mt-6 mb-2 px-4">
                        <p class="text-xs font-bold text-blue-500 uppercase tracking-widest border-b border-blue-100 pb-1">Mahasiswa</p>
                    </div>
                    
                    <a href="{{ route('mahasiswa.proposal.status') }}" 
                       class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-600 transition group {{ request()->routeIs('mahasiswa.proposal.*') ? 'bg-blue-50 text-blue-700 font-bold' : '' }}">
                        <i class="fas fa-info-circle w-5 text-center text-lg group-hover:scale-110 transition"></i>
                        <span class="text-sm font-medium">Status Proposal</span>
                    </a>

                    <a href="{{ route('mahasiswa.sidang.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-600 transition group {{ request()->routeIs('mahasiswa.sidang.*') ? 'bg-blue-50 text-blue-700 font-bold' : '' }}">
                        <span class="text-lg group-hover:scale-110 transition">📅</span> 
                        <span class="text-sm font-medium">Jadwal Sidang</span>
                    </a>

                    <a href="{{ route('mahasiswa.dokumen.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-600 transition group {{ request()->routeIs('mahasiswa.dokumen.*') ? 'bg-blue-50 text-blue-700 font-bold' : '' }}">
                        <i class="fas fa-file-invoice w-5 text-center text-lg group-hover:scale-110 transition"></i>
                        <span class="text-sm font-medium">Dokumen Administrasi TA</span>
                    </a>  
                @endif

                {{-- AREA KOORDINATOR --}}
                @if(Auth::user()->role == 'koordinator')
                    <div class="mt-6 mb-2 px-4">
                        <p class="text-xs font-bold text-red-500 uppercase tracking-widest border-b border-red-100 pb-1">Koordinator</p>
                    </div>

                    <a href="{{ route('koordinator.users.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-xl hover:bg-red-50 hover:text-red-600 transition group {{ request()->routeIs('koordinator.users.*') ? 'bg-red-50 text-red-700 font-bold' : '' }}">
                        <span class="text-lg group-hover:scale-110 transition">👥</span> 
                        <span class="text-sm font-medium">Daftar Pengguna</span>
                    </a>

                    <a href="{{ route('koordinator.penetapan.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-xl hover:bg-red-50 hover:text-red-600 transition group {{ request()->routeIs('koordinator.penetapan.*') ? 'bg-red-50 text-red-700 font-bold' : '' }}">
                        <span class="text-lg group-hover:scale-110 transition">📝</span> 
                        <span class="text-sm font-medium">Penetapan Dosen</span>
                    </a>
                    
                    <a href="{{ route('koordinator.sidang.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-xl hover:bg-red-50 hover:text-red-600 transition group {{ request()->routeIs('koordinator.sidang.*') ? 'bg-red-50 text-red-700 font-bold' : '' }}">
                        <span class="text-lg group-hover:scale-110 transition">📅</span> 
                        <span class="text-sm font-medium">Jadwal Sidang</span>
                    </a>

                    <a href="{{ route('koordinator.approval') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-xl hover:bg-red-50 hover:text-red-600 transition group {{ request()->routeIs('koordinator.approval') ? 'bg-red-50 text-red-700 font-bold' : '' }}">
                        <span class="text-lg group-hover:scale-110 transition">✅</span> 
                        <span class="text-sm font-medium">Approval Reschedule</span>
                    </a>

                    <a href="{{ route('koordinator.monitoring.index') }}" 
                    class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-xl hover:bg-red-50 hover:text-red-600 transition group {{ request()->routeIs('koordinator.monitoring.index') ? 'bg-red-50 text-red-700 font-bold' : '' }}">
                        <i class="fas fa-desktop w-5 text-center text-lg"></i>
                        <span class="text-sm font-medium">Monitoring Administrasi</span>
                    </a>

                    <a href="{{ route('koordinator.monitoring.statistik') }}" 
                       class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-xl hover:bg-red-50 hover:text-red-600 transition group {{ request()->routeIs('koordinator.monitoring.statistik') ? 'bg-red-50 text-red-700 font-bold' : '' }}">
                        <i class="fas fa-chart-pie w-5 text-center text-lg group-hover:scale-110 transition"></i>
                        <span class="text-sm font-medium">Statistik TA</span>
                    </a>

                    <a href="{{ route('koordinator.riwayat.index') }}" 
                    class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-xl hover:bg-red-50 hover:text-red-600 transition group {{ request()->routeIs('koordinator.riwayat.*') || request()->routeIs('koordinator.monitoring.timeline') ? 'bg-red-50 text-red-700 font-bold' : '' }}">
                        <i class="fas fa-history w-5 text-center text-lg group-hover:rotate-[-20deg] transition"></i>
                        <span class="text-sm font-medium">Log Riwayat TA</span>
                    </a>
                @endif

                {{-- AREA DOSEN --}}
                @if(Auth::user()->role == 'dosen')
                    <div class="mt-6 mb-2 px-4">
                        <p class="text-xs font-bold text-green-600 uppercase tracking-widest border-b border-green-100 pb-1">Dosen</p>
                    </div>

                    @php
                        $pendingCount = 0;
                        if(class_exists('App\Models\DosenRequest')) {
                            $pendingCount = App\Models\DosenRequest::where('dosen_id', Auth::id())->where('status', 'pending')->count();
                        }
                    @endphp
                    
                    <a href="{{ route('dosen.request.index') }}" class="flex items-center justify-between px-4 py-3 text-gray-700 rounded-xl hover:bg-green-50 hover:text-green-600 transition group {{ request()->routeIs('dosen.request.*') ? 'bg-green-50 text-green-700 font-bold' : '' }}">
                        <div class="flex items-center gap-3">
                            <span class="text-lg group-hover:scale-110 transition">📩</span> 
                            <span class="text-sm font-medium">Permintaan Masuk</span>
                        </div>
                        @if($pendingCount > 0)
                            <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full animate-pulse shadow-sm">{{ $pendingCount }}</span>
                        @endif
                    </a>

                    <a href="{{ route('dosen.monitoring.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-xl hover:bg-green-50 hover:text-green-600 transition group {{ request()->routeIs('dosen.monitoring.*') ? 'bg-green-50 text-green-700 font-bold' : '' }}">
                        <span class="text-lg group-hover:scale-110 transition">👀</span> 
                        <span class="text-sm font-medium">Monitoring</span>
                    </a>

                    <a href="{{ route('dosen.bimbingan.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-xl hover:bg-green-50 hover:text-green-600 transition group {{ request()->routeIs('dosen.bimbingan.*') ? 'bg-green-50 text-green-700 font-bold' : '' }}">
                        <i class="fas fa-chalkboard-teacher w-5 text-center text-lg group-hover:scale-110 transition"></i>
                        <span class="text-sm font-medium">Bimbingan Saya</span>
                    </a>

                    <a href="{{ route('dosen.penguji.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-xl hover:bg-green-50 hover:text-green-600 transition group {{ request()->routeIs('dosen.penguji.*') ? 'bg-green-50 text-green-700 font-bold' : '' }}">
                        <i class="fas fa-clipboard-check w-5 text-center text-lg group-hover:scale-110 transition"></i>
                        <span class="text-sm font-medium">Daftar Menguji</span>
                    </a>
                    
                    <a href="{{ route('dosen.sidang.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-xl hover:bg-blue-50 hover:text-blue-600 transition group {{ request()->routeIs('dosen.sidang.*') ? 'bg-blue-50 text-blue-700 font-bold' : '' }}">
                        <span class="text-lg group-hover:scale-110 transition">📅</span> 
                        <span class="text-sm font-medium">Jadwal Sidang</span>
                    </a>

                    <a href="{{ route('dosen.validasi.index') }}" 
                       class="flex items-center gap-3 px-4 py-3 text-gray-700 rounded-xl hover:bg-green-50 hover:text-green-600 transition group {{ request()->routeIs('dosen.validasi.*') ? 'bg-green-50 text-green-700 font-bold' : '' }}">
                        <i class="fas fa-user-check w-5 text-center text-lg group-hover:scale-110 transition"></i>
                        <span class="text-sm font-medium">Validasi Dokumen TA</span>
                    </a>
                @endif
            </nav>

            <div class="mt-auto p-4 border-t border-gray-100 bg-gray-50/50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 px-4 py-3 text-red-600 rounded-xl hover:bg-red-100/50 hover:shadow-sm transition group">
                        <span class="text-lg group-hover:-translate-x-1 transition">🚪</span>
                        <span class="font-bold text-sm">Log Out</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN CONTENT AREA --}}
        <main class="flex-1 overflow-y-auto h-screen pb-20 bg-gray-100">
            <div class="p-6 md:p-8 max-w-7xl mx-auto">
                @if (isset($header))
                    <header class="bg-white shadow-sm mb-8 rounded-2xl p-6 border border-gray-100">
                        {{ $header }}
                    </header>
                @endif

                <div class="animate-fade-in-up">
                    {{ $slot ?? '' }}
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.4s ease-out forwards;
        }
    </style>
</body>
</html>