@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Validasi: {{ $dokumen->jenis_dokumen }}</h2>
                <p class="text-gray-600">Mahasiswa: <strong>{{ $dokumen->mahasiswa->name }}</strong></p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="border rounded-lg p-2 bg-gray-100">
                    <h3 class="font-bold mb-2">Preview Dokumen:</h3>
                    <iframe src="{{ asset('storage/' . $dokumen->file_path) }}" class="w-full h-96" frameborder="0"></iframe>
                    <div class="mt-2 text-center">
                        <a href="{{ asset('storage/' . $dokumen->file_path) }}" target="_blank" class="text-blue-600 underline text-sm">Buka di Tab Baru</a>
                    </div>
                </div>

                <div>
                    <div class="mb-6">
                        <h3 class="font-bold text-gray-700">Komentar Mahasiswa:</h3>
                        <p class="p-3 bg-blue-50 border border-blue-100 rounded italic mt-1">
                            {{ $dokumen->komen_mahasiswa ?? 'Tidak ada komentar.' }}
                        </p>
                    </div>

                    <form action="{{ route('dosen.validasi.dokumen.update', $dokumen->id) }}" method="POST">
                        @csrf @method('PATCH')
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2 text-red-600">Keputusan Validasi:</label>
                            <div class="flex gap-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="status" value="Disetujui" class="form-radio text-green-600" {{ $dokumen->status == 'Disetujui' ? 'checked' : '' }}>
                                    <span class="ml-2">Setujui</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="status" value="Ditolak" class="form-radio text-red-600" {{ $dokumen->status == 'Ditolak' ? 'checked' : '' }}>
                                    <span class="ml-2">Tolak</span>
                                </label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 font-bold mb-2">Catatan/Alasan:</label>
                            <textarea name="catatan_dosen" rows="4" class="w-full border rounded p-2" placeholder="Tulis alasan jika ditolak atau catatan tambahan...">{{ $dokumen->catatan_dosen }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">*Wajib diisi jika status dokumen "Ditolak".</p>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded font-bold hover:bg-green-700">
                                Simpan Validasi
                            </button>
                            <a href="{{ route('dosen.validasi.mahasiswa', $dokumen->mahasiswa_id) }}" class="bg-gray-500 text-white px-6 py-2 rounded font-bold hover:bg-gray-600 text-center">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection