@extends('layouts.app')

@section('content')



{{-- KONTEN --}}
<div class="bg-gray-100 min-h-screen py-10 px-6">

    <div class="max-w-6xl mx-auto space-y-6">

        {{-- INFO MAHASISWA --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="bg-white rounded-2xl shadow p-6 space-y-4">
                <div>
                    <p class="text-sm text-gray-500">NIM</p>
                    <p class="font-semibold">{{ $mahasiswa['nim'] }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Nama Lengkap</p>
                    <p class="font-semibold">{{ $mahasiswa['nama'] }}</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow p-6 space-y-4">
                <div class="flex justify-between items-center">
                    <p class="font-semibold">Progres Kelengkapan</p>
                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-semibold">
                        {{ $mahasiswa['progres'] }}
                    </span>
                </div>

                <div class="flex gap-6 text-sm font-medium">
                    <span>✓ Lengkap</span>
                    <span>■ Menunggu</span>
                    <span>✕ Tidak Valid</span>
                </div>
            </div>

        </div>

        {{-- TABEL DOKUMEN --}}
        <div class="bg-white rounded-2xl shadow p-6 overflow-x-auto">

            <table class="w-full border-separate border-spacing-y-2">

                <thead>
                    <tr class="bg-neutral-900 text-white text-sm">
                        <th class="py-3 rounded-l-xl">Dokumen</th>
                        <th>Status</th>
                        <th>Validator</th>
                        <th class="rounded-r-xl">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($dokumen as $d)
                        <tr class="bg-gray-50 text-sm text-center">
                            <td class="py-2 text-left px-4 rounded-l-xl">
                                {{ $d['nama'] }}
                            </td>

                            <td>
                                @if ($d['status'] === 'lengkap')
                                    ✓
                                @elseif ($d['status'] === 'menunggu')
                                    ■
                                @else
                                    ✕
                                @endif
                            </td>

                            <td>{{ $d['validator'] }}</td>

                            <td class="rounded-r-xl">
                                <button class="bg-neutral-900 text-white px-4 py-1.5 rounded-lg text-xs">
                                    {{ $d['status'] === 'lengkap' ? 'Lihat' : 'Menunggu' }}
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>

        </div>

    </div>
</div>

@endsection
