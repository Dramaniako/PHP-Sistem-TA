<x-app-layout title="Buat Jadwal Sidang">
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('koordinator.sidang.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Penjadwalan Sidang Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- BANNER INFO --}}
            <div class="bg-gradient-to-r from-indigo-600 to-blue-600 rounded-2xl shadow-lg p-6 mb-8 text-white flex items-center gap-4">
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="fas fa-calendar-plus text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg">Form Penjadwalan</h3>
                    <p class="text-sm opacity-90">Sistem akan otomatis mengambil data Proposal, Pembimbing, dan Penguji saat Anda memilih mahasiswa.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="sidangForm()">
                
                {{-- KOLOM KIRI: FORM INPUT --}}
                <div class="lg:col-span-2 space-y-6">
                    <form action="{{ route('koordinator.sidang.store') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                        @csrf
                        <input type="hidden" name="proposal_id" x-model="proposal_id">

                        {{-- Section 1: Pilih Mahasiswa --}}
                        <div class="mb-8">
                            <h4 class="text-gray-800 font-bold border-b border-gray-100 pb-2 mb-4 flex items-center gap-2">
                                <span class="bg-blue-100 text-blue-600 text-xs px-2 py-1 rounded">1</span> Pilih Kandidat
                            </h4>
                            
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cari Mahasiswa</label>
                            <select class="w-full border-gray-300 rounded-xl p-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm" 
                                    x-on:change="fetchData($event.target.value)" required>
                                <option value="">-- Pilih Mahasiswa Siap Sidang --</option>
                                @foreach($mahasiswas as $m)
                                    <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->nim }})</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-2">*Hanya menampilkan mahasiswa yang proposalnya disetujui.</p>
                        </div>

                        {{-- Section 2: Tentukan Jadwal --}}
                        <div>
                            <h4 class="text-gray-800 font-bold border-b border-gray-100 pb-2 mb-4 flex items-center gap-2">
                                <span class="bg-blue-100 text-blue-600 text-xs px-2 py-1 rounded">2</span> Detail Pelaksanaan
                            </h4>

                            <div class="space-y-4">
                            {{-- Input Tanggal --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Sidang</label>
                                <input type="date" name="tanggal_sidang" required
                                    class="w-full border-gray-300 rounded-xl p-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                            </div>

                            {{-- Input Jam (Split 2 Kolom) --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jam Mulai</label>
                                    <input type="time" name="jam_mulai" required
                                        class="w-full border-gray-300 rounded-xl p-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jam Selesai</label>
                                    <input type="time" name="jam_selesai" required
                                        class="w-full border-gray-300 rounded-xl p-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                </div>
                            </div>
                            
                            {{-- Input Ruangan (Tetap) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ruangan</label>
                                <input type="text" name="ruangan" placeholder="Contoh: Gedung A, R. 201" required
                                    class="w-full border-gray-300 rounded-xl p-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                            </div>
                        </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-3">
                            <a href="{{ route('koordinator.sidang.index') }}" class="px-6 py-3 rounded-xl border border-gray-300 text-gray-600 font-bold hover:bg-gray-50 transition">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="px-6 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition transform hover:-translate-y-0.5"
                                    :disabled="!proposal_id" 
                                    :class="!proposal_id ? 'opacity-50 cursor-not-allowed' : ''">
                                <i class="fas fa-save mr-2"></i> Simpan Jadwal
                            </button>
                        </div>
                    </form>
                </div>

                {{-- KOLOM KANAN: PREVIEW DATA (STICKY) --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-8 space-y-6">
                        
                        {{-- Placeholder State --}}
                        <div x-show="!proposal_id" class="bg-gray-50 rounded-xl border-2 border-dashed border-gray-300 p-8 text-center text-gray-400">
                            <i class="fas fa-user-graduate text-4xl mb-3"></i>
                            <p class="text-sm">Pilih mahasiswa di sebelah kiri untuk melihat detail proposal dan dosen.</p>
                        </div>

                        {{-- Active State (Loading) --}}
                        <div x-show="loading" class="bg-white rounded-xl shadow-sm p-8 text-center" x-cloak>
                            <i class="fas fa-circle-notch fa-spin text-blue-600 text-2xl mb-3"></i>
                            <p class="text-sm text-gray-500">Mengambil data...</p>
                        </div>

                        {{-- Active State (Data Loaded) --}}
                        <div x-show="proposal_id && !loading" class="bg-white rounded-xl shadow-lg border-t-4 border-blue-600 overflow-hidden transition-all duration-500" x-cloak>
                            <div class="p-6">
                                <h5 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Judul Tugas Akhir</h5>
                                <p class="font-bold text-gray-800 leading-snug" x-text="judul"></p>
                                
                                <div class="mt-6 space-y-4">
                                    <div class="flex items-start gap-3">
                                        <div class="bg-blue-50 text-blue-600 p-2 rounded-lg text-xs"><i class="fas fa-user-tie"></i></div>
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase font-bold">Pembimbing</p>
                                            <p class="text-sm font-semibold text-gray-800" x-text="pembimbing"></p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <div class="bg-indigo-50 text-indigo-600 p-2 rounded-lg text-xs"><i class="fas fa-chalkboard-teacher"></i></div>
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase font-bold">Penguji Utama</p>
                                            <p class="text-sm font-semibold text-gray-800" x-text="penguji"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 space-y-2">
                                <template x-if="khs_link">
                                    <a :href="khs_link" target="_blank" class="block w-full text-center py-2 rounded-lg border border-gray-300 bg-white text-xs font-bold text-gray-600 hover:bg-gray-50 hover:text-blue-600 transition">
                                        <i class="fas fa-file-alt mr-1"></i> Cek File KHS
                                    </a>
                                </template>
                                <template x-if="ta_link">
                                    <a :href="ta_link" target="_blank" class="block w-full text-center py-2 rounded-lg bg-blue-100 text-blue-700 text-xs font-bold hover:bg-blue-200 transition">
                                        <i class="fas fa-book mr-1"></i> Cek Draft TA
                                    </a>
                                </template>
                                <template x-if="!ta_link">
                                    <div class="text-center text-xs text-red-500 italic py-1">
                                        <i class="fas fa-exclamation-circle"></i> Draft TA belum diupload
                                    </div>
                                </template>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- SCRIPT LOGIC (SAMA SEPERTI SEBELUMNYA) --}}
    <script>
        function sidangForm() {
            return {
                loading: false,
                proposal_id: '',
                judul: '',
                pembimbing: '',
                penguji: '',
                khs_link: null,
                ta_link: null,

                fetchData(id) {
                    if(!id) {
                        this.resetData();
                        return;
                    }
                    this.loading = true;
                    this.proposal_id = ''; // Reset dulu biar transisi smooth

                    fetch(`/koordinator/api/get-proposal/${id}`)
                        .then(res => {
                            if(!res.ok) throw new Error('Data tidak ditemukan');
                            return res.json();
                        })
                        .then(data => {
                            this.proposal_id = data.proposal_id;
                            this.judul = data.judul;
                            this.pembimbing = data.pembimbing;
                            this.penguji = data.penguji;
                            this.khs_link = data.file_khs;
                            this.ta_link = data.file_ta;
                        })
                        .catch(err => {
                            alert(err.message);
                            this.resetData();
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                },

                resetData() {
                    this.proposal_id = '';
                    this.judul = '';
                    this.pembimbing = '';
                    this.penguji = '';
                    this.khs_link = null;
                    this.ta_link = null;
                }
            }
        }
    </script>
</x-app-layout>