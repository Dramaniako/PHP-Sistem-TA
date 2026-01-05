<x-app-layout title="Detail Mahasiswa Ujian">
    <div class="py-10 px-6 max-w-5xl mx-auto">
        
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            {{-- HEADER KHUSUS PENGUJI (Warna Ungu) --}}
            <div class="bg-purple-900 p-6 text-white flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold">{{ $proposal->judul }}</h2>
                    <p class="text-purple-200 text-sm mt-1">Mahasiswa: {{ $proposal->mahasiswa->name }} ({{ $proposal->mahasiswa->nim }})</p>
                </div>
                <div class="text-right">
                    <span class="bg-purple-700 border border-purple-500 text-white text-xs px-3 py-1 rounded-full uppercase font-bold tracking-wider">
                        {{ $proposal->dosen_pembimbing_id == auth()->id() ? 'PEMBIMBING' : 'PENGUJI' }}
                    </span>
                </div>
            </div>

            <div class="p-8">
                {{-- ABSTRAK --}}
                <div class="mb-8">
                    <h3 class="text-sm font-bold text-gray-400 uppercase mb-2">Abstrak / Ringkasan</h3>
                    <p class="text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-xl border border-gray-200">
                        {{ $proposal->deskripsi }}
                    </p>
                </div>

                {{-- AREA DOWNLOAD DOKUMEN --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    {{-- 1. DOKUMEN PROPOSAL (Menggunakan Route Download Controller) --}}
                    <div class="border rounded-xl p-4 flex items-center gap-4 hover:border-purple-400 transition bg-white shadow-sm group">
                        <div class="bg-red-100 text-red-600 w-12 h-12 flex items-center justify-center rounded-lg text-2xl group-hover:scale-110 transition"><i class="fas fa-file-pdf"></i></div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-800 text-sm">Dokumen Proposal</p>
                            <p class="text-xs text-gray-500">File Utama Tugas Akhir</p>
                        </div>
                        <a href="{{ route('dosen.proposal.download', $proposal->id) }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-purple-700 flex items-center gap-2">
                            <i class="fas fa-download"></i> Unduh
                        </a>
                    </div>

                    {{-- 2. KARTU HASIL STUDI --}}
                    <div class="border rounded-xl p-4 flex items-center gap-4 hover:border-green-400 transition bg-white shadow-sm group">
                        <div class="bg-green-100 text-green-600 w-12 h-12 flex items-center justify-center rounded-lg text-2xl group-hover:scale-110 transition">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-800 text-sm">Kartu Hasil Studi</p>
                            <p class="text-xs text-gray-500">Syarat Kelayakan</p>
                        </div>

                        @if($proposal->file_khs)
                            {{-- UBAH DI SINI: Gunakan Route Controller --}}
                            <a href="{{ route('dosen.proposal.download.khs', $proposal->id) }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-green-700 flex items-center gap-2">
                                <i class="fas fa-download"></i> Unduh KHS
                            </a>
                        @else
                            <span class="text-xs text-red-500 italic bg-red-50 px-2 py-1 rounded">
                                Tidak ada file
                            </span>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- SCRIPT SEDERHANA UNTUK TOGGLE FORM --}}
    <script>
        function toggleForm(mode) {
            const areaNilai = document.getElementById('area-nilai');
            const areaTolak = document.getElementById('area-tolak');
            const inputNilai = document.querySelector('input[name="nilai"]');

            if (mode === 'nilai') {
                areaNilai.classList.remove('hidden');
                areaTolak.classList.add('hidden');
                inputNilai.required = true;
            } else {
                areaNilai.classList.add('hidden');
                areaTolak.classList.remove('hidden');
                inputNilai.required = false;
            }
        }
    </script>
</x-app-layout>