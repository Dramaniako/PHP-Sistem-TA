@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 shadow-sm sm:rounded-lg">
            <h2 class="text-2xl font-bold mb-6">Monitoring Dokumen Seluruh Mahasiswa</h2>

            <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <form action="{{ url()->current() }}" method="GET" class="flex w-full md:w-1/2 gap-2">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari nama mahasiswa atau NIM..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 transition">
                        Cari
                    </button>
                    @if(request('search'))
                        <a href="{{ url()->current() }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 flex items-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <table class="min-w-full border">
                <thead>
                    <tr class="bg-gray-50 border-b">
                        <th class="p-4 text-left font-semibold text-gray-600">Mahasiswa</th>
                        <th class="p-4 text-left font-semibold text-gray-600">Progres</th>
                        <th class="p-4 text-center font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mahasiswas as $mhs)
                        @php
                            // Menghitung jumlah dokumen yang sudah disetujui
                            $setuju = $mhs->dokumens->where('status', 'Disetujui')->count();
                            // Menghitung persentase progres (asumsi total 9 dokumen)
                            $persen = ($setuju / 9) * 100;
                        @endphp
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="p-4">
                                <div class="font-bold text-gray-800">{{ $mhs->name }}</div>
                                <div class="text-xs text-gray-500">{{ $mhs->nim ?? 'NIM tidak tersedia' }}</div>
                            </td>
                            <td class="p-4">
                                <div class="w-full bg-gray-200 h-2.5 rounded-full mb-1">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $persen }}%"></div>
                                </div>
                                <span class="text-xs font-medium text-gray-600">{{ $setuju }} / 9 Dokumen Selesai</span>
                            </td>
                            <td class="p-4 text-center">
                                <a href="{{ route('koordinator.monitoring.cetak', $mhs->id) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                    Cetak
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-8 text-center text-gray-500 italic">
                                Tidak ada data mahasiswa yang ditemukan untuk "{{ request('search') }}".
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
