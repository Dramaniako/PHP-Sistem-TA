<x-app-layout title="Daftar Jadwal Sidang">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Jadwal Sidang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Pesan Notifikasi --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow-sm" role="alert">
                    <p class="font-bold">Berhasil</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    
                    {{-- Header Tabel & Tombol Tambah --}}
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-700">Manajemen Jadwal Sidang</h3>
                            <p class="text-sm text-gray-500">Daftar seluruh jadwal ujian tugas akhir mahasiswa.</p>
                        </div>
                        <a href="{{ route('koordinator.sidang.create') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Buat Jadwal Baru
                        </a>
                    </div>

                    {{-- Tabel Jadwal --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border-b">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Waktu & Jenis</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Mahasiswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Dosen Penguji</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Lokasi</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($jadwal_sidang as $jadwal)
                                    <tr class="hover:bg-blue-50/30 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900">
                                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->isoFormat('dddd, D MMM Y') }}
                                            </div>
                                            <div class="text-xs text-blue-600 font-mono">
                                                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                                            </div>
                                            <span class="mt-1 inline-block text-[10px] uppercase tracking-wider font-bold text-gray-400">
                                                # {{ str_replace('_', ' ', $jadwal->jenis_sidang) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $jadwal->mahasiswa->name ?? '-' }}</div>
                                            <div class="text-xs text-gray-500">{{ $jadwal->mahasiswa->nim ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            <div class="flex items-center">
                                                <div class="h-7 w-7 rounded-full bg-gray-200 flex items-center justify-center mr-2 text-[10px] font-bold">
                                                    {{ strtoupper(substr($jadwal->dosen->name ?? '?', 0, 1)) }}
                                                </div>
                                                {{ $jadwal->dosen->name ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="text-gray-600 border-b border-dotted border-gray-400">
                                                {{ $jadwal->lokasi }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusClass = match($jadwal->status) {
                                                    'dijadwalkan' => 'bg-blue-100 text-blue-700 border border-blue-200',
                                                    'selasai' => 'bg-green-100 text-green-700 border border-green-200',
                                                    'gagal' => 'bg-orange-100 text-orange-700 border border-orange-200',
                                                    'dibatalkan' => 'bg-red-100 text-red-700 border border-red-200',
                                                    default => 'bg-gray-100 text-gray-700',
                                                };
                                            @endphp
                                            <span class="px-3 py-1 text-[10px] leading-5 font-bold rounded-full {{ $statusClass }}">
                                                {{ strtoupper($jadwal->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <a href="{{ route('koordinator.sidang.edit', $jadwal->id) }}" 
                                               class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition">
                                                Update
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="text-gray-400">
                                                <svg class="mx-auto h-12 w-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                <p class="text-lg font-medium">Data Kosong</p>
                                                <p class="text-sm">Belum ada jadwal sidang yang terdaftar.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>