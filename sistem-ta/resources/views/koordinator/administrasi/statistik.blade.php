@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
            
            <div class="flex items-center justify-between mb-8 border-b pb-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Statistik Administrasi Tugas Akhir</h2>
                    <p class="text-sm text-gray-500">Ringkasan data progres kelengkapan dokumen mahasiswa secara real-time.</p>
                </div>
                <div class="text-right">
                    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-blue-600 bg-blue-200 last:mr-0 mr-1">
                        Tahun Akademik {{ date('Y') }}/{{ date('Y') + 1 }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <div class="relative flex flex-col min-w-0 break-words bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="flex-auto p-6">
                        <div class="flex flex-row">
                            <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                <h5 class="text-gray-400 uppercase font-bold text-xs">Total Mahasiswa</h5>
                                <span class="font-bold text-3xl text-gray-800">{{ $totalMhs }}</span>
                                <p class="text-sm text-gray-500 mt-1">Mahasiswa terdaftar dalam sistem</p>
                            </div>
                            <div class="relative w-auto pl-4 flex-initial">
                                <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative flex flex-col min-w-0 break-words bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-300">
                    <div class="flex-auto p-6">
                        <div class="flex flex-row">
                            <div class="relative w-full pr-4 max-w-full flex-grow flex-1">
                                <h5 class="text-gray-400 uppercase font-bold text-xs">Administrasi Lengkap</h5>
                                <span class="font-bold text-3xl text-green-600">{{ $mhsLengkap }}</span>
                                <p class="text-sm text-gray-500 mt-1">Mahasiswa dengan 9/9 dokumen disetujui</p>
                            </div>
                            <div class="relative w-auto pl-4 flex-initial">
                                <div class="text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-green-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 border-t pt-4">
                            <a href="{{ route('koordinator.monitoring.statistik.detail') }}" class="inline-flex items-center text-sm font-semibold text-green-700 hover:text-green-900 transition-colors">
                                Lihat Detail Mahasiswa
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-10 p-5 bg-blue-50 rounded-lg border border-blue-100">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Catatan Sistem Administrasi</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Data "Administrasi Lengkap" dihitung berdasarkan validasi dosen terhadap 9 jenis dokumen wajib.</li>
                                <li>Gunakan fitur <strong>Detail</strong> untuk melihat judul TA dan kondisi dokumen per individu.</li>
                                <li>Jika administrasi sudah lengkap, Koordinator/TU dapat mencetak berkas melalui menu Monitoring.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end">
                <a href="{{ route('koordinator.monitoring.index') }}" class="bg-gray-800 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-700 transition duration-200 shadow-sm">
                    Kembali ke Monitoring Utama
                </a>
            </div>

        </div>
    </div>
</div>
@endsection