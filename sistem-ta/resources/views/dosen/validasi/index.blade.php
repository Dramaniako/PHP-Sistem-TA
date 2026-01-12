@extends('layouts.app')

@section('content')
<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-200">
            <div class="p-8">
                
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Daftar Mahasiswa Bimbingan/Ujian</h2>
                        <p class="text-sm text-gray-500 mt-1">Halaman validasi berkas administrasi mahasiswa bimbingan atau ujian Anda.</p>
                    </div>
                    <div class="flex items-center bg-blue-50 px-4 py-2 rounded-xl">
                        <i class="fas fa-user-graduate text-blue-500 mr-3 text-xl"></i>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-blue-400 leading-none">Total Mahasiswa</p>
                            <p class="text-lg font-black text-blue-700 leading-none mt-1">{{ $mahasiswas->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="overflow-hidden border border-gray-100 rounded-xl shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nomor Induk (NIM)</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Antrean Validasi</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($mahasiswas as $mhs)
                            <tr class="hover:bg-blue-50/30 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 font-bold shadow-sm">
                                            {{ substr($mhs->name, 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900">{{ $mhs->name }}</div>
                                            <div class="text-[10px] text-indigo-500 font-bold uppercase tracking-tighter">Mahasiswa Bimbingan</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-md text-xs font-mono font-bold">
                                        {{ $mhs->nim ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($mhs->total_menunggu > 0)
                                            {{-- Kondisi 1: Ada dokumen baru yang butuh divalidasi --}}
                                            <div class="inline-flex items-center px-3 py-1 rounded-full bg-amber-100 text-amber-700 animate-pulse border border-amber-200">
                                                <span class="text-xs font-black uppercase">{{ $mhs->total_menunggu }} Dokumen Menunggu</span>
                                            </div>
                                        @elseif($mhs->dokumens_count == 0)
                                            {{-- Kondisi 2: Benar-benar belum ada file yang masuk --}}
                                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-500 text-xs font-bold border border-gray-200">
                                                <i class="fas fa-file-import mr-1"></i> Belum Unggah
                                            </span>
                                        @elseif($mhs->total_disetujui == 9)
                                            {{-- Kondisi 3: Lengkap 9 dokumen dan semuanya sudah disetujui --}}
                                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold border border-green-200">
                                                <i class="fas fa-check-double mr-1"></i> Validasi Selesai
                                            </span>
                                        @else
                                            {{-- Kondisi 4: Sudah unggah sebagian/semua tapi tidak ada yang 'Menunggu' (Mungkin ada yang ditolak) --}}
                                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-bold border border-blue-200">
                                                <i class="fas fa-info-circle mr-1"></i> Terpantaun Bersih
                                            </span>
                                        @endif
                                    </td>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <a href="{{ route('dosen.validasi.mahasiswa', $mhs->id) }}" 
                                       class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white text-xs font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-md shadow-indigo-100 active:scale-95 group">
                                        Periksa Berkas
                                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                            <i class="fas fa-users-slash text-gray-200 text-4xl"></i>
                                        </div>
                                        <h3 class="text-gray-800 font-bold text-lg">Belum Ada Mahasiswa</h3>
                                        <p class="text-gray-400 text-sm max-w-xs mx-auto">Anda belum ditetapkan sebagai pembimbing atau penguji untuk mahasiswa manapun.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 p-4 bg-gray-50 rounded-xl border border-gray-100 flex items-start gap-3">
                    <i class="fas fa-info-circle text-gray-400 mt-0.5"></i>
                    <p class="text-xs text-gray-500 leading-relaxed">
                        Data mahasiswa di atas muncul berdasarkan tabel <strong>penetapan dosen</strong>. Jika terdapat mahasiswa bimbingan yang tidak muncul, silakan hubungi Koordinator Program Studi untuk pembaruan data penetapan.
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection