<x-app-layout title="Profil Mahasiswa">
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- HEADER FAKULTAS --}}
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-xl font-bold text-gray-800">Fakultas Matematika dan Ilmu Pengetahuan Alam</h2>
                    <p class="text-sm text-gray-500">Universitas Udayana</p>
                    
                    <div class="mt-4 flex gap-2">
                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-md font-semibold">
                            {{ ucfirst($user->role) }}
                        </span>
                        <span class="inline-block px-3 py-1 bg-gray-100 text-gray-600 text-xs rounded-md font-semibold">
                            Aktif
                        </span>
                    </div>
                </div>
                {{-- Hiasan Background --}}
                <div class="absolute right-0 top-0 h-full w-32 bg-gradient-to-l from-gray-50 to-transparent"></div>
            </div>

            {{-- KARTU PROFIL UTAMA --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800">Profil Dosen</h3>
                    
                    {{-- TOMBOL EDIT (SUDAH DIPERBAIKI) --}}
                    <a href="{{ route('profile.edit') }}" class="bg-yellow-400 hover:bg-yellow-500 text-white text-xs font-bold px-4 py-2 rounded shadow transition flex items-center gap-2">
                        <i class="fas fa-edit"></i> Edit Profil
                    </a>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                        
                        {{-- KOLOM FOTO (KIRI) --}}
                        <div class="md:col-span-3 flex justify-center">
                            <div class="relative">
                                {{-- Tampilkan Foto Profil --}}
                                <img src="{{ $user->profile_photo_url }}" 
                                    alt="{{ $user->name }}" 
                                    class="w-48 h-48 rounded-lg object-cover border-4 border-white shadow-lg">
                                
                                {{-- Hiasan/Badge Role --}}
                                <div class="absolute bottom-2 right-2 bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded shadow">
                                    {{ ucfirst($user->role) }}
                                </div>
                            </div>
                        </div>

                        {{-- KOLOM DATA (KANAN) --}}
                        <div class="md:col-span-9 grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- NIM --}}
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">NIDN</label>
                                <div class="text-gray-800 font-bold text-lg">
                                    {{ $user->nim ?? '-' }}
                                </div>
                            </div>

                            {{-- EMAIL (PENTING) --}}
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Email Terdaftar</label>
                                <div class="text-gray-800 font-medium">
                                    {{ $user->email }}
                                </div>
                            </div>

                            {{-- NAMA LENGKAP --}}
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Nama Lengkap</label>
                                <div class="text-gray-800 font-bold text-lg">
                                    {{ $user->name }}
                                </div>
                            </div>

                            {{-- PROGRAM STUDI (STATIC) --}}
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Program Studi</label>
                                <div class="text-gray-800 font-medium">
                                    Informatika
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

 {{-- ================= KARTU MAHASISWA BIMBINGAN ================= --}}
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">

    {{-- HEADER --}}
    <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-gray-50">
        <h3 class="text-lg font-bold text-gray-800">
            Mahasiswa Bimbingan
        </h3>

        <span class="text-sm font-semibold text-gray-600">
            Total: {{ $proposalBimbingan->count() }}
        </span>
    </div>

    {{-- BODY --}}
    <div class="p-6">

        @if($proposalBimbingan->isNotEmpty())

            <div class="space-y-4">

                @foreach($proposalBimbingan as $proposal)

                    @php
                        $mhs = optional($proposal->mahasiswaUser)->mahasiswa;
                    @endphp

                    {{-- ITEM MAHASISWA --}}
                    <div class="flex items-start justify-between gap-4 p-4 border rounded-lg hover:bg-gray-50 transition">

                        {{-- INFO --}}
                        <div class="flex gap-4">

                            {{-- AVATAR --}}
                            <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center font-bold text-gray-500">
                                {{ strtoupper(substr($mhs->nama ?? 'M', 0, 1)) }}
                            </div>

                            {{-- DATA --}}
                            <div>
                                <p class="font-bold text-gray-800">
                                    {{ $mhs->nama ?? '-' }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    NIM: {{ $mhs->nim ?? '-' }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ $mhs->prodi ?? '-' }}
                                </p>
                                <p class="text-sm text-gray-600 mt-1 line-clamp-2">
                                    <span class="font-medium">Judul TA:</span>
                                    {{ $mhs->judul_ta ?? $proposal->judul ?? '-' }}
                                </p>
                            </div>
                        </div>

                        {{-- STATUS --}}
                        <span class="px-3 py-1 text-xs font-semibold rounded-full self-start
                            {{ $proposal->status === 'disetujui'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-yellow-100 text-yellow-700' }}">
                            {{ ucfirst($proposal->status) }}
                        </span>

                    </div>

                @endforeach

            </div>

        @else
            {{-- EMPTY STATE --}}
            <div class="bg-gray-50 border border-dashed border-gray-300 rounded-lg p-8 text-center">
                <p class="text-gray-500 text-sm">
                    Belum ada mahasiswa bimbingan
                </p>
            </div>
        @endif

    </div>
</div>

        </div>
    </div>
</x-app-layout>