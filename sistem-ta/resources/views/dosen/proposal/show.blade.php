<x-app-layout title="Detail Proposal Mahasiswa">
    <div class="py-10 px-6 max-w-5xl mx-auto">
        
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            {{-- Header --}}
            <div class="bg-slate-800 p-6 text-white flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold">{{ $proposal->judul }}</h2>
                    <p class="text-slate-300 text-sm mt-1">Oleh: {{ $proposal->mahasiswa->name }} ({{ $proposal->mahasiswa->nim }})</p>
                </div>
                <div class="text-right">
                    <span class="bg-blue-600 text-white text-xs px-3 py-1 rounded-full uppercase font-bold tracking-wider">
                        {{ $proposal->dosen_pembimbing_id == auth()->id() ? 'PEMBIMBING' : 'PENGUJI' }}
                    </span>
                </div>
            </div>

            <div class="p-8">
                {{-- Abstrak --}}
                <div class="mb-8">
                    <h3 class="text-sm font-bold text-gray-400 uppercase mb-2">Deskripsi / Abstrak</h3>
                    <p class="text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-xl border border-gray-200">
                        {{ $proposal->deskripsi }}
                    </p>
                </div>

                {{-- Grid File Download --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- 1. File Proposal --}}
                    <div class="border rounded-xl p-4 flex items-center gap-4 hover:border-blue-400 transition cursor-pointer bg-white shadow-sm">
                        <div class="bg-red-100 text-red-600 w-12 h-12 flex items-center justify-center rounded-lg text-2xl">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-800 text-sm">Dokumen Proposal</p>
                            <p class="text-xs text-gray-500">File Utama TA</p>
                        </div>
                        <a href="#" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-blue-700">Download</a>
                        {{-- Catatan: Anda perlu buat route download khusus dosen jika mau aman, atau gunakan Storage::url sementara --}}
                    </div>

                    {{-- 2. File KHS --}}
                    <div class="border rounded-xl p-4 flex items-center gap-4 hover:border-green-400 transition cursor-pointer bg-white shadow-sm">
                        <div class="bg-green-100 text-green-600 w-12 h-12 flex items-center justify-center rounded-lg text-2xl">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-800 text-sm">Kartu Hasil Studi (KHS)</p>
                            <p class="text-xs text-gray-500">Syarat Pengajuan</p>
                        </div>
                        @if($proposal->file_khs)
                            {{-- Ganti '#' dengan route download KHS jika sudah dibuat --}}
                            <a href="#" class="bg-green-600 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-green-700">Lihat</a>
                        @else
                            <span class="text-xs text-red-500 italic">Tidak ada file</span>
                        @endif
                    </div>
                </div>

                <hr class="border-gray-100 my-8">
                {{-- FORMULIR PENILAIAN DOSEN --}}
                <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm mt-8" x-data="{ mode: null }">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 border-b pb-2">
                        <i class="fas fa-gavel mr-2"></i> Form Penilaian Proposal
                    </h3>

                    <form action="{{ route('dosen.proposal.keputusan', $proposal->id) }}" method="POST"
                          @submit.prevent="if (mode === 'tolak') { if (confirm('Apakah Anda yakin ingin menolak proposal ini secara permanen?')) $el.submit() } else { $el.submit() }">
                        @csrf
                        @method('PUT')

                        {{-- Pilihan Mode: Penilaian vs Tolak --}}
                        <div class="flex gap-4 mb-6">
                            {{-- Opsi 1: Berikan Nilai --}}
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="keputusan" value="nilai" x-model="mode" class="peer hidden">
                                <div class="p-4 rounded-xl border-2 text-center transition hover:bg-blue-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700">
                                    <div class="mb-2 text-2xl"><i class="fas fa-calculator"></i></div>
                                    <div class="font-bold text-sm">Berikan Nilai</div>
                                    <div class="text-[10px] text-gray-500">Nilai &gt; 75 Lulus, &le; 75 Revisi</div>
                                </div>
                            </label>

                            {{-- Opsi 2: Tolak Proposal --}}
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="keputusan" value="tolak" x-model="mode" class="peer hidden">
                                <div class="p-4 rounded-xl border-2 border-gray-100 text-center transition hover:bg-red-50 peer-checked:border-red-600 peer-checked:bg-red-50 peer-checked:text-red-700">
                                    <div class="mb-2 text-2xl"><i class="fas fa-ban"></i></div>
                                    <div class="font-bold text-sm">Tolak Proposal</div>
                                    <div class="text-[10px] text-gray-500">Proposal tidak layak</div>
                                </div>
                            </label>
                        </div>

                        {{-- INPUT NILAI (Hanya muncul jika mode == nilai) --}}
                        <div x-show="mode === 'nilai'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="mb-6 bg-blue-50 p-6 rounded-xl border border-blue-100">
                            <label class="block text-sm font-bold text-blue-800 mb-2">Masukkan Nilai (0 - 100)</label>
                            <div class="relative">
                                <input type="number" name="nilai" min="0" max="100" 
                                    class="w-full text-3xl font-bold text-center border-2 border-blue-200 rounded-xl p-3 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-300" 
                                    placeholder="0" :required="mode === 'nilai'">
                                <div class="text-center mt-2 text-xs font-medium text-blue-600">
                                    *Sistem otomatis menentukan Lulus/Revisi berdasarkan nilai ini.
                                </div>
                            </div>
                        </div>

                        {{-- PESAN PERINGATAN (Hanya muncul jika mode == tolak) --}}
                        <div x-show="mode === 'tolak'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="mb-6 bg-red-50 p-4 rounded-xl border border-red-100 flex items-start gap-3">
                            <i class="fas fa-exclamation-triangle text-red-500 mt-1"></i>
                            <div>
                                <h4 class="font-bold text-red-700 text-sm">Perhatian</h4>
                                <p class="text-xs text-red-600">Anda akan menolak proposal ini secara permanen. Tombol simpan akan memunculkan konfirmasi akhir.</p>
                            </div>
                        </div>

                        {{-- Input Komentar (Hanya muncul jika salah satu mode dipilih) --}}
                        <div class="mb-6" x-show="mode !== null" x-cloak x-transition>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Catatan / Masukan</label>
                            <textarea name="komentar" rows="4" 
                                class="w-full border-gray-300 rounded-xl text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                placeholder="Berikan alasan nilai atau catatan perbaikan..." :required="mode !== null">{{ $proposal->komentar }}</textarea>
                        </div>

                        {{-- Tombol Simpan (Muncul jika mode dipilih) --}}
                        <div class="flex justify-end" x-show="mode !== null" x-cloak x-transition>
                            <button type="submit" class="bg-slate-900 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-black transition transform active:scale-95 flex items-center gap-2">
                                <i class="fas fa-save"></i> Simpan Penilaian
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>