<x-app-layout title="Monitoring Bimbingan">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Bimbingan Saya</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                {{-- Search Bar --}}
                <div class="flex justify-between mb-4">
                    <h3 class="font-bold text-lg">Mahasiswa Bimbingan</h3>
                    <form method="GET" class="flex gap-2">
                        <input type="text" name="search" placeholder="Cari Mahasiswa..." class="border border-gray-300 rounded px-2 py-1">
                        <button type="submit" class="bg-gray-800 text-white px-3 py-1 rounded">Cari</button>
                    </form>
                </div>

                <table class="min-w-full border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Mahasiswa</th>
                            <th class="px-4 py-2 text-left">Judul</th>
                            <th class="px-4 py-2 text-left">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($proposals as $p)
                            <tr class="border-t">
                                <td class="px-4 py-2 font-bold">{{ $p->mahasiswa->name }}</td>
                                <td class="px-4 py-2">{{ $p->judul }}</td>
                                <td class="px-4 py-2">
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">
                                        {{ $p->status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-4 py-2 text-center">Tidak ada mahasiswa bimbingan.</td></tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>