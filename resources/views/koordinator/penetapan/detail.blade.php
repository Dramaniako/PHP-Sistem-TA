<x-app-layout title="Detail Proposal">
    {{-- Tambahkan Pesan Sukses/Error --}}
    @if(session('success'))
        <div class="absolute top-4 right-4 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50 animate-bounce">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="absolute top-4 right-4 bg-red-500 text-white px-4 py-2 rounded shadow-lg z-50">
            {{ session('error') }}
        </div>
    @endif

    <div class="min-h-screen bg-gray-100 p-6 flex gap-6 overflow-hidden h-screen">
        
        {{-- KOLOM KIRI: SIDEBAR --}}
        <div class="w-1/3 flex flex-col gap-4 h-full">
            
            {{-- 1. SEARCH BAR (FUNGSI CARI) --}}
            <div class="bg-white p-4 rounded-xl shadow-sm">
                <h3 class="font-bold text-gray-800 mb-2">Cari Mahasiswa</h3>
                {{-- Form GET ke route yang sama --}}
                <form action="{{ route('koordinator.penetapan.show', $proposal->id) }}" method="GET" class="relative">
                    <input type="text" name="search_sidebar" value="{{ request('search_sidebar') }}" 
                           placeholder="Ketik Nama / NIM..." 
                           class="w-full bg-gray-50 border-none rounded-lg py-2 pl-10 text-sm focus:ring-2 focus:ring-blue-500">
                    <span class="absolute left-3 top-2.5 text-gray-400"><i class="fas fa-search"></i></span>
                    <button type="submit" class="hidden"></button>
                </form>
            </div>

            {{-- 2. LIST PROPOSAL (DINAMIS DARI DATABASE) --}}
            <div class="flex-1 overflow-y-auto pr-2 space-y-3">
                <h4 class="text-xs font-bold text-gray-500 uppercase">Daftar Proposal</h4>
                
                @forelse($sidebarProposals as $p)
                    <a href="{{ route('koordinator.penetapan.show', $p->id) }}">
                        <div class="bg-white p-4 rounded-xl shadow-sm border-l-4 {{ $p->id == $proposal->id ? 'border-blue-500 bg-blue-50' : 'border-transparent hover:border-gray-200' }} cursor-pointer transition">
                            <div class="flex justify-between items-start mb-1">
                                <h5 class="font-bold text-gray-800">{{ $p->mahasiswa->name }}</h5>
                                <span class="text-[10px] bg-gray-100 px-2 py-0.5 rounded text-gray-500">{{ $p->mahasiswa->nim ?? 'NIM' }}</span>
                            </div>
                            <p class="text-xs text-gray-500 line-clamp-2 mb-2">"{{ $p->judul }}"</p>
                            
                            {{-- Status Badge Kecil --}}
                            <div class="flex justify-between items-center">
                                <p class="text-[10px] text-gray-400">{{ $p->created_at->format('d M') }}</p>
                                <span class="text-[10px] px-2 rounded-full 
                                    {{ $p->status == 'disetujui' ? 'bg-green-100 text-green-700' : 
                                     ($p->status == 'ditolak' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                    {{ ucfirst($p->status) }}
                                </span>
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="text-center text-gray-400 text-sm mt-4">Tidak ditemukan.</p>
                @endforelse
            </div>
        </div>

        {{-- KOLOM KANAN: DETAIL CONTENT --}}
        <div class="w-2/3 bg-white rounded-2xl shadow-lg p-8 overflow-y-auto h-full">
            <h3 class="text-lg font-bold text-gray-800 mb-6 border-l-4 border-gray-800 pl-3">Detail Proposal</h3>

            {{-- 3. DATA MAHASISWA & PRODI --}}
            <div class="bg-white border border-gray-100 rounded-xl p-6 flex items-center gap-6 mb-6 shadow-sm">
                <div class="w-20 h-20 bg-gray-200 rounded-xl overflow-hidden flex-shrink-0">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($proposal->mahasiswa->name) }}&background=random" class="w-full h-full object-cover">
                </div>
                <div class="flex-1">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase">Nama Mahasiswa</p>
                            <p class="font-bold text-lg text-gray-800">{{ $proposal->mahasiswa->name }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase">Program Studi</p>
                            <p class="font-bold text-gray-800">{{ $proposal->mahasiswa->prodi ?? 'Informatika' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-400 uppercase">Nomor Induk</p>
                            <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-sm font-mono">{{ $proposal->mahasiswa->nim ?? '-' }}</span>
                        </div>
                        <div>
                             <p class="text-xs font-bold text-gray-400 uppercase">Status Saat Ini</p>
                             <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-bold">{{ ucfirst($proposal->status) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. DOWNLOAD PROPOSAL (LOGIKA FILE SUDAH DIGABUNGKAN DI SINI) --}}
            <div class="mb-8">
                <h4 class="text-xs font-bold text-gray-400 uppercase mb-2"><i class="fas fa-bookmark mr-1"></i> Judul Proposal</h4>
                <h2 class="text-xl font-bold text-gray-900 leading-relaxed mb-4">"{{ $proposal->judul }}"</h2>

                @if($proposal->file_proposal && Storage::disk('public')->exists($proposal->file_proposal))
                    <div class="border border-gray-200 bg-white rounded-xl p-4 flex items-center justify-between hover:border-blue-400 transition shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="bg-red-100 p-3 rounded-lg text-red-500">
                                <i class="fas fa-file-pdf fa-2x"></i>
                            </div>
                            <div>
                                {{-- Nama File Otomatis --}}
                                <p class="font-bold text-gray-800 text-sm">
                                    Proposal_TA_{{ $proposal->mahasiswa->nim }}.pdf
                                </p>
                                {{-- Size & Waktu --}}
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ number_format(Storage::disk('public')->size($proposal->file_proposal) / 1024, 0) }} KB 
                                    • Diunggah {{ $proposal->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        
                        {{-- Tombol Download --}}
                        <a href="{{ route('koordinator.penetapan.download', $proposal->id) }}" class="group flex flex-col items-center text-gray-400 hover:text-blue-600 transition">
                            <div class="w-8 h-8 rounded-full bg-gray-100 group-hover:bg-blue-100 flex items-center justify-center mb-1">
                                <i class="fas fa-download"></i>
                            </div>
                            <span class="text-[10px] font-bold">Unduh</span>
                        </a>
                    </div>
                @else
                    <div class="border border-dashed border-red-200 bg-red-50 rounded-xl p-6 text-center">
                        <div class="text-red-400 mb-2"><i class="fas fa-exclamation-circle fa-2x"></i></div>
                        <p class="text-sm font-bold text-red-600">File dokumen tidak ditemukan</p>
                        <p class="text-xs text-red-500">Mahasiswa belum mengunggah file atau file telah dihapus.</p>
                    </div>
                @endif
                
                {{-- Deskripsi --}}
                <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-100">
                    <p class="text-xs font-bold text-gray-400 uppercase mb-1">Abstrak / Deskripsi</p>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $proposal->deskripsi }}</p>
                </div>
            </div>

            {{-- 5. FORM SIMPAN KEPUTUSAN --}}
            <div class="border-t pt-6">
                <form action="{{ route('koordinator.penetapan.keputusan', $proposal->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <h4 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wider">Formulir Review / Keputusan</h4>
                    
                    {{-- Pilihan Radio Button --}}
                    <div class="space-y-3 mb-6">
                        <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-green-50 transition has-[:checked]:border-green-500 has-[:checked]:bg-green-50">
                            <input type="radio" name="status_review" value="setuju" class="peer hidden" {{ $proposal->status == 'disetujui' ? 'checked' : '' }}>
                            <div class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center mr-4"><i class="fas fa-check"></i></div>
                            <div class="flex-1">
                                <span class="block font-bold text-gray-800">Setuju</span>
                                <span class="text-xs text-gray-500">Proposal diterima tanpa revisi</span>
                            </div>
                            <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:bg-green-500 peer-checked:border-green-500"></div>
                        </label>

                        <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-yellow-50 transition has-[:checked]:border-yellow-500 has-[:checked]:bg-yellow-50">
                            <input type="radio" name="status_review" value="revisi" class="peer hidden" {{ $proposal->status == 'revisi' ? 'checked' : '' }}>
                            <div class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center mr-4"><i class="fas fa-pen"></i></div>
                            <div class="flex-1">
                                <span class="block font-bold text-gray-800">Revisi</span>
                                <span class="text-xs text-gray-500">Perlu perbaikan</span>
                            </div>
                            <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:bg-yellow-500 peer-checked:border-yellow-500"></div>
                        </label>

                        <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-red-50 transition has-[:checked]:border-red-500 has-[:checked]:bg-red-50">
                            <input type="radio" name="status_review" value="tolak" class="peer hidden" {{ $proposal->status == 'ditolak' ? 'checked' : '' }}>
                            <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center mr-4"><i class="fas fa-times"></i></div>
                            <div class="flex-1">
                                <span class="block font-bold text-gray-800">Tolak</span>
                                <span class="text-xs text-gray-500">Proposal tidak layak</span>
                            </div>
                            <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:bg-red-500 peer-checked:border-red-500"></div>
                        </label>
                    </div>

                    {{-- Komentar --}}
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Komentar / Catatan</label>
                        <textarea name="komentar" class="w-full bg-gray-50 border-gray-200 rounded-xl p-4 text-sm focus:ring-blue-500 focus:border-blue-500" rows="4" placeholder="Tuliskan catatan...">{{ $proposal->komentar }}</textarea>
                    </div>

                    {{-- Tombol Simpan --}}
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 flex items-center gap-2 transition transform active:scale-95">
                            Simpan Keputusan <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>