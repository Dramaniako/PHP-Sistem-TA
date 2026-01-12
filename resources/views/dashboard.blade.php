<x-app-layout title="Dashboard Utama">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- WELCOME BANNER --}}
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-xl p-8 mb-8 text-white flex items-center justify-between">
                <div>
                    <h3 class="text-3xl font-bold">Halo, {{ Auth::user()->name }}! 👋</h3>
                    <p class="mt-2 opacity-90">Selamat datang di Sistem Informasi Tugas Akhir.</p>
                    <span class="inline-block mt-3 px-3 py-1 bg-white/20 rounded-full text-sm font-semibold uppercase tracking-wide">
                        {{ Auth::user()->role }}
                    </span>
                </div>
                <div class="hidden md:block text-6xl">
                    🎓
                </div>
            </div>

            {{-- ======================= --}}
            {{-- TAMPILAN KOORDINATOR --}}
            {{-- ======================= --}}
            @if(Auth::user()->role == 'koordinator')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    {{-- Card 1 --}}
                    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
                        <div class="text-gray-500 text-sm font-bold uppercase">Total Mahasiswa</div>
                        <div class="text-3xl font-bold text-gray-800 mt-2">{{ $data['total_mahasiswa'] }}</div>
                    </div>
                    {{-- Card 2 --}}
                    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-yellow-500">
                        <div class="text-gray-500 text-sm font-bold uppercase">Proposal Baru (Pending)</div>
                        <div class="text-3xl font-bold text-gray-800 mt-2">{{ $data['proposal_pending'] }}</div>
                        <a href="{{ route('koordinator.penetapan.index') }}" class="text-xs text-blue-500 hover:underline mt-2 block">Proses Sekarang &rarr;</a>
                    </div>
                    {{-- Card 3 --}}
                    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
                        <div class="text-gray-500 text-sm font-bold uppercase">Sidang Minggu Ini</div>
                        <div class="text-3xl font-bold text-gray-800 mt-2">{{ $data['sidang_minggu_ini'] }}</div>
                    </div>
                </div>

                {{-- Recent Activity --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h4 class="font-bold text-gray-700 mb-4 text-lg">Proposal Terbaru Masuk</h4>
                    <ul class="divide-y divide-gray-100">
                        @forelse($data['recent_proposals'] as $prop)
                            <li class="py-3 flex justify-between items-center">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $prop->mahasiswa->name }}</p>
                                    <p class="text-sm text-gray-500 truncate w-64">{{ $prop->judul }}</p>
                                </div>
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full">
                                    {{ $prop->dosen_pembimbing_id ? 'Diproses' : 'Menunggu' }}
                                </span>
                            </li>
                        @empty
                            <p class="text-gray-500 text-sm">Belum ada proposal baru.</p>
                        @endforelse
                    </ul>
                </div>
            @endif

            {{-- ======================= --}}
            {{-- TAMPILAN MAHASISWA --}}
            {{-- ======================= --}}
            @if(Auth::user()->role == 'mahasiswa')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    {{-- Status Proposal --}}
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h4 class="font-bold text-gray-800 text-lg mb-4">📄 Status Proposal</h4>
                        @if($data['my_proposal'])
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <h5 class="font-bold text-blue-600">{{ $data['my_proposal']->judul }}</h5>
                                <div class="mt-3 space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Tanggal Pengajuan:</span>
                                        <span class="font-medium">{{ $data['my_proposal']->created_at->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Pembimbing:</span>
                                        <span class="font-medium">
                                            {{ $data['my_proposal']->pembimbing->name ?? 'Belum Ditetapkan' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-6">
                                <p class="text-gray-500 mb-4">Anda belum mengajukan judul.</p>
                                <a href="{{ route('mahasiswa.proposal.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 shadow">
                                    + Ajukan Judul Sekarang
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Status Sidang --}}
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h4 class="font-bold text-gray-800 text-lg mb-4">📅 Jadwal Sidang</h4>
                        @if($data['my_sidang'])
                            <div class="text-center bg-green-50 p-6 rounded-lg border border-green-200">
                                <p class="text-sm text-green-600 font-bold uppercase tracking-wider mb-2">Sidang Dijadwalkan</p>
                                <p class="text-3xl font-bold text-gray-800">{{ \Carbon\Carbon::parse($data['my_sidang']->tanggal)->format('d M') }}</p>
                                <p class="text-lg text-gray-600">{{ \Carbon\Carbon::parse($data['my_sidang']->tanggal)->format('Y') }}</p>
                                <div class="mt-4 inline-block bg-white px-4 py-2 rounded-full shadow-sm text-sm font-bold text-gray-700">
                                    ⏰ {{ $data['my_sidang']->jam_mulai }} WITA
                                </div>
                            </div>
                        @else
                            <div class="text-center py-6 text-gray-500">
                                <p>Belum ada jadwal sidang.</p>
                                <p class="text-xs mt-1">Pastikan proposal Anda sudah disetujui.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            {{-- ======================= --}}
            {{-- TAMPILAN DOSEN --}}
            {{-- ======================= --}}
            @if(Auth::user()->role == 'dosen')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-indigo-500">
                        <div class="text-gray-500 text-sm font-bold uppercase">Mahasiswa Bimbingan Aktif</div>
                        <div class="text-4xl font-bold text-gray-800 mt-2">{{ $data['total_bimbingan'] }}</div>
                        <a href="{{ route('dosen.monitoring.index') }}" class="text-sm text-indigo-600 mt-2 block hover:underline">Lihat Daftar &rarr;</a>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm">
                        <h4 class="font-bold text-gray-700 mb-4">Jadwal Menguji Terdekat</h4>
                        <ul class="space-y-3">
                            @forelse($data['jadwal_menguji'] as $jadwal)
                                <li class="flex gap-3 items-start p-2 hover:bg-gray-50 rounded">
                                    <div class="bg-blue-100 text-blue-600 rounded p-2 text-center min-w-[50px]">
                                        <div class="text-xs font-bold">{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('M') }}</div>
                                        <div class="text-lg font-bold">{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d') }}</div>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-800 text-sm">{{ $jadwal->mahasiswa->name ?? 'Mahasiswa' }}</p>
                                        <p class="text-xs text-gray-500">{{ $jadwal->jam_mulai }} - {{ $jadwal->lokasi }}</p>
                                    </div>
                                </li>
                            @empty
                                <p class="text-gray-500 text-sm italic">Tidak ada jadwal menguji dalam waktu dekat.</p>
                            @endforelse
                        </ul>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>