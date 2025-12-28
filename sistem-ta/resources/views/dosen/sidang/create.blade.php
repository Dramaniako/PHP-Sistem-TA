<x-app-layout title="Buat Jadwal Sidang">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Jadwal Sidang Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Menampilkan Error Validasi --}}
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <strong class="font-bold">Ada Kesalahan Input!</strong>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('dosen.sidang.store') }}" method="POST">
                        @csrf

                        {{-- 1. Pilih Mahasiswa --}}
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Mahasiswa</label>
                            <select name="mahasiswa_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Cari Nama Mahasiswa --</option>
                                @foreach($mahasiswas as $mhs)
                                    <option value="{{ $mhs->id }}">{{ $mhs->name }} (NIM/Email: {{ $mhs->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 2. Pilih Dosen Penguji --}}
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Dosen Penguji</label>
                            <select name="dosen_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Cari Nama Dosen --</option>
                                @foreach($dosens as $dosen)
                                    <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 3. Judul & Jenis Sidang --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Judul Tugas Akhir</label>
                                <input type="text" name="judul_ta" value="{{ old('judul_ta') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Masukkan Judul Lengkap">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Jenis Sidang</label>
                                <select name="jenis_sidang" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="proposal">Seminar Proposal</option>
                                    <option value="seminar_hasil">Seminar Hasil</option>
                                    <option value="sidang_akhir">Sidang Akhir (Skripsi)</option>
                                </select>
                            </div>
                        </div>

                        {{-- 4. Waktu & Lokasi --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Sidang</label>
                                <input type="date" name="tanggal" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi / Ruangan</label>
                                <input type="text" name="lokasi" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Contoh: Ruang Sidang 1 / Zoom Meeting">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Jam Mulai</label>
                                <input type="time" name="jam_mulai" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Jam Selesai</label>
                                <input type="time" name="jam_selesai" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-lg">
                                Simpan Jadwal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>