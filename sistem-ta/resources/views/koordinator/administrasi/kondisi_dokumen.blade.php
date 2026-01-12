@extends('layouts.app')
@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-8 shadow-sm sm:rounded-lg">
            <h2 class="text-xl font-bold mb-2">Kondisi Dokumen: {{ $mhs->name }}</h2>
            <p class="text-gray-600 mb-6">Judul: {{ $mhs->penetapan->judul ?? '-' }}</p>

            <div class="space-y-4">
                @foreach($mhs->dokumens as $doc)
                <div class="flex justify-between items-center border-b pb-2">
                    <span class="text-sm">{{ $doc->jenis_dokumen }}</span>
                    <span class="px-3 py-1 rounded-full text-xs font-bold 
                        {{ $doc->status == 'Disetujui' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $doc->status }}
                    </span>
                </div>
                @endforeach
            </div>
            <div class="mt-8 text-center">
                <a href="{{ route('koordinator.monitoring.statistik.detail') }}" class="text-blue-600">← Kembali ke Daftar</a>
            </div>
        </div>
    </div>
</div>
@endsection