@extends('layouts.app')

@section('content')

@include('layouts.header_ta')

<div class="min-h-screen py-10 px-4">

    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md p-8">

        <h1 class="text-lg font-medium text-gray-800 mb-6 text-center">
            Statistik Administrasi TA
        </h1>

        {{-- PENGATURAN PERIODE --}}
        <div class="mb-8 border-b border-gray-200 pb-6">
            <h2 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                <span class="w-2 h-2 bg-gray-800 rounded-full mr-3"></span>
                Pengaturan Periode TA
            </h2>

            <div class="flex gap-3">
                <select class="text-sm border rounded-md px-3 py-2">
                    <option>Semester</option>
                </select>
                <select class="text-sm border rounded-md px-3 py-2">
                    <option>Tahun</option>
                </select>
                <select class="text-sm border rounded-md px-3 py-2">
                    <option>Status</option>
                </select>
            </div>
        </div>

        {{-- GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

            {{-- KIRI: STATISTIK --}}
            <div class="space-y-4">
                <div class="flex justify-between text-sm text-gray-700">
                    <span>Mahasiswa Aktif</span>
                    <span class="font-semibold text-gray-900">144</span>
                </div>
                <div class="flex justify-between text-sm text-gray-700">
                    <span>Dokumen Lengkap</span>
                    <span class="font-semibold text-gray-900">96</span>
                </div>
                <div class="flex justify-between text-sm text-gray-700">
                    <span>Menunggu Validasi</span>
                    <span class="font-semibold text-gray-900">15</span>
                </div>
                <div class="flex justify-between text-sm text-gray-700">
                    <span>Belum Upload</span>
                    <span class="font-semibold text-gray-900">33</span>
                </div>

                <a href="#"
                   class="block mt-6 text-center bg-gray-900 hover:bg-gray-800 text-white text-sm font-semibold py-2.5 rounded-md">
                    Daftar Mahasiswa
                </a>
            </div>

            {{-- KANAN: LAPORAN --}}
            <div class="md:border-l md:pl-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">
                    Laporan Administrasi TA
                </h3>

                <div class="space-y-3">
                    @include('dashboard.partials.laporan_item', ['format'=>'PDF','filesize'=>'2.4 MB','icon_color'=>'bg-red-600'])
                    @include('dashboard.partials.laporan_item', ['format'=>'DOCX','filesize'=>'1.8 MB','icon_color'=>'bg-blue-600'])
                    @include('dashboard.partials.laporan_item', ['format'=>'CSV','filesize'=>'900 KB','icon_color'=>'bg-green-600'])
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
