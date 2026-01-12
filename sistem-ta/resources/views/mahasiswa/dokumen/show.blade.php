@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">{{ $jenis }}</h2>
                <a href="{{ route('mahasiswa.dokumen.index') }}" class="text-gray-500 hover:text-gray-700">← Kembali</a>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg mb-6 border-l-4 border-blue-500 text-sm text-gray-600">
                <strong>Deskripsi Singkat:</strong>
                <p class="mt-1">
                    Pastikan dokumen yang diunggah sudah lengkap dan sahih sesuai persyaratan.
                    Untuk Berita Acara dan Lembar Pengesahan, wajib berisi tanda tangan basah (asli) yang kemudian di-scan menjadi PDF.
                </p>
            </div>

            @php $data = $dokumens->where('jenis_dokumen', $jenis)->first(); @endphp

            <div class="mb-6">
                <h3 class="font-bold text-gray-700 mb-2">Respon Validator (Dosen)</h3>
                <div class="p-4 bg-gray-100 rounded border">
                    @if($data && $data->catatan_dosen)
                        <p class="text-gray-800">"{{ $data->catatan_dosen }}"</p>
                    @else
                        <p class="text-gray-400 italic">Belum ada catatan dari dosen.</p>
                    @endif
                </div>
            </div>

            <form action="{{ route('mahasiswa.dokumen.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="jenis_dokumen" value="{{ $jenis }}">

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Upload File (PDF, Maks 5MB)</label>
                    <input type="file" name="file" required class="w-full p-2 border rounded">
                    @if($data)
                        <p class="mt-2 text-xs text-blue-600">File lama: <a href="{{ asset('storage/'.$data->file_path) }}" target="_blank" class="underline">Lihat Dokumen Saat Ini</a></p>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Komentar Mahasiswa (Opsional)</label>
                    <textarea name="komen_mahasiswa" rows="3" class="w-full p-2 border rounded" placeholder="Tambahkan keterangan jika perlu...">{{ $data->komen_mahasiswa ?? '' }}</textarea>
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                        Simpan dan Serahkan File
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
