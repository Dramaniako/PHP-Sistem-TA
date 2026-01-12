<x-app-layout title="Status Proposal Saya">
    <div class="py-12 px-4 sm:px-6 lg:px-8 max-w-5xl mx-auto">
        
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Status Pengajuan Proposal</h2>
            <p class="text-gray-500 text-sm">Pantau perkembangan proposal Tugas Akhir Anda di sini.</p>
        </div>

        @if(!$proposal)
            {{-- KONDISI 1: BELUM UPLOAD --}}
            <div class="bg-white rounded-xl shadow-sm p-10 text-center border border-dashed border-gray-300">
                <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                    <i class="fas fa-folder-open text-3xl"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-700">Belum ada Proposal</h3>
                <p class="text-gray-500 mb-6">Anda belum mengunggah judul atau proposal TA.</p>
                <a href="{{ route('mahasiswa.proposal.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700 transition">
                    + Ajukan Proposal Baru
                </a>
            </div>
        @else
            {{-- KONDISI 2: SUDAH UPLOAD (TAMPILKAN STATUS) --}}
            
            <div class="bg-white rounded-t-2xl shadow-sm border border-gray-100 p-8">
                <div class="flex flex-col md:flex-row justify-between md:items-start gap-4">
                    <div>
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Judul Proposal</span>
                        <h1 class="text-xl md:text-2xl font-bold text-gray-900 mt-1 leading-snug">
                            "{{ $proposal->judul }}"
                        </h1>
                        <p class="text-sm text-gray-500 mt-2">
                            <i class="far fa-clock mr-1"></i> Diajukan pada: {{ $proposal->created_at->isoFormat('D MMMM Y, HH:mm') }}
                        </p>
                    </div>
                    
                    <div class="flex-shrink-0">
                        @if($proposal->status == 'pending')
                            <span class="bg-yellow-100 text-yellow-700 px-6 py-2 rounded-full font-bold text-sm border border-yellow-200 shadow-sm flex items-center gap-2">
                                <i class="fas fa-spinner fa-spin"></i> Menunggu Review
                            </span>
                        @elseif($proposal->status == 'disetujui')
                            <span class="bg-green-100 text-green-700 px-6 py-2 rounded-full font-bold text-sm border border-green-200 shadow-sm flex items-center gap-2">
                                <i class="fas fa-check-circle"></i> Disetujui
                            </span>
                        @elseif($proposal->status == 'revisi')
                            <span class="bg-orange-100 text-orange-700 px-6 py-2 rounded-full font-bold text-sm border border-orange-200 shadow-sm flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i> Perlu Revisi
                            </span>
                        @elseif($proposal->status == 'ditolak')
                            <span class="bg-red-100 text-red-700 px-6 py-2 rounded-full font-bold text-sm border border-red-200 shadow-sm flex items-center gap-2">
                                <i class="fas fa-times-circle"></i> Ditolak
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-b-2xl shadow-sm border-x border-b border-gray-100 p-8 space-y-6">
                
                @if($proposal->status == 'disetujui')
                    <div class="bg-green-50 border border-green-200 rounded-xl p-6 flex items-center gap-6">
                        <div class="bg-white p-4 rounded-full shadow-sm text-green-600 text-2xl">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-green-600 uppercase mb-1">Dosen Pembimbing Ditetapkan</p>
                            <h4 class="text-lg font-bold text-gray-900">{{ $proposal->dosenPembimbing->name ?? 'Belum ditentukan' }}</h4>
                            <p class="text-sm text-gray-600">Silakan segera menghubungi dosen pembimbing untuk proses bimbingan selanjutnya.</p>
                        </div>
                    </div>
                @endif

                {{-- JIKA REVISI: TAMPILKAN CATATAN & FORM UPLOAD ULANG --}}
                @if($proposal->status == 'revisi')
                    <div class="bg-orange-50 border border-orange-200 rounded-xl p-6">
                        <div class="flex items-start gap-4">
                            <i class="fas fa-comment-dots text-orange-500 text-xl mt-1"></i>
                            <div class="w-full">
                                <h4 class="font-bold text-orange-800 mb-2">Catatan Revisi dari Koordinator/Dosen:</h4>
                                <div class="bg-white p-4 rounded-lg border border-orange-100 text-gray-700 text-sm italic mb-6">
                                    "{{ $proposal->komentar ?? 'Tidak ada catatan khusus, silakan hubungi koordinator.' }}"
                                </div>

                                {{-- Form Re-upload untuk Revisi --}}
                                <div class="bg-white p-6 rounded-xl border border-orange-200 shadow-sm">
                                    <h5 class="text-sm font-bold text-gray-700 mb-4">Upload Hasil Perbaikan Proposal</h5>
                                    <form action="{{ route('mahasiswa.proposal.update_revisi', $proposal->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">File Proposal Baru (PDF)</label>
                                                <input type="file" name="file_proposal" accept=".pdf" required
                                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                                            </div>
                                            <button type="submit" class="bg-orange-600 text-white px-5 py-2 rounded-lg text-sm font-bold hover:bg-orange-700 transition">
                                                Kirim Perbaikan Proposal
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- JIKA DITOLAK --}}
                @if($proposal->status == 'ditolak')
                    <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                        <h4 class="font-bold text-red-800 mb-2"><i class="fas fa-ban mr-2"></i> Pengajuan Ditolak</h4>
                        <p class="text-sm text-red-600 mb-4">Mohon maaf, proposal Anda belum dapat diterima. Silakan lihat catatan di bawah dan ajukan judul baru.</p>
                        @if($proposal->komentar)
                            <div class="bg-white p-3 rounded border border-red-100 text-gray-600 text-sm">
                                <strong>Alasan:</strong> {{ $proposal->komentar }}
                            </div>
                        @endif
                        <div class="mt-4">
                            <a href="{{ route('mahasiswa.proposal.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-red-700">
                                Ajukan Judul Baru
                            </a>
                        </div>
                    </div>
                @endif

                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <a href="{{ route('mahasiswa.proposal.download', $proposal->id) }}" class="text-gray-500 hover:text-blue-600 font-bold text-sm flex items-center gap-2">
                        <i class="fas fa-file-pdf"></i> Lihat Dokumen yang Saya Upload
                    </a>
                </div>

            </div>
        @endif
        
        {{-- LOGIKA UPLOAD DRAFT TA (Hanya muncul jika Proposal DISETUJUI) --}}
        @if(isset($proposal) && $proposal->status == 'disetujui')
            
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg border border-green-200">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    {{-- Header Section --}}
                    <div class="flex items-start gap-4 mb-6">
                        <div class="bg-green-100 p-3 rounded-full text-green-600">
                            <i class="fas fa-file-signature text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Langkah Selanjutnya: Upload Draft TA</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Selamat proposal Anda telah disetujui! Silakan upload Draft Tugas Akhir (Lengkap) dalam format PDF agar Koordinator dapat menjadwalkan sidang.
                            </p>
                        </div>
                    </div>

                    {{-- Form Upload --}}
                    <form action="{{ route('mahasiswa.proposal.upload_draft') }}" method="POST" enctype="multipart/form-data" class="bg-gray-50 p-6 rounded-xl border border-dashed border-gray-300">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                            
                            {{-- Input File --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    File Draft TA (PDF, Max 10MB)
                                </label>
                                <input type="file" name="file_ta" accept=".pdf" required
                                    class="block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2.5 file:px-4
                                    file:rounded-lg file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100
                                    border border-gray-300 rounded-lg cursor-pointer bg-white focus:outline-none">
                                @error('file_ta')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Tombol Submit --}}
                            <div>
                                <button type="submit" class="w-full flex justify-center items-center gap-2 px-4 py-2.5 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <i class="fas fa-upload"></i> Upload Draft
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Status File Terkini --}}
                    @if($proposal->file_ta)
                        <div class="mt-6 flex items-center p-4 bg-blue-50 text-blue-800 rounded-lg border border-blue-100">
                            <i class="fas fa-check-circle text-xl mr-3"></i>
                            <div class="flex-1">
                                <span class="font-bold text-sm block">Draft TA sudah terupload</span>
                                <span class="text-xs opacity-75">Terakhir diupdate: {{ $proposal->updated_at->diffForHumans() }}</span>
                            </div>
                            <a href="{{ asset('storage/' . $proposal->file_ta) }}" target="_blank" class="px-4 py-2 bg-white text-blue-600 text-xs font-bold rounded shadow-sm hover:bg-gray-50 border border-blue-200">
                                Lihat File
                            </a>
                        </div>
                    @endif

                </div>
            </div>

        @endif
    </div>
</x-app-layout>