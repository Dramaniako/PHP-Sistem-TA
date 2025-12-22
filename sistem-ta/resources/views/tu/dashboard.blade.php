@extends('layouts.app')

@section('content')



{{-- KONTEN --}}
<div class="bg-gray-100 min-h-screen py-12 px-6">

    <div class="max-w-6xl mx-auto space-y-6">

        {{-- JUDUL --}}
        <div class="bg-white rounded-2xl shadow px-6 py-4 text-center">
            <h1 class="text-2xl font-semibold">Statistik Administrasi TA</h1>
        </div>

        {{-- PENGATURAN PERIODE --}}
        <div class="bg-white rounded-2xl shadow px-6 py-6">
            <h2 class="flex items-center font-semibold mb-4">
                <span class="w-2 h-2 bg-black rounded-full mr-3"></span>
                Pengaturan Periode TA
            </h2>

            <div class="flex gap-4">

                {{-- SEMESTER --}}
                <details class="relative w-40">
                    <summary class="cursor-pointer bg-gray-100 rounded-xl px-4 py-2 text-gray-600 flex justify-between items-center">
                        Semester
                        <span>⌄</span>
                    </summary>
                    <div class="absolute z-10 mt-2 bg-white rounded-xl shadow w-full max-h-48 overflow-y-auto">
                        @for ($i = 1; $i <= 16; $i++)
                            <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                Semester {{ $i }}
                            </div>
                        @endfor
                    </div>
                </details>

                {{-- TAHUN --}}
                <details class="relative w-40">
                    <summary class="cursor-pointer bg-gray-100 rounded-xl px-4 py-2 text-gray-600 flex justify-between items-center">
                        Tahun
                        <span>⌄</span>
                    </summary>
                    <div class="absolute z-10 mt-2 bg-white rounded-xl shadow w-full max-h-48 overflow-y-auto">
                        @for ($tahun = 2018; $tahun <= 2025; $tahun++)
                            <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                {{ $tahun }}
                            </div>
                        @endfor
                    </div>
                </details>

                {{-- STATUS --}}
                <details class="relative w-40">
                    <summary class="cursor-pointer bg-gray-100 rounded-xl px-4 py-2 text-gray-600 flex justify-between items-center">
                        Status
                        <span>⌄</span>
                    </summary>
                    <div class="absolute z-10 mt-2 bg-white rounded-xl shadow w-full">
                        <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                            Aktif
                        </div>
                        <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                            Tidak Aktif
                        </div>
                    </div>
                </details>

            </div>
        </div>


        {{-- GRID UTAMA --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            {{-- KOLOM KIRI --}}
            <div class="space-y-4">
                @foreach ([
                    'Mahasiswa Aktif',
                    'Dokumen Lengkap',
                    'Menunggu Validasi',
                    'Belum Upload'
                ] as $item)
                    <div class="bg-white rounded-2xl shadow px-6 py-4 flex justify-between">
                        <span class="font-medium">{{ $item }}</span>
                        <span class="text-gray-400">-</span>
                    </div>
                @endforeach

                <a href="{{ route('tu.mahasiswa') }}"
                class="mt-6 w-full block text-center bg-neutral-900 text-white py-3 rounded-2xl shadow hover:bg-neutral-800 transition">
                    Daftar Mahasiswa
                </a>
            </div>

            {{-- KOLOM KANAN --}}
            <div class="bg-white rounded-2xl shadow px-6 py-6">
                <h2 class="flex items-center font-semibold mb-6">
                    <span class="w-2 h-2 bg-black rounded-full mr-3"></span>
                    Laporan Administrasi TA
                </h2>

                <div class="space-y-4">

                    {{-- ITEM --}}
                    <div class="flex justify-between items-center bg-gray-50 rounded-2xl p-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-red-100 text-red-600 font-bold rounded-xl flex items-center justify-center">
                                PDF
                            </div>
                            <div>
                                <p class="font-medium">Laporan_TA_Semester_TA</p>
                                <p class="text-sm text-gray-500">2.4 MB · Export PDF</p>
                            </div>
                        </div>
                        ⬇️
                    </div>

                    <div class="flex justify-between items-center bg-gray-50 rounded-2xl p-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-100 text-blue-600 font-bold rounded-xl flex items-center justify-center">
                                DOCX
                            </div>
                            <div>
                                <p class="font-medium">Laporan_TA_Semester_TA</p>
                                <p class="text-sm text-gray-500">2.4 MB · Export DOCX</p>
                            </div>
                        </div>
                        ⬇️
                    </div>

                    <div class="flex justify-between items-center bg-gray-50 rounded-2xl p-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-green-100 text-green-600 font-bold rounded-xl flex items-center justify-center">
                                CSV
                            </div>
                            <div>
                                <p class="font-medium">Laporan_TA_Semester_TA</p>
                                <p class="text-sm text-gray-500">2.4 MB · Export CSV</p>
                            </div>
                        </div>
                        ⬇️
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>

@endsection
