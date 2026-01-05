<x-app-layout title="Approval Reschedule">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Persetujuan Perubahan Jadwal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if($pengajuans->isEmpty())
                        <div class="text-center py-10 text-gray-500">
                            <i class="fas fa-check-circle text-4xl mb-3 text-green-200"></i>
                            <p>Tidak ada permintaan perubahan jadwal yang pending.</p>
                        </div>
                    @else
                        <div class="grid gap-6">
                            @foreach($pengajuans as $p)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition bg-gray-50">
                                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                    
                                    <div class="flex-1">
                                        {{-- Menggunakan sidangJadwal --}}
                                        <h3 class="font-bold text-lg text-blue-900">
                                            {{ $p->sidangJadwal?->mahasiswa?->name ?? 'Mahasiswa tidak ditemukan' }}
                                        </h3>
                                        <p class="text-xs text-gray-500 mb-2">
                                            Judul: {{ $p->sidangJadwal?->judul_ta ?? '-' }}
                                        </p>
                                        
                                        <div class="flex items-center gap-4 text-sm mt-3">
                                            <div class="bg-red-50 text-red-700 px-3 py-1 rounded border border-red-200">
                                                <span class="block text-xs font-bold text-red-400">JADWAL LAMA</span>
                                                {{ $p->sidangJadwal?->tanggal ? \Carbon\Carbon::parse($p->sidangJadwal->tanggal)->isoFormat('D MMMM Y') : '-' }} <br> 
                                                ({{ $p->sidangJadwal?->jam_mulai ?? '-' }})
                                            </div>
                                            <i class="fas fa-arrow-right text-gray-400"></i>
                                            <div class="bg-green-50 text-green-700 px-3 py-1 rounded border border-green-200">
                                                <span class="block text-xs font-bold text-green-400">USULAN BARU</span>
                                                {{ \Carbon\Carbon::parse($p->tanggal_saran)->isoFormat('D MMMM Y') }} <br> 
                                                ({{ $p->jam_saran }})
                                            </div>
                                        </div>

                                        <div class="mt-3 text-sm italic text-gray-600 bg-white p-2 rounded border border-dashed border-gray-300">
                                            "Alasan: {{ $p->alasan }}"
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-2 w-full md:w-auto">
                                        <form action="{{ route('koordinator.proses_approval', $p->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="keputusan" value="terima">
                                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                                                <i class="fas fa-check mr-1"></i> Setujui Perubahan
                                            </button>
                                        </form>

                                        <form action="{{ route('koordinator.proses_approval', $p->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="keputusan" value="tolak">
                                            <button type="submit" class="w-full bg-white border border-red-300 text-red-600 hover:bg-red-50 font-bold py-2 px-4 rounded shadow">
                                                <i class="fas fa-times mr-1"></i> Tolak
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>