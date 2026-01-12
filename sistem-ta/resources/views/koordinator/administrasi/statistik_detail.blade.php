@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
            <div class="p-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Daftar Mahasiswa Tahap Administrasi</h2>
                        <p class="text-sm text-gray-500 mt-1">Monitoring progres 9 dokumen wajib tugas akhir mahasiswa.</p>
                    </div>
                </div>

                <div class="mb-8">
                    <form action="{{ url()->current() }}" method="GET" class="flex flex-wrap items-center gap-3">
                        <div class="relative flex-1 min-w-[300px]">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                                <i class="fas fa-search"></i>
                            </span>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Cari Nama Mahasiswa atau Judul..."
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm"
                            >
                        </div>
                        <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition shadow-md shadow-blue-100">
                            Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ url()->current() }}" class="px-6 py-2.5 bg-gray-100 text-gray-600 font-semibold rounded-xl hover:bg-gray-200 transition">
                                <i class="fas fa-undo mr-1"></i> Reset
                            </a>
                        @endif
                    </form>
                    @if(request('search'))
                        <p class="mt-3 text-sm text-gray-500 bg-blue-50 py-2 px-4 rounded-lg inline-block">
                            <i class="fas fa-info-circle text-blue-500 mr-1"></i> Menampilkan hasil untuk: <span class="font-bold text-blue-700">"{{ request('search') }}"</span>
                        </p>
                    @endif
                </div>

                <div class="overflow-hidden border border-gray-100 rounded-xl shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Identitas Mahasiswa</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Judul Tugas Akhir</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Progres Dokumen</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($mahasiswas as $mhs)
                            <tr class="hover:bg-blue-50/30 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold">
                                            {{ substr($mhs->name, 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900">{{ $mhs->name }}</div>
                                            <div class="text-xs text-gray-500">ID: #{{ $mhs->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-700 line-clamp-2 italic leading-relaxed">
                                        "{{ $mhs->penetapan->judul ?? 'Belum ada judul terdaftar' }}"
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @php 
                                        $count = $mhs->dokumens->where('status', 'Disetujui')->count();
                                        $percent = ($count / 9) * 100;
                                        $colorClass = $count == 9 ? 'bg-green-500' : ($count > 4 ? 'bg-blue-500' : 'bg-amber-500');
                                    @endphp
                                    <div class="flex flex-col items-center">
                                        <div class="w-full bg-gray-200 rounded-full h-2 mb-2 max-w-[120px]">
                                            <div class="{{ $colorClass }} h-2 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                                        </div>
                                        <span class="text-[11px] font-black {{ str_replace('bg-', 'text-', $colorClass) }} uppercase">
                                            {{ $count }} / 9 Selesai
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <a href="{{ route('koordinator.monitoring.kondisi', $mhs->id) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-white border border-blue-600 text-blue-600 text-xs font-bold rounded-lg hover:bg-blue-600 hover:text-white transition-all shadow-sm active:scale-95">
                                        <i class="fas fa-eye mr-2"></i> Lihat Kondisi
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-user-slash text-gray-200 text-5xl mb-4"></i>
                                        <p class="text-gray-500 font-medium">Data mahasiswa tidak ditemukan.</p>
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection