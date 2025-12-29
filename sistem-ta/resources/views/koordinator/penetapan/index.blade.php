<x-app-layout title="Daftar Penetapan">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Penetapan Tugas Akhir') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if(session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
                @endif

                {{-- Header: HANYA Search Bar (Tombol Tambah DIHAPUS) --}}
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    
                    {{-- Judul Halaman Kecil --}}
                    <div class="text-gray-700 font-bold text-lg">
                        Antrean Judul Masuk
                    </div>

                    <form method="GET" action="{{ route('koordinator.penetapan.index') }}" class="w-full md:w-1/3 flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Judul / Nama Mahasiswa..." class="w-full border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                        <button type="submit" class="bg-gray-800 text-white px-4 rounded-r-md hover:bg-gray-700">
                            🔍
                        </button>
                    </form>
                </div>

                {{-- Tabel Data --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full border leading-normal">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Judul Proposal</th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pembimbing</th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Penguji</th>
                                <th class="px-5 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi / Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($proposals as $p)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-5 py-4 text-sm font-bold text-gray-900">{{ $p->mahasiswa->name }}</td>
                                    <td class="px-5 py-4 text-sm text-gray-700 italic">"{{ Str::limit($p->judul, 40) }}"</td>
                                    <td class="px-5 py-4 text-sm text-gray-600">{{ $p->pembimbing->name ?? '-' }}</td>
                                    <td class="px-5 py-4 text-sm text-gray-600">{{ $p->penguji->name ?? '-' }}</td>
                                    <td class="px-5 py-4 text-sm text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            
                                            {{-- 1. TOMBOL DETAIL (BARU: Tambahkan ini) --}}
                                            {{-- Tombol ini muncul baik sudah ditetapkan maupun belum --}}
                                            <a href="{{ route('koordinator.penetapan.show', $p->id) }}" class="bg-blue-100 hover:bg-blue-200 text-blue-700 font-bold py-1 px-3 rounded text-xs flex items-center" title="Lihat Detail Proposal">
                                                👁️
                                            </a>

                                            {{-- 2. LOGIKA TOMBOL PROSES (YANG LAMA) --}}
                                            @if($p->dosen_pembimbing_id == null)
                                                {{-- Jika Belum Ada Dosen -> Tombol Kuning --}}
                                                <a href="{{ route('koordinator.penetapan.edit', $p->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded text-xs">
                                                    ⚙️ Tetapkan
                                                </a>
                                            @else
                                                {{-- Jika Sudah Ada -> Teks Selesai + Link Ubah --}}
                                                <div class="flex flex-col items-center">
                                                    <span class="px-2 py-0.5 font-semibold leading-tight text-green-700 bg-green-100 rounded-full text-[10px] mb-1">
                                                        ✓ Selesai
                                                    </span>
                                                    <a href="{{ route('koordinator.penetapan.edit', $p->id) }}" class="text-xs text-blue-500 hover:underline">Ubah</a>
                                                </div>
                                            @endif

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-8 text-center text-gray-500">
                                        <p class="text-lg">📂 Belum ada data masuk.</p>
                                        <p class="text-sm">Silakan minta mahasiswa mengisi form pengajuan judul.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $proposals->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>