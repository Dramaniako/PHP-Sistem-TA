<x-app-layout title="Detail Proposal Mahasiswa">
    <div class="py-10 px-6 max-w-5xl mx-auto">
        
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            {{-- HEADER --}}
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
                {{-- ABSTRAK --}}
                <div class="mb-8">
                    <h3 class="text-sm font-bold text-gray-400 uppercase mb-2">Deskripsi / Abstrak</h3>
                    <p class="text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-xl border border-gray-200">
                        {{ $proposal->deskripsi }}
                    </p>
                </div>

                {{-- FILE DOWNLOAD --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    {{-- File Proposal --}}
                    <div class="border rounded-xl p-4 flex items-center gap-4 hover:border-purple-400 transition bg-white shadow-sm group">
                        <div class="bg-red-100 text-red-600 w-12 h-12 flex items-center justify-center rounded-lg text-2xl group-hover:scale-110 transition"><i class="fas fa-file-pdf"></i></div>
                        <div class="flex-1">
                            <p class="font-bold text-gray-800 text-sm">Dokumen Proposal</p>
                            <p class="text-xs text-gray-500">File Utama Tugas Akhir</p>
                        </div>
                        <a href="{{ route('dosen.proposal.download', $proposal->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-purple-700 flex items-center gap-2">
                            <i class="fas fa-download"></i> Unduh
                        </a>
                    </div>
                    {{-- File KHS --}}
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

                <hr class="border-gray-100 my-8">

                {{-- ================================================= --}}
                {{-- BAGIAN 1: MANAJEMEN JADWAL BIMBINGAN (BARU)       --}}
                {{-- ================================================= --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-8 overflow-hidden">
                    <div class="bg-gray-50 p-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">
                            <i class="fas fa-calendar-alt mr-2"></i> Jadwal Bimbingan
                        </h3>
                        <div>
                            <button onclick="switchTab('list')" id="btn-list" class="bg-white border text-gray-600 px-3 py-1 rounded text-xs font-bold mr-2 shadow-sm">Daftar Jadwal</button>
                            <button onclick="switchTab('create')" id="btn-create" class="bg-blue-600 text-white px-3 py-1 rounded text-xs font-bold shadow-sm">+ Buat Baru</button>
                        </div>
                    </div>

                    <div class="p-6">
                        {{-- TAB 1: LIST JADWAL --}}
                        <div id="tab-list">
                            @forelse($jadwals as $jadwal)
                                <div class="border-b border-gray-100 pb-4 mb-4 last:border-0 last:mb-0 last:pb-0">
                                    <div class="flex flex-col md:flex-row justify-between md:items-start gap-4">
                                        {{-- Info Jadwal --}}
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="font-bold text-gray-800 text-sm">
                                                    {{ $jadwal->waktu_bimbingan ? $jadwal->waktu_bimbingan->translatedFormat('l, d F Y - H:i') : '-' }}
                                                </span>
                                                {{-- Badge Status --}}
                                                @if($jadwal->status == 'dijadwalkan')
                                                    <span class="bg-blue-100 text-blue-700 text-[10px] px-2 py-0.5 rounded font-bold">Terjadwal</span>
                                                @elseif($jadwal->status == 'pengajuan_reschedule')
                                                    <span class="bg-orange-100 text-orange-700 text-[10px] px-2 py-0.5 rounded font-bold animate-pulse">Minta Reschedule</span>
                                                @elseif($jadwal->status == 'disetujui_reschedule')
                                                    <span class="bg-green-100 text-green-700 text-[10px] px-2 py-0.5 rounded font-bold">Reschedule OK</span>
                                                @endif
                                            </div>
                                            <p class="text-xs text-gray-600"><i class="fas fa-map-marker-alt mr-1"></i> {{ $jadwal->tempat }}</p>
                                            <p class="text-xs text-gray-500 mt-1 italic">Topik: "{{ $jadwal->topik }}"</p>
                                        </div>

                                        {{-- AKSI DOSEN (Jika ada request reschedule) --}}
                                        @if($jadwal->status == 'pengajuan_reschedule')
                                            <div class="bg-orange-50 p-3 rounded-lg border border-orange-200 w-full md:w-auto">
                                                <p class="text-[10px] font-bold text-orange-800 uppercase mb-1">Permintaan Perubahan</p>
                                                <p class="text-xs text-gray-700 mb-2">
                                                    Baru: <strong>{{ $jadwal->waktu_reschedule->translatedFormat('d M Y, H:i') }}</strong><br>
                                                    Alasan: "{{ $jadwal->alasan_reschedule }}"
                                                </p>
                                                <div class="flex gap-2">
                                                    {{-- Form Terima --}}
                                                    <form action="{{ route('dosen.proposal.jadwal.respon', $jadwal->id) }}" method="POST">
                                                        @csrf @method('PUT')
                                                        <input type="hidden" name="keputusan" value="terima">
                                                        <button class="bg-green-600 text-white text-xs px-3 py-1 rounded font-bold hover:bg-green-700">Terima</button>
                                                    </form>
                                                    {{-- Form Tolak --}}
                                                    <form action="{{ route('dosen.proposal.jadwal.respon', $jadwal->id) }}" method="POST">
                                                        @csrf @method('PUT')
                                                        <input type="hidden" name="keputusan" value="tolak">
                                                        <button class="bg-red-600 text-white text-xs px-3 py-1 rounded font-bold hover:bg-red-700">Tolak</button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-gray-400 text-sm">
                                    Belum ada jadwal bimbingan. Klik "Buat Baru".
                                </div>
                            @endforelse
                        </div>

                        {{-- TAB 2: CREATE JADWAL (Default Hidden) --}}
                        <div id="tab-create" class="hidden">
                            <form action="{{ route('dosen.proposal.jadwal.store', $proposal->id) }}" method="POST">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 mb-1">Waktu Bimbingan</label>
                                        <input type="datetime-local" name="waktu_bimbingan" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 mb-1">Tempat / Link</label>
                                        <input type="text" name="tempat" placeholder="R. 303 atau Link Zoom" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500" required>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-gray-500 mb-1">Topik Bahasan</label>
                                    <textarea name="topik" rows="2" class="w-full border-gray-300 rounded-lg text-sm focus:ring-blue-500" placeholder="Contoh: Revisi Bab 1" required></textarea>
                                </div>
                                <div class="flex justify-end gap-2">
                                    <button type="button" onclick="switchTab('list')" class="text-gray-500 text-sm font-bold px-4 py-2 hover:text-gray-700">Batal</button>
                                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold shadow hover:bg-blue-700">Simpan Jadwal</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                {{-- ================================================= --}}
                {{-- BAGIAN 2: FORM PENILAIAN PROPOSAL                 --}}
                {{-- ================================================= --}}
                <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                    <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4 border-b pb-2">
                        <i class="fas fa-gavel mr-2"></i> Form Penilaian Proposal
                    </h3>

                    <form id="formKeputusan" action="{{ route('dosen.proposal.keputusan', $proposal->id) }}" method="POST">
                        @csrf @method('PUT')

                        {{-- Mode Pilihan --}}
                        <div class="flex gap-4 mb-6">
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="keputusan" value="nilai" class="peer hidden" onchange="toggleForm('nilai')">
                                <div class="p-4 rounded-xl border-2 text-center transition hover:bg-blue-50 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:text-blue-700">
                                    <div class="mb-2 text-2xl"><i class="fas fa-calculator"></i></div>
                                    <div class="font-bold text-sm">Berikan Nilai</div>
                                    <div class="text-[10px] text-gray-500">Nilai &gt; 75 Lulus</div>
                                </div>
                            </label>

                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="keputusan" value="tolak" class="peer hidden" onchange="toggleForm('tolak')">
                                <div class="p-4 rounded-xl border-2 border-gray-100 text-center transition hover:bg-red-50 peer-checked:border-red-600 peer-checked:bg-red-50 peer-checked:text-red-700">
                                    <div class="mb-2 text-2xl"><i class="fas fa-ban"></i></div>
                                    <div class="font-bold text-sm">Tolak Proposal</div>
                                    <div class="text-[10px] text-gray-500">Tidak Layak</div>
                                </div>
                            </label>
                        </div>

                        {{-- Area Input Nilai --}}
                        <div id="area-nilai" class="hidden mb-6 bg-blue-50 p-6 rounded-xl border border-blue-100">
                            <label class="block text-sm font-bold text-blue-800 mb-2">Masukkan Nilai (0 - 100)</label>
                            <input type="number" id="input-nilai" name="nilai" min="0" max="100" class="w-full text-3xl font-bold text-center border-2 border-blue-200 rounded-xl p-3" placeholder="0">
                            <div class="text-center mt-2 text-xs font-medium text-blue-600">*Sistem otomatis menentukan Lulus/Revisi</div>
                        </div>

                        {{-- Area Alert Tolak --}}
                        <div id="area-tolak" class="hidden mb-6 bg-red-50 p-4 rounded-xl border border-red-100 flex items-start gap-3">
                            <i class="fas fa-exclamation-triangle text-red-500 mt-1"></i>
                            <div>
                                <h4 class="font-bold text-red-700 text-sm">Perhatian</h4>
                                <p class="text-xs text-red-600">Anda akan menolak proposal ini secara permanen. Mahasiswa harus mengajukan judul baru.</p>
                            </div>
                        </div>

                        {{-- Komentar & Submit --}}
                        <div id="area-common" class="hidden">
                            <div class="mb-6">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Catatan / Masukan</label>
                                <textarea name="komentar" rows="4" required class="w-full border-gray-300 rounded-xl text-sm shadow-sm" placeholder="Berikan catatan...">{{ $proposal->komentar }}</textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="bg-slate-900 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:bg-black transition flex items-center gap-2">
                                    <i class="fas fa-save"></i> Simpan Penilaian
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS (VANILLA JS) --}}
    <script>
        // 1. Script untuk Tabs Jadwal
        function switchTab(tabName) {
            document.getElementById('tab-list').classList.add('hidden');
            document.getElementById('tab-create').classList.add('hidden');
            document.getElementById('tab-' + tabName).classList.remove('hidden');

            // Update style tombol aktif
            if(tabName === 'list') {
                document.getElementById('btn-list').classList.add('bg-white', 'border', 'text-gray-600');
                document.getElementById('btn-list').classList.remove('bg-blue-600', 'text-white');
                document.getElementById('btn-create').classList.add('bg-blue-600', 'text-white');
                document.getElementById('btn-create').classList.remove('bg-white', 'border', 'text-gray-600');
            } else {
                document.getElementById('btn-create').classList.add('bg-white', 'border', 'text-gray-600');
                document.getElementById('btn-create').classList.remove('bg-blue-600', 'text-white');
                document.getElementById('btn-list').classList.add('bg-blue-600', 'text-white');
                document.getElementById('btn-list').classList.remove('bg-white', 'border', 'text-gray-600');
            }
        }

        // 2. Script untuk Form Penilaian
        function toggleForm(mode) {
            const areaNilai = document.getElementById('area-nilai');
            const areaTolak = document.getElementById('area-tolak');
            const areaCommon = document.getElementById('area-common');
            const inputNilai = document.getElementById('input-nilai');

            areaCommon.classList.remove('hidden');

            if (mode === 'nilai') {
                areaNilai.classList.remove('hidden');
                areaTolak.classList.add('hidden');
                inputNilai.setAttribute('required', 'required');
            } else {
                areaNilai.classList.add('hidden');
                areaTolak.classList.remove('hidden');
                inputNilai.removeAttribute('required');
                inputNilai.value = '';
            }
        }

        // 3. Konfirmasi Tolak
        document.getElementById('formKeputusan').addEventListener('submit', function(e) {
            const isTolak = document.querySelector('input[name="keputusan"][value="tolak"]').checked;
            if (isTolak) {
                if (!confirm('Apakah Anda yakin ingin MENOLAK proposal ini secara permanen?')) {
                    e.preventDefault();
                }
            }
        });
    </script>
</x-app-layout>