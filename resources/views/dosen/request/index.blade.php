<x-app-layout title="Permintaan Kesediaan">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Permintaan Menjadi Pembimbing/Penguji') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 text-green-700 p-4 rounded-lg">{{ session('success') }}</div>
            @endif

            <div class="grid gap-6">
                @forelse($requests as $req)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="inline-block px-3 py-1 text-xs font-bold text-blue-600 bg-blue-100 rounded-full mb-2 uppercase">
                                    Permintaan Sebagai {{ str_replace('_', ' ', ucfirst($req->role)) }}
                                </span>
                                <h3 class="text-lg font-bold text-gray-900">{{ $req->proposal->judul }}</h3>
                                <p class="text-gray-600 mt-1">Mahasiswa: <span class="font-bold">{{ $req->proposal->mahasiswa->name }}</span> ({{ $req->proposal->mahasiswa->nim }})</p>
                                <p class="text-sm text-gray-500 mt-2 italic">"{{ Str::limit($req->proposal->deskripsi, 150) }}"</p>
                            </div>
                            
                            <div class="flex flex-col gap-2 w-48">
                                {{-- Form Terima --}}
                                <form action="{{ route('dosen.request.respond', $req->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="terima">
                                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition">
                                        ✓ Bersedia
                                    </button>
                                </form>

                                {{-- Tombol Tolak (Memicu Modal/Form Sederhana) --}}
                                <form action="{{ route('dosen.request.respond', $req->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="action" value="tolak">
                                    <div class="mt-2">
                                        <input type="text" name="alasan" placeholder="Alasan menolak..." class="w-full text-xs border-gray-300 rounded mb-1" required>
                                        <button type="submit" class="w-full bg-red-100 hover:bg-red-200 text-red-600 font-bold py-1 px-4 rounded text-xs transition">
                                            ✕ Tolak
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 bg-white rounded-lg shadow-sm">
                        <p class="text-gray-500">Tidak ada permintaan baru saat ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>