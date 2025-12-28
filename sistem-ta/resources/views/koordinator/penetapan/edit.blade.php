<x-app-layout title="Proses Penetapan Dosen">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Penetapan Dosen Pembimbing & Penguji</h2>
    </x-slot>

    {{-- Load Select2 CSS --}}
    <head>
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>.select2-container .select2-selection--single { height: 42px !important; display: flex; align-items: center; border-color: #d1d5db !important; }</style>
    </head>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                {{-- Form mengarah ke UPDATE --}}
                <form action="{{ route('koordinator.penetapan.update', $proposal->id) }}" method="POST">
                    @csrf
                    @method('PUT') {{-- PENTING UNTUK UPDATE --}}
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        {{-- Nama Mahasiswa (Otomatis Terisi & Readonly) --}}
                        <div>
                            <label class="block font-bold mb-2 text-gray-500">Mahasiswa</label>
                            <input type="text" value="{{ $proposal->mahasiswa->name }} ({{ $proposal->mahasiswa->email }})" 
                                   class="w-full bg-gray-100 border-gray-300 rounded text-gray-600 cursor-not-allowed" readonly>
                        </div>

                        {{-- Tanggal Pengajuan --}}
                        <div>
                            <label class="block font-bold mb-2 text-gray-500">Tanggal Pengajuan</label>
                            <input type="text" value="{{ $proposal->created_at->format('d F Y') }}" 
                                   class="w-full bg-gray-100 border-gray-300 rounded text-gray-600 cursor-not-allowed" readonly>
                        </div>
                    </div>

                    {{-- Judul Proposal (Otomatis Terisi & Readonly) --}}
                    <div class="mb-6">
                        <label class="block font-bold mb-2 text-gray-500">Judul Proposal</label>
                        <textarea rows="3" class="w-full bg-gray-100 border-gray-300 rounded text-gray-600 cursor-not-allowed" readonly>{{ $proposal->judul }}</textarea>
                    </div>

                    <hr class="mb-6">
                    <h3 class="font-bold text-lg mb-4 text-blue-600">Tetapkan Dosen</h3>

                    {{-- Dropdown Dosen (Select2) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block font-bold mb-2">Pilih Pembimbing</label>
                            <select name="dosen_pembimbing_id" class="w-full select2-search" required>
                                <option value="">-- Cari Dosen --</option>
                                @foreach($dosens as $dosen)
                                    <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold mb-2">Pilih Penguji</label>
                            <select name="dosen_penguji_id" class="w-full select2-search" required>
                                <option value="">-- Cari Dosen --</option>
                                @foreach($dosens as $dosen)
                                    <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('koordinator.penetapan.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan Penetapan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script Select2 --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script> $(document).ready(function() { $('.select2-search').select2({ width: '100%', placeholder: "Ketik nama dosen..." }); }); </script>
</x-app-layout>