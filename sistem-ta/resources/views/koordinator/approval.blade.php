<x-app-layout title="Persetujuan Perubahan Jadwal">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Persetujuan Perubahan Jadwal Sidang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 p-8">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Antrean Reschedule</h3>
                        <p class="text-sm text-gray-500">Dosen mengajukan permohonan, Koordinator menetapkan jadwal melalui mode edit.</p>
                    </div>
                </div>

                @if($pengajuans->isEmpty())
                    <div class="text-center py-16 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                        <p class="text-gray-500 font-medium">Tidak ada pengajuan perubahan jadwal.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-100">
                            <thead>
                                <tr class="bg-gray-50/50">
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Mahasiswa</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Dosen Penguji</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Alasan & Dokumen</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-50">
                                @foreach($pengajuans as $item)
                                <tr class="hover:bg-blue-50/30 transition-colors">
                                    <td class="px-6 py-5">
                                        <div class="text-sm font-bold text-gray-900">{{ $item->sidangJadwal?->mahasiswa?->name }}</div>
                                        <div class="text-[11px] text-gray-400 italic">{{ $item->sidangJadwal?->judul_ta }}</div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span class="text-sm font-medium text-gray-700">{{ $item->sidangJadwal?->dosen?->name }}</span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex flex-col gap-2">
                                            <span class="text-xs text-gray-600 bg-gray-100 p-2 rounded-lg border italic">"{{ $item->alasan }}"</span>
                                            @if($item->file_pendukung)
                                                <a href="{{ asset('storage/' . $item->file_pendukung) }}" target="_blank" class="text-blue-600 hover:underline text-xs font-bold">
                                                    <i class="fas fa-file-pdf mr-1"></i> Download Surat Tugas
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <div class="flex justify-center gap-3">
                                            {{-- Link ke Halaman Edit dengan Parameter --}}
                                            <a href="{{ route('koordinator.sidang.edit', $item->sidangJadwal->id) }}?pengajuan_id={{ $item->id }}" 
                                               class="bg-blue-600 hover:bg-blue-700 text-white text-[11px] font-bold py-2 px-4 rounded-xl shadow-lg transition-all transform hover:-translate-y-0.5">
                                                <i class="fas fa-edit mr-1"></i> Atur Jadwal Baru
                                            </a>

                                            <form action="{{ route('koordinator.reject', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Tolak pengajuan ini?')" class="bg-white border border-red-200 text-red-500 hover:bg-red-50 text-[11px] font-bold py-2 px-4 rounded-xl transition-all">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>