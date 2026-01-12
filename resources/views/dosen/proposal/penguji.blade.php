<x-app-layout title="Daftar Jadwal Menguji">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-bold text-lg mb-4">Daftar Mahasiswa Ujian</h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">Mahasiswa</th>
                                    <th class="px-6 py-3">Judul Proposal</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($proposals as $proposal)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            {{ $proposal->mahasiswa->name }}<br>
                                            <span class="text-xs text-gray-500">{{ $proposal->mahasiswa->nim }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ Str::limit($proposal->judul, 50) }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($proposal->status == 'disetujui')
                                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Lulus</span>
                                            @elseif($proposal->status == 'revisi')
                                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Revisi</span>
                                            @elseif($proposal->status == 'ditolak')
                                                <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">Ditolak</span>
                                            @else
                                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">Pending</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            {{-- TOMBOL LIHAT DETAIL --}}
                                            {{-- Tetap mengarah ke 'dosen.proposal.show' --}}
                                            <a href="{{ route('dosen.proposal.show', $proposal->id) }}" 
                                               class="text-white bg-purple-700 hover:bg-purple-800 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-xs px-4 py-2">
                                                <i class="fas fa-eye mr-1"></i> Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            Belum ada jadwal menguji.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $proposals->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>