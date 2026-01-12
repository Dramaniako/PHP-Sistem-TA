@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold">Daftar Dokumen Mahasiswa</h2>
                    <p class="text-gray-600">Nama: {{ $mahasiswa->name }} | NIM: {{ $mahasiswa->nim ?? '-' }}</p>
                </div>
                <a href="{{ route('dosen.validasi.index') }}" class="text-gray-500 hover:text-gray-700">← Kembali ke Daftar Mahasiswa</a>
            </div>

            @foreach($tahapan as $namaTahap => $listJenis)
                <h3 class="font-bold text-lg mt-8 mb-3 text-gray-700 border-b pb-2">{{ $namaTahap }}</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jenis Dokumen</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Komentar Mhs</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($listJenis as $jenis)
                                @php $doc = $dokumens->where('jenis_dokumen', $jenis)->first(); @endphp
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $jenis }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $doc && $doc->komen_mahasiswa ? Str::limit($doc->komen_mahasiswa, 30) : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        @if($doc)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $doc->status == 'Disetujui' ? 'bg-green-100 text-green-800' : 
                                                   ($doc->status == 'Ditolak' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ $doc->status }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 italic text-xs">Belum Upload</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm font-medium">
                                        @if($doc)
                                            <a href="{{ route('dosen.validasi.dokumen.show', $doc->id) }}" class="text-blue-600 hover:text-blue-900">Validasi</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection