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

                {{-- FORMULIR KEPUTUSAN DOSEN --}}
                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4">
                        <i class="fas fa-gavel mr-2"></i> Keputusan Pembimbing / Penguji
                    </h3>

                    <form action="{{ route('dosen.proposal.keputusan', $proposal->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Pilihan Status --}}
                        <div class="space-y-3 mb-6">
                            {{-- Setuju --}}
                            <label class="flex items-center p-4 border bg-white rounded-xl cursor-pointer hover:border-green-500 transition has-[:checked]:border-green-500 has-[:checked]:bg-green-50 has-[:checked]:shadow-sm">
                                <input type="radio" name="status" value="approved" class="peer hidden" {{ $proposal->status == 'disetujui' ? 'checked' : '' }}>
                                <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center mr-4">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="flex-1">
                                    <span class="block font-bold text-gray-800">Setuju / Lanjut</span>
                                    <span class="text-xs text-gray-500">Proposal layak dilanjutkan ke tahap berikutnya</span>
                                </div>
                                <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:bg-green-500 peer-checked:border-green-500 transition"></div>
                            </label>

                            {{-- Revisi --}}
                            <label class="flex items-center p-4 border bg-white rounded-xl cursor-pointer hover:border-yellow-500 transition has-[:checked]:border-yellow-500 has-[:checked]:bg-yellow-50 has-[:checked]:shadow-sm">
                                <input type="radio" name="status" value="pending" class="peer hidden" {{ $proposal->status == 'revisi' ? 'checked' : '' }}>
                                <div class="w-10 h-10 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center mr-4">
                                    <i class="fas fa-pen"></i>
                                </div>
                                <div class="flex-1">
                                    <span class="block font-bold text-gray-800">Perlu Revisi</span>
                                    <span class="text-xs text-gray-500">Mahasiswa harus memperbaiki proposal</span>
                                </div>
                                <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:bg-yellow-500 peer-checked:border-yellow-500 transition"></div>
                            </label>

                            {{-- Tolak --}}
                            <label class="flex items-center p-4 border bg-white rounded-xl cursor-pointer hover:border-red-500 transition has-[:checked]:border-red-500 has-[:checked]:bg-red-50 has-[:checked]:shadow-sm">
                                <input type="radio" name="status" value="rejected" class="peer hidden" {{ $proposal->status == 'ditolak' ? 'checked' : '' }}>
                                <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center mr-4">
                                    <i class="fas fa-times"></i>
                                </div>
                                <div class="flex-1">
                                    <span class="block font-bold text-gray-800">Tolak Proposal</span>
                                    <span class="text-xs text-gray-500">Judul/Topik harus diganti total</span>
                                </div>
                                <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:bg-red-500 peer-checked:border-red-500 transition"></div>
                            </label>
                        </div>

                        {{-- Input Komentar --}}
                        <div class="mb-6">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Catatan / Masukan Revisi</label>
                            <textarea name="komentar" rows="4" 
                                class="w-full border-gray-300 rounded-xl text-sm focus:border-blue-500 focus:ring-blue-500 shadow-sm"
                                placeholder="Tuliskan detail revisi yang harus dilakukan mahasiswa...">{{ $proposal->komentar }}</textarea>
                        </div>

                        {{-- Tombol Simpan --}}
                        <div class="flex justify-end">
                            <button type="submit" class="bg-slate-800 hover:bg-black text-white px-6 py-3 rounded-xl font-bold shadow-lg transform hover:-translate-y-0.5 transition flex items-center gap-2">
                                <i class="fas fa-save"></i> Simpan Keputusan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>