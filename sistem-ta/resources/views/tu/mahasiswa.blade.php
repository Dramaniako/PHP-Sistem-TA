@extends('layouts.app')

@section('content')



{{-- KONTEN --}}
<div class="bg-gray-100 min-h-screen py-10 px-6">

    <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow p-6">

        <h1 class="text-xl font-semibold mb-6">Daftar Mahasiswa Tugas Akhir</h1>

        {{-- FILTER --}}
        <div class="flex gap-4 mb-6">
            <select class="bg-gray-100 rounded-xl px-4 py-2 text-sm">
                <option>Program Studi</option>
            </select>
            <select class="bg-gray-100 rounded-xl px-4 py-2 text-sm">
                <option>Semester</option>
            </select>
        </div>

        {{-- TABEL --}}
        <div class="overflow-x-auto">
            <table class="w-full border-separate border-spacing-y-2">

                <thead>
                    <tr class="bg-neutral-900 text-white text-sm">
                        <th class="py-3 rounded-l-xl">Nama Mahasiswa</th>
                        <th>NIM</th>
                        <th>Judul Tugas Akhir</th>
                        <th class="rounded-r-xl">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($mahasiswa as $mhs)
                    <tr class="bg-gray-50 text-sm text-center">
                        <td class="py-2 rounded-l-xl">{{ $mhs['nama'] }}</td>
                        <td>{{ $mhs['nim'] }}</td>
                        <td class="text-left px-3">{{ $mhs['judul'] }}</td>
                        <td class="rounded-r-xl">
                            <a href="{{ route('tu.mahasiswa.detail', $mhs['nim']) }}"
                            class="inline-block bg-neutral-900 text-white px-4 py-1.5 rounded-lg text-xs hover:bg-neutral-800">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection
