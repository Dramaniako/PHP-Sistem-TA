@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-8 shadow-sm sm:rounded-2xl border border-gray-200">
            
            <div class="flex items-center gap-4 mb-8">
                <div class="p-3 rounded-xl bg-purple-100 text-purple-600">
                    <i class="fas fa-history text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Log Riwayat Aktivitas Mahasiswa</h2>
                    <p class="text-sm text-gray-500">Pilih mahasiswa untuk melihat kronologi perkembangan Tugas Akhir.</p>
                </div>
            </div>

            <div class="mb-6">
                <form action="{{ url()->current() }}" method="GET" class="flex w-full md:w-1/2 gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nama atau NIM..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500">
                    <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded-xl hover:bg-purple-700 font-bold">Cari</button>
                </form>
            </div>

            <div class="overflow-hidden border border-gray-100 rounded-2xl">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Mahasiswa</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($mahasiswas as $mhs)
                        <tr class="hover:bg-purple-50/30 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-800">{{ $mhs->name }}</div>
                                <div class="text-xs text-gray-500">{{ $mhs->nim ?? 'NIM N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('koordinator.monitoring.timeline', $mhs->id) }}" 
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-xl text-xs font-black hover:bg-purple-700 transition shadow-sm shadow-purple-100">
                                    <i class="fas fa-route"></i> LIHAT TIMELINE
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="p-12 text-center text-gray-400 italic">Data mahasiswa tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection