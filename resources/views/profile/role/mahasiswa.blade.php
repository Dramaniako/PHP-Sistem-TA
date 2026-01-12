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
                    <h3 class="text-lg font-bold text-gray-800">Profil Mahasiswa</h3>
                    
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
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">NIM</label>
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

            {{-- KARTU BAWAH --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- BAGIAN DOSEN PEMBIMBING (Request #2) --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-md font-bold text-gray-800 mb-4 border-l-4 border-gray-800 pl-3">Dosen Pembimbing</h3>

        @if($proposal && $proposal->dosenPembimbing)
            {{-- Jika Sudah Ditetapkan --}}
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center font-bold text-gray-500">
                    {{ substr($proposal->dosenPembimbing->name, 0, 2) }}
                </div>
                <div>
                    <p class="font-bold text-gray-800">{{ $proposal->dosenPembimbing->name }}</p>
                    <p class="text-xs text-gray-500">NIDN: {{ $proposal->dosenPembimbing->nim ?? '-' }}</p> {{-- Asumsi NIM dipakai utk NIDN dosen --}}
                </div>
            </div>
        @else
            {{-- Jika Belum Ditetapkan --}}
            <div class="bg-yellow-50 border border-yellow-100 rounded-lg p-4 text-center">
                <p class="text-yellow-700 text-sm font-medium">Belum ditetapkan oleh Koordinator</p>
            </div>
        @endif
    </div>

    {{-- BAGIAN DOKUMEN TA (Request #3) --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-md font-bold text-gray-800 mb-4 border-l-4 border-gray-800 pl-3">Dokumen TA</h3>

        @if($proposal && $proposal->file_proposal)
            {{-- Jika File Ada --}}
            <div class="flex items-center justify-between bg-blue-50 p-4 rounded-lg border border-blue-100">
                <div class="flex items-center gap-3">
                    <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                    <div class="text-left">
                        <p class="text-sm font-bold text-gray-700 truncate w-40">Proposal TA.pdf</p>
                        <p class="text-xs text-gray-500">Diunggah: {{ $proposal->created_at->format('d M Y') }}</p>
                    </div>
                </div>
                {{-- Tombol Download (Akan kita buat rutenya di tahap 3) --}}
                <a href="{{ route('mahasiswa.proposal.download', $proposal->id) }}" 
                    class="text-blue-600 hover:text-blue-800 text-sm font-bold">
                    Unduh ⬇
                </a>
            </div>
        @else
            {{-- Jika Tidak Ada File --}}
            <div class="bg-gray-50 rounded-lg p-6 text-center border border-dashed border-gray-300">
                <p class="text-gray-500 text-sm">Belum ada dokumen yang diunggah</p>
                <a href="{{ route('mahasiswa.proposal.create') }}" class="text-blue-600 hover:underline text-xs mt-2 block">Upload Sekarang</a>
            </div>
        @endif
    </div>
</div>

        </div>
    </div>
</x-app-layout>