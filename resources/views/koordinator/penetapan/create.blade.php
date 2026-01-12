<x-app-layout title="Penetapan Dosen">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Buat Penetapan Baru</h2>
    </x-slot>

    {{-- Tambahkan CSS Select2 --}}
    <head>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            /* Fix styling Select2 agar sesuai Tailwind */
            .select2-container .select2-selection--single {
                height: 42px !important;
                border-color: #d1d5db !important; /* gray-300 */
                border-radius: 0.375rem !important; /* rounded-md */
                display: flex;
                align-items: center;
            }
        </style>
    </head>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="{{ route('koordinator.penetapan.store') }}" method="POST">
                    @csrf
                    
                    {{-- Judul --}}
                    <div class="mb-4">
                        <label class="block font-bold mb-2">Judul Proposal</label>
                        <input type="text" name="judul" class="w-full border-gray-300 rounded shadow-sm" required placeholder="Masukkan Judul Proposal...">
                    </div>

                    {{-- Mahasiswa (SEARCHABLE) --}}
                    <div class="mb-4">
                        <label class="block font-bold mb-2">Pilih Mahasiswa</label>
                        <select name="mahasiswa_id" class="w-full select2-search">
                            <option value="">-- Cari Nama Mahasiswa --</option>
                            @foreach($mahasiswas as $mhs)
                                <option value="{{ $mhs->id }}">{{ $mhs->name }} ({{ $mhs->nim ?? 'NIM' }})</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Dropdown Pembimbing & Penguji --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-bold mb-2">Dosen Pembimbing</label>
                            <select name="dosen_pembimbing_id" class="w-full select2-search">
                                <option value="">-- Cari Dosen --</option>
                                @foreach($dosens as $dosen)
                                    <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold mb-2">Dosen Penguji</label>
                            <select name="dosen_penguji_id" class="w-full select2-search">
                                <option value="">-- Cari Dosen --</option>
                                @foreach($dosens as $dosen)
                                    <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-2">
                        <a href="{{ route('koordinator.penetapan.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script Select2 --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Aktifkan Select2 pada class .select2-search
            $('.select2-search').select2({
                width: '100%',
                placeholder: "Ketik untuk mencari..."
            });
        });
    </script>

</x-app-layout>