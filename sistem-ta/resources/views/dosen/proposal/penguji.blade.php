<x-app-layout title="Daftar Mahasiswa yang Diuji">
    <div class="py-12 px-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6 border-l-4 border-blue-600 pl-3">Mahasiswa yang Diuji</h2>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Mahasiswa</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Judul Proposal</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($proposals as $p)
                            <tr>
                                <td class="px-6 py-4">
                                    <p class="font-bold text-gray-800">{{ $p->mahasiswa->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $p->mahasiswa->nim }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $p->judul }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('dosen.proposal.show', $p->id) }}" class="text-blue-600 hover:text-blue-900 text-sm font-bold">
                                        Lihat Detail ➡
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-6 py-4 text-center text-gray-400">Belum ada mahasiswa bimbingan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>