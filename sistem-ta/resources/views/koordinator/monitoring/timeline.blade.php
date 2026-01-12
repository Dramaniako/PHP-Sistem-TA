@extends('layouts.app')

@section('content')
    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-sm sm:rounded-2xl border border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Riwayat Perjalanan Tugas Akhir</h2>
                <p class="text-gray-500 mb-8 font-medium">{{ $mahasiswa->name }} ({{ $mahasiswa->nim ?? '-' }})</p>

                <div class="relative border-l-4 border-blue-500 ml-4 md:ml-6 space-y-10">
                    @forelse($riwayats as $log)
                        <div class="relative pl-10">
                            <div
                                class="absolute -left-[14px] top-1 w-6 h-6 bg-white border-4 border-blue-500 rounded-full shadow-sm">
                            </div>

                            <div class="bg-gray-50 p-5 rounded-2xl border border-gray-100 hover:shadow-md transition">
                                <div class="flex flex-wrap justify-between items-center gap-2 mb-3">
                                    <span
                                        class="px-3 py-1 bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest rounded-lg">
                                        {{ $log->tahap }}
                                    </span>
                                    <span class="text-xs font-bold text-gray-400">
                                        <i class="far fa-clock mr-1"></i> {{ $log->created_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                                <h4 class="font-bold text-gray-800 text-lg">{{ $log->aksi }}</h4>
                                <p class="text-sm text-gray-600 mt-2 leading-relaxed">{{ $log->keterangan }}</p>

                                @if($log->file_path)
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <a href="{{ asset('storage/' . $log->file_path) }}" target="_blank"
                                            class="inline-flex items-center text-xs font-bold text-blue-600 bg-blue-50 px-3 py-2 rounded-lg hover:bg-blue-100 transition">
                                            <i class="fas fa-file-download mr-2 text-sm"></i> LIHAT LAMPIRAN DOKUMEN
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="pl-10 text-gray-400 italic">Belum ada riwayat aktivitas tercatat.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection