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
            <div
                class="bg-gradient-to-r from-indigo-600 to-blue-600 rounded-2xl shadow-lg p-6 mb-8 text-white flex items-center gap-4">
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="fas fa-calendar-plus text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg">Form Penjadwalan</h3>
                    <p class="text-sm opacity-90">Sistem akan memvalidasi ketersediaan dokumen (Proposal/Draft TA)
                        secara otomatis sesuai jenis sidang.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="sidangForm()">

                {{-- KOLOM KIRI: FORM INPUT --}}
                <div class="lg:col-span-2 space-y-6">
                    <form action="{{ route('koordinator.sidang.store') }}" method="POST"
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                        @csrf
                        {{-- Hidden input untuk menyimpan ID proposal yang terpilih --}}
                        <input type="hidden" name="proposal_id" x-model="proposal_id">

                        {{-- Section 1: Pilih Mahasiswa --}}
                        <div class="mb-8">
                            <h4
                                class="text-gray-800 font-bold border-b border-gray-100 pb-2 mb-4 flex items-center gap-2">
                                <span class="bg-blue-100 text-blue-600 text-xs px-2 py-1 rounded">1</span> Pilih
                                Kandidat
                            </h4>

                            <label class="block text-sm font-medium text-gray-700 mb-2">Cari Mahasiswa</label>
                            <select
                                class="w-full border-gray-300 rounded-xl p-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                                x-on:change="fetchData($event.target.value)" required>
                                <option value="">-- Pilih Mahasiswa Siap Sidang --</option>
                                @foreach($mahasiswas as $m)
                                    <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->nim }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Section 2: Tentukan Jadwal --}}
                        <div>
                            <h4
                                class="text-gray-800 font-bold border-b border-gray-100 pb-2 mb-4 flex items-center gap-2">
                                <span class="bg-blue-100 text-blue-600 text-xs px-2 py-1 rounded">2</span> Detail
                                Pelaksanaan
                            </h4>

                            <div class="mb-4">
                                <label class="block mb-1 font-medium text-gray-700">Jenis Sidang</label>
                                <select name="jenis_sidang" x-model="jenis_sidang"
                                    class="w-full border-gray-300 rounded-xl p-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                                    required>
                                    <option value="">-- Pilih Jenis Sidang --</option>
                                    <option value="Sidang Proposal">Sidang Proposal</option>
                                    <option value="Sidang Akhir">Sidang Akhir</option>
                                </select>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                                    <input type="date" name="tanggal_sidang" required
                                        class="w-full border-gray-300 rounded-xl p-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                </div>

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

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Ruangan</label>
                                    <input type="text" name="ruangan" placeholder="Contoh: Gedung A, R. 201" required
                                        class="w-full border-gray-300 rounded-xl p-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-3">
                            <a href="{{ route('koordinator.sidang.index') }}"
                                class="px-6 py-3 rounded-xl border border-gray-300 text-gray-600 font-bold hover:bg-gray-50 transition">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition"
                                @click="if(!validateFile()) { $event.preventDefault(); }" :disabled="!proposal_id"
                                :class="!proposal_id ? 'opacity-50 cursor-not-allowed' : ''">
                                <i class="fas fa-save mr-2"></i> Simpan Jadwal
                            </button>
                        </div>
                    </form>
                </div>

                {{-- KOLOM KANAN: PREVIEW DATA --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-8 space-y-6">

                        {{-- Kondisi Belum Pilih Mahasiswa --}}
                        <div x-show="!proposal_id"
                            class="bg-gray-50 rounded-xl border-2 border-dashed border-gray-300 p-8 text-center text-gray-400">
                            <i class="fas fa-user-graduate text-4xl mb-3"></i>
                            <p class="text-sm">Pilih mahasiswa untuk memvalidasi dokumen syarat sidang.</p>
                        </div>

                        {{-- Loading State --}}
                        <div x-show="loading" class="bg-white rounded-xl shadow-sm p-8 text-center" x-cloak>
                            <i class="fas fa-circle-notch fa-spin text-blue-600 text-2xl mb-3"></i>
                            <p class="text-sm text-gray-500">Mengambil data dokumen...</p>
                        </div>

                        {{-- Data Terload --}}
                        <div x-show="proposal_id && !loading"
                            class="bg-white rounded-xl shadow-lg border-t-4 border-blue-600 overflow-hidden" x-cloak>
                            <div class="p-6">
                                <h5 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Judul TA
                                    Mahasiswa</h5>
                                <p class="font-bold text-gray-800 leading-snug" x-text="judul"></p>

                                <div class="mt-6 space-y-4">
                                    <div class="flex items-start gap-3">
                                        <div class="bg-blue-50 text-blue-600 p-2 rounded-lg text-xs"><i
                                                class="fas fa-user-tie"></i></div>
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase font-bold">Pembimbing</p>
                                            <p class="text-sm font-semibold text-gray-800" x-text="pembimbing"></p>
                                        </div>
                                    </div>
                                    <div class="flex items-start gap-3">
                                        <div class="bg-indigo-50 text-indigo-600 p-2 rounded-lg text-xs"><i
                                                class="fas fa-chalkboard-teacher"></i></div>
                                        <div>
                                            <p class="text-xs text-gray-500 uppercase font-bold">Penguji</p>
                                            <p class="text-sm font-semibold text-gray-800" x-text="penguji"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 space-y-3">
                                {{-- KHS Link (Selalu Ada) --}}
                                <template x-if="khs_link">
                                    <a :href="khs_link"
                                        class="block w-full text-center py-2 rounded-lg border border-gray-300 bg-white text-xs font-bold text-gray-600 hover:text-blue-600 transition">
                                        <i class="fas fa-file-alt mr-1"></i> Download KHS
                                    </a>
                                </template>

                                {{-- Dokumen Syarat Sidang Berdasarkan Jenis --}}
                                <div class="pt-2 border-t border-gray-200">
                                    {{-- Jika Sidang Proposal --}}
                                    <template x-if="jenis_sidang === 'Sidang Proposal'">
                                        <div>
                                            <template x-if="proposal_awal_link">
                                                <a :href="proposal_awal_link"
                                                    class="block w-full text-center py-2 rounded-lg bg-orange-100 text-orange-700 text-xs font-bold hover:bg-orange-200">
                                                    <i class="fas fa-file-pdf mr-1"></i> Download File Proposal
                                                </a>
                                            </template>
                                            <template x-if="!proposal_awal_link">
                                                <p class="text-center text-xs text-red-500 italic font-bold">File
                                                    Proposal Belum Ada!</p>
                                            </template>
                                        </div>
                                    </template>

                                    {{-- Jika Sidang Akhir --}}
                                    <template x-if="jenis_sidang === 'Sidang Akhir'">
                                        <div>
                                            <template x-if="ta_link">
                                                <a :href="ta_link"
                                                    class="block w-full text-center py-2 rounded-lg bg-blue-100 text-blue-700 text-xs font-bold hover:bg-blue-200">
                                                    <i class="fas fa-book mr-1"></i> Download Draft TA
                                                </a>
                                            </template>
                                            <template x-if="!ta_link">
                                                <p class="text-center text-xs text-red-500 italic font-bold">Draft TA
                                                    Belum Ada!</p>
                                            </template>
                                        </div>
                                    </template>

                                    <template x-if="!jenis_sidang">
                                        <p class="text-center text-xs text-gray-400 italic">Tentukan jenis sidang untuk
                                            cek dokumen</p>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function sidangForm() {
            return {
                loading: false,
                proposal_id: '',
                jenis_sidang: '',
                judul: '',
                pembimbing: '',
                penguji: '',
                khs_link: null,
                ta_link: null,
                proposal_awal_link: null,

                fetchData(id) {
                    if (!id) {
                        this.resetData();
                        return;
                    }
                    this.loading = true;
                    this.proposal_id = '';

                    fetch(`/koordinator/sidang/api/get-proposal/${id}`)
                        .then(res => {
                            if (!res.ok) throw new Error('Mahasiswa belum memiliki proposal yang disetujui');
                            return res.json();
                        })
                        .then(data => {
                            this.proposal_id = data.proposal_id;
                            this.judul = data.judul;
                            this.pembimbing = data.pembimbing;
                            this.penguji = data.penguji_list.join(', ') || 'Belum ada penguji';

                            // BANGUN URL DOWNLOAD BERDASARKAN PROPOSAL_ID
                            // KHS dan Draft TA menggunakan rute di SidangKoordinatorController
                            this.khs_link = data.file_khs ? `/koordinator/sidang/download-khs/${data.proposal_id}` : null;
                            this.ta_link = data.file_ta ? `/koordinator/sidang/download-ta/${data.proposal_id}` : null;

                            // Proposal Awal biasanya menggunakan rute di PenetapanController
                            this.proposal_awal_link = data.proposal_id ? `/koordinator/penetapan/${data.proposal_id}/download` : null;
                        })
                        .catch(err => {
                            alert(err.message);
                            this.resetData();
                        })
                        .finally(() => {
                            this.loading = false;
                        });
                },

                validateFile() {
                    if (!this.jenis_sidang) {
                        alert('Pilih jenis sidang terlebih dahulu!');
                        return false;
                    }
                    if (this.jenis_sidang === 'Sidang Proposal' && !this.proposal_awal_link) {
                        alert('Dokumen Proposal mahasiswa belum tersedia!');
                        return false;
                    }
                    if (this.jenis_sidang === 'Sidang Akhir' && !this.ta_link) {
                        alert('Mahasiswa belum mengunggah Draft TA!');
                        return false;
                    }
                    return true;
                },

                resetData() {
                    this.proposal_id = '';
                    this.jenis_sidang = '';
                    this.judul = '';
                    this.pembimbing = '';
                    this.penguji = '';
                    this.khs_link = null;
                    this.ta_link = null;
                    this.proposal_awal_link = null;
                }
            }
        }
    </script>
</x-app-layout>