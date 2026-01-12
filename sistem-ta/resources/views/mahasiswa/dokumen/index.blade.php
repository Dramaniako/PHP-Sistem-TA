@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Dokumen Administrasi Tugas Akhir</h2>
            
            <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                <span class="text-sm font-medium text-blue-700">Progres TA: {{ number_format($progres) }}%</span>
                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $progres }}%"></div>
                </div>
            </div>

            @php
                $tahapan = [
                    'Tahap Pelaksanaan & Bimbingan' => ['Formulir Bimbingan (Logbook)', 'Bukti Keikutsertaan Seminar Nasional', 'Bukti Publikasi Ilmiah'],
                    'Tahap Ujian Tugas Akhir (Skripsi)' => ['Naskah Skripsi/Laporan TA', 'Persetujuan Dosen PA', 'Berkas Kelengkapan Ujian'],
                    'Tahap Akhir (Pasca Ujian)' => ['Laporan TA Hasil Revisi', 'Lembar Pengesahan TA', 'Berkas untuk SKL']
                ];
            @endphp

            @foreach($tahapan as $namaTahap => $listDokumen)
                <h3 class="font-bold text-lg mt-6 mb-2 text-gray-700 border-b pb-2">{{ $namaTahap }}</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 mb-4">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Dokumen</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($listDokumen as $jenis)
                                @php $data = $dokumens->where('jenis_dokumen', $jenis)->first(); @endphp
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $jenis }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ !$data ? 'bg-gray-100 text-gray-800' : 
                                               ($data->status == 'Disetujui' ? 'bg-green-100 text-green-800' : 
                                               ($data->status == 'Ditolak' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                            {{ $data->status ?? 'Belum ada' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium">
                                        <a href="{{ route('mahasiswa.dokumen.show', ['jenis' => $jenis]) }}" class="text-blue-600 hover:text-blue-900">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection