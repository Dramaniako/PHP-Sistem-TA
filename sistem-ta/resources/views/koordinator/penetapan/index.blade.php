<x-app-layout title="Daftar Penetapan">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Penetapan Tugas Akhir') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if(session('success'))
                    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('success') }}</div>
                @endif

                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <div class="text-gray-700 font-bold text-lg">Antrean Judul Masuk</div>
                    <form method="GET" action="{{ route('koordinator.penetapan.index') }}" class="w-full md:w-1/3 flex">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Judul / Nama Mahasiswa..." class="w-full border-gray-300 rounded-l-md focus:ring-blue-500 focus:border-blue-500">
                        <button type="submit" class="bg-gray-800 text-white px-4 rounded-r-md hover:bg-gray-700">🔍</button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full border leading-normal">
                        <thead class="bg-gray-100 border-b">
                            <tr>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Judul Proposal</th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pembimbing</th>
                                <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tim Penguji</th>
                                <th class="px-5 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi / Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($proposals as $p)
                                @php
                                    // Mengambil semua request dari tabel dosen_requests
                                    $allReqs = $p->dosenRequests;
                                    $pembimbingReq = $allReqs->where('role', 'pembimbing')->first();
                                    $pengujiReqs = $allReqs->filter(fn($r) => str_contains($r->role, 'penguji'));

                                    // Cek apakah sudah ada penetapan awal
                                    $sudahAdaRequest = $allReqs->count() > 0;
                                    $adaPenolakan = $allReqs->contains('status', 'rejected');
                                    $adaPending = $allReqs->contains('status', 'pending');
                                @endphp

                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-5 py-4 text-sm">
                                        <div class="font-bold text-gray-900">{{ $p->mahasiswa->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $p->mahasiswa->nim }}</div>
                                    </td>
                                    <td class="px-5 py-4 text-sm text-gray-700 italic">"{{ Str::limit($p->judul, 40) }}"</td>
                                    
                                    {{-- Kolom Pembimbing --}}
                                    <td class="px-5 py-4 text-sm text-gray-600">
                                        @if($pembimbingReq)
                                            <div class="flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full {{ $pembimbingReq->status == 'accepted' ? 'bg-green-500' : ($pembimbingReq->status == 'rejected' ? 'bg-red-500' : 'bg-yellow-500') }}"></span>
                                                <span class="{{ $pembimbingReq->status == 'rejected' ? 'line-through text-red-400' : '' }}">
                                                    {{ $pembimbingReq->dosen->name ?? '-' }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">Belum dipilih</span>
                                        @endif
                                    </td>

                                    {{-- Kolom Tim Penguji --}}
                                    <td class="px-5 py-4 text-sm text-gray-600">
                                        @if($pengujiReqs->count() > 0)
                                            <ul class="space-y-1">
                                                @foreach($pengujiReqs as $req)
                                                    <li class="flex items-center gap-2">
                                                        <span class="w-1.5 h-1.5 rounded-full {{ $req->status == 'accepted' ? 'bg-green-500' : ($req->status == 'rejected' ? 'bg-red-500' : 'bg-yellow-500') }}"></span>
                                                        <span class="{{ $req->status == 'rejected' ? 'line-through text-red-400' : '' }}">
                                                            {{ $req->dosen->name ?? '-' }}
                                                        </span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <span class="text-gray-400 italic">Belum dipilih</span>
                                        @endif
                                    </td>

                                    <td class="px-5 py-4 text-sm text-center">
                                        <div class="flex flex-col items-center gap-1">
                                            @if(!$sudahAdaRequest)
                                                <a href="{{ route('koordinator.penetapan.edit', $p->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded text-xs">
                                                    ⚙️ Tetapkan Dosen
                                                </a>
                                            @elseif($adaPenolakan)
                                                <span class="px-2 py-0.5 font-semibold leading-tight text-red-700 bg-red-100 rounded-full text-[10px]">
                                                    ✘ Ditolak Dosen
                                                </span>
                                                <a href="{{ route('koordinator.penetapan.edit', $p->id) }}" class="text-[10px] text-blue-600 font-bold hover:underline">Ubah Tim</a>
                                            @elseif($adaPending)
                                                <span class="px-2 py-0.5 font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full text-[10px]">
                                                    🕒 Menunggu Respon
                                                </span>
                                                <a href="{{ route('koordinator.penetapan.edit', $p->id) }}" class="text-[10px] text-gray-500 hover:underline">Ubah Tim</a>
                                            @else
                                                <span class="px-2 py-0.5 font-semibold leading-tight text-green-700 bg-green-100 rounded-full text-[10px]">
                                                    ✓ Ditetapkan
                                                </span>
                                                <a href="{{ route('koordinator.penetapan.edit', $p->id) }}" class="text-[10px] text-blue-600 hover:underline">Ubah Tim</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-8 text-center text-gray-500">📂 Belum ada data masuk.</td>
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
</x-app-layout>