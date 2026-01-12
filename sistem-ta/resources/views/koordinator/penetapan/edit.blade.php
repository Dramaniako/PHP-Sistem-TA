<x-app-layout title="Penetapan Dosen">
    
    {{-- LOGIKA PHP: Ambil Request Terakhir untuk Pembimbing & Penguji --}}
    @php
    $reqPembimbing = App\Models\DosenRequest::where('proposal_id', $proposal->id)
                        ->where('role', 'pembimbing')
                        ->latest()
                        ->first();

    // Variabel ini yang akan kita gunakan di seluruh halaman
    $reqPengujiList = App\Models\DosenRequest::where('proposal_id', $proposal->id)
                        ->where('role', 'like', 'penguji%')
                        ->get();
    @endphp

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800">Status Penetapan</h2>
                <div class="mt-2 inline-flex items-center px-4 py-2 bg-blue-50 border border-blue-200 rounded-lg text-blue-700 text-sm font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Info Sistem: <span class="ml-2 font-normal">Sistem akan mengirim notifikasi permintaan kesediaan ke Dosen yang Anda pilih.</span>
                </div>
            </div>

            <form action="{{ route('koordinator.penetapan.update', $proposal->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- CARD 1: DATA MAHASISWA (Read Only) --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 border-l-4 border-gray-800 pl-3">Data Mahasiswa</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- NIM --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">NIM</label>
                            <div class="flex items-center bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                                <span class="text-gray-400 mr-3"><i class="fas fa-id-card"></i></span>
                                <input type="text" value="{{ $proposal->mahasiswa->nim ?? '-' }}" class="bg-transparent border-none w-full text-gray-700 font-semibold focus:ring-0" readonly>
                            </div>
                        </div>

                        {{-- Nama Lengkap --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Nama Lengkap</label>
                            <div class="flex items-center bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                                <span class="text-gray-400 mr-3"><i class="fas fa-user"></i></span>
                                <input type="text" value="{{ $proposal->mahasiswa->name }}" class="bg-transparent border-none w-full text-gray-700 font-semibold focus:ring-0" readonly>
                            </div>
                        </div>

                        {{-- Tanggal Pengajuan --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Tanggal Pengajuan</label>
                            <div class="flex items-center bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                                <span class="text-gray-400 mr-3"><i class="fas fa-calendar-alt"></i></span>
                                <input type="text" value="{{ $proposal->created_at->format('d M Y') }}" class="bg-transparent border-none w-full text-gray-700 font-semibold focus:ring-0" readonly>
                            </div>
                        </div>
                    </div>

                    {{-- Judul Proposal --}}
                    <div class="mt-6">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Judul Proposal</label>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-gray-700 italic">
                            "{{ $proposal->judul }}"
                        </div>
                    </div>
                </div>

                {{-- CARD 2: DOSEN PENGUJI (Form Input) --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 mb-20">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 border-l-4 border-gray-800 pl-3">Dosen Pembimbing & Penguji</h3>

                    {{-- ==================================================== --}}
                    {{-- BAGIAN PEMBIMBING --}}
                    {{-- ==================================================== --}}
                    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-4 hover:shadow-md transition">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
                            <div class="md:col-span-2 pt-2">
                                <span class="text-xs font-bold text-gray-400 uppercase">Peran</span>
                                <p class="font-bold text-blue-600 mt-1">Pembimbing Utama</p>
                            </div>
                            <div class="md:col-span-6">
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Pilih Dosen untuk Mengirim Request</label>
                                <select name="dosen_pembimbing_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">-- Pilih Dosen --</option>
                                    @foreach($dosens as $dosen)
                                        {{-- Logic Selected: Jika ada request pending, pilih dosen itu. Jika sudah fix, pilih dari proposal --}}
                                        @php
                                            $isSelected = false;
                                            if($reqPembimbing && $reqPembimbing->dosen_id == $dosen->id) {
                                                $isSelected = true;
                                            } elseif(!$reqPembimbing && $proposal->dosen_pembimbing_id == $dosen->id) {
                                                $isSelected = true;
                                            }
                                        @endphp
                                        <option value="{{ $dosen->id }}" {{ $isSelected ? 'selected' : '' }}>
                                            {{ $dosen->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            {{-- KOLOM STATUS DINAMIS --}}
                            <div class="md:col-span-4">
                                <span class="block text-xs font-bold text-gray-400 uppercase mb-2">Status Terkini</span>
                                
                                @if(!$reqPembimbing)
                                    <div class="flex items-center bg-gray-100 px-3 py-2 rounded-lg text-gray-500 border border-gray-200">
                                        <span class="w-2 h-2 rounded-full bg-gray-400 mr-2"></span> Belum Diajukan
                                    </div>
                                @elseif($reqPembimbing->status == 'pending')
                                    <div class="flex items-center bg-yellow-50 px-3 py-2 rounded-lg text-yellow-700 border border-yellow-200">
                                        <span class="animate-pulse mr-2">⏳</span> Menunggu Konfirmasi Dosen
                                    </div>
                                    <p class="text-[10px] text-gray-400 mt-1">Request dikirim: {{ $reqPembimbing->created_at->diffForHumans() }}</p>
                                @elseif($reqPembimbing->status == 'accepted')
                                    <div class="flex items-center bg-green-50 px-3 py-2 rounded-lg text-green-700 border border-green-200">
                                        <span class="mr-2">✓</span> Bersedia / Disetujui
                                    </div>
                                @elseif($reqPembimbing->status == 'rejected')
                                    <div class="flex items-center bg-red-50 px-3 py-2 rounded-lg text-red-700 border border-red-200">
                                        <span class="mr-2">✕</span> Ditolak
                                    </div>
                                    <div class="mt-2 p-2 bg-red-50 border border-red-100 rounded text-xs text-red-600 italic">
                                        "{{ $reqPembimbing->pesan_penolakan ?? 'Tidak ada alasan.' }}"
                                    </div>
                                    <p class="text-[10px] text-red-400 mt-1">*Silakan pilih dosen lain dan simpan ulang.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ==================================================== --}}
                    {{-- BAGIAN PENGUJI (DINAMIS) --}}
                    {{-- ==================================================== --}}
                    <div x-data="{ 
                        pengujis: {{ $reqPengujiList->count() > 0 ? $reqPengujiList->map(fn($q) => ['dosen_id' => $q->dosen_id, 'status' => $q->status, 'pesan' => $q->pesan_penolakan, 'time' => $q->created_at->diffForHumans()]) : '[{dosen_id: \'\', status: \'none\', pesan: \'\', time: \'\'}]' }}
                    }">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Daftar Dosen Penguji</h4>
                            <button type="button" @click="pengujis.push({dosen_id: '', status: 'none', pesan: '', time: ''})" 
                                    class="text-xs bg-indigo-50 text-indigo-600 px-3 py-2 rounded-lg font-bold hover:bg-indigo-100 transition">
                                + Tambah Penguji
                            </button>
                        </div>

                        <template x-for="(penguji, index) in pengujis" :key="index">
                            <div class="bg-white border border-gray-200 rounded-xl p-6 mb-4 hover:shadow-md transition relative">
                                {{-- Tombol Hapus Baris --}}
                                <button type="button" @click="pengujis.splice(index, 1)" 
                                        class="absolute top-4 right-4 text-gray-400 hover:text-red-500" x-show="pengujis.length > 1">
                                    <i class="fas fa-trash"></i>
                                </button>

                                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
                                    <div class="md:col-span-2 pt-2">
                                        <span class="text-xs font-bold text-gray-400 uppercase">Peran</span>
                                        <p class="font-bold text-indigo-600 mt-1" x-text="'Penguji ' + (index + 1)"></p>
                                    </div>
                                    
                                    <div class="md:col-span-6">
                                        <label class="block text-xs font-bold text-gray-400 uppercase mb-2">Pilih Dosen</label>
                                        <select :name="'dosen_penguji_id['+index+']'" x-model="penguji.dosen_id" 
                                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="">-- Pilih Dosen --</option>
                                            @foreach($dosens as $dosen)
                                                <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- KOLOM STATUS DINAMIS PENGUJI --}}
                                    <div class="md:col-span-4">
                                        <span class="block text-xs font-bold text-gray-400 uppercase mb-2">Status Terkini</span>

                                        {{-- Status: Belum Diajukan --}}
                                        <div x-show="penguji.status == 'none'" class="flex items-center bg-gray-100 px-3 py-2 rounded-lg text-gray-500 border border-gray-200">
                                            <span class="w-2 h-2 rounded-full bg-gray-400 mr-2"></span> Belum Diajukan
                                        </div>

                                        {{-- Status: Pending --}}
                                        <div x-show="penguji.status == 'pending'" class="flex items-center bg-yellow-50 px-3 py-2 rounded-lg text-yellow-700 border border-yellow-200">
                                            <span class="animate-pulse mr-2">⏳</span> Menunggu Konfirmasi
                                        </div>

                                        {{-- Status: Accepted --}}
                                        <div x-show="penguji.status == 'accepted'" class="flex items-center bg-green-50 px-3 py-2 rounded-lg text-green-700 border border-green-200">
                                            <span class="mr-2">✓</span> Bersedia
                                        </div>

                                        {{-- Status: Rejected --}}
                                        <div x-show="penguji.status == 'rejected'">
                                            <div class="flex items-center bg-red-50 px-3 py-2 rounded-lg text-red-700 border border-red-200">
                                                <span class="mr-2">✕</span> Ditolak
                                            </div>
                                            <div class="mt-2 p-2 bg-red-50 border border-red-100 rounded text-xs text-red-600 italic" x-text="penguji.pesan"></div>
                                        </div>
                                        
                                        <p class="text-[10px] text-gray-400 mt-1" x-show="penguji.time" x-text="'Request: ' + penguji.time"></p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                {{-- Floating Action Button --}}
                    <div class="fixed bottom-0 left-0 right-0 bg-white border-t p-4 z-50 flex justify-end gap-4 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)]">
                        <a href="{{ route('koordinator.penetapan.index') }}" class="px-6 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">
                            Kembali
                        </a>
                        <button type="submit" class="px-8 py-2.5 rounded-lg bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            
                            {{-- PERBAIKAN DI SINI: Ganti $reqPenguji menjadi $reqPengujiList->count() --}}
                            @if($reqPembimbing || (isset($reqPengujiList) && $reqPengujiList->count() > 0))
                                Update / Kirim Ulang Request
                            @else
                                Kirim Permintaan Kesediaan
                            @endif
                        </button>
                    </div>
            </form>

        </div>
    </div>
</x-app-layout>