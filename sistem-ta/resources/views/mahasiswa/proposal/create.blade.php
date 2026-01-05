<x-app-layout title="Ajukan Judul Proposal">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengajuan Judul Tugas Akhir') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8"> {{-- Lebar saya ubah ke max-w-5xl agar grid muat --}}
            
            {{-- Info Box --}}
            <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
                <p class="font-bold">Informasi</p>
                <p>Silakan ajukan judul dan deskripsi singkat Tugas Akhir Anda. Pastikan dokumen dalam format PDF.</p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    
                    {{-- Form Pengajuan --}}
                    <form action="{{ route('mahasiswa.proposal.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- 1. Input Judul --}}
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Judul Proposal TA
                            </label>
                            <input type="text" name="judul" value="{{ old('judul') }}" required 
                                class="shadow appearance-none border rounded w-full py-3 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-blue-500 focus:border-blue-500" 
                                placeholder="Contoh: Implementasi Algoritma XYZ pada Sistem ABC...">
                            @error('judul')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- 2. Input Deskripsi / Abstrak --}}
                        <div class="mb-8">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Deskripsi Singkat / Ringkasan Masalah
                            </label>
                            <textarea name="deskripsi" rows="5" required 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Jelaskan secara singkat latar belakang dan tujuan penelitian...">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- ================================================= --}}
                        {{-- MULAI BAGIAN BARU: GRID INPUT FILE                --}}
                        {{-- (Menggantikan input file lama yang terpisah)      --}}
                        {{-- ================================================= --}}
                        <div class="mb-8">
                            <label class="block text-gray-700 text-sm font-bold mb-4">Upload Dokumen Persyaratan</label>
                            
                            {{-- Grid Layout untuk File --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                {{-- Kartu 1: File Proposal --}}
                                <div class="border-2 border-dashed border-gray-300 bg-gray-50 rounded-xl p-6 hover:bg-blue-50 hover:border-blue-300 transition group">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="bg-white p-2 rounded-lg text-red-500 shadow-sm"><i class="fas fa-file-pdf fa-lg"></i></div>
                                        <div>
                                            <h4 class="font-bold text-gray-700 text-sm">File Proposal (Bab 1-3)</h4>
                                            <p class="text-[10px] text-gray-400">Wajib, Format PDF (Max 5MB)</p>
                                        </div>
                                    </div>
                                    <input type="file" name="file_proposal" accept=".pdf" required 
                                        class="block w-full text-xs text-gray-500 
                                        file:mr-4 file:py-2 file:px-4 
                                        file:rounded-full file:border-0 
                                        file:text-xs file:font-semibold 
                                        file:bg-blue-600 file:text-white 
                                        hover:file:bg-blue-700 cursor-pointer">
                                    @error('file_proposal')
                                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
        
                                {{-- Kartu 2: File KHS --}}
                                <div class="border-2 border-dashed border-gray-300 bg-gray-50 rounded-xl p-6 hover:bg-yellow-50 hover:border-yellow-300 transition group">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="bg-white p-2 rounded-lg text-yellow-600 shadow-sm"><i class="fas fa-file-alt fa-lg"></i></div>
                                        <div>
                                            <h4 class="font-bold text-gray-700 text-sm">Kartu Hasil Studi (KHS)</h4>
                                            <p class="text-[10px] text-gray-400">Wajib, Format PDF (Max 5MB)</p>
                                        </div>
                                    </div>
                                    <input type="file" name="file_khs" accept=".pdf" required 
                                        class="block w-full text-xs text-gray-500 
                                        file:mr-4 file:py-2 file:px-4 
                                        file:rounded-full file:border-0 
                                        file:text-xs file:font-semibold 
                                        file:bg-yellow-600 file:text-white 
                                        hover:file:bg-yellow-700 cursor-pointer">
                                    @error('file_khs')
                                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        {{-- ================================================= --}}
                        {{-- AKHIR BAGIAN BARU                                 --}}
                        {{-- ================================================= --}}

                        {{-- Tombol Submit --}}
                        <div class="flex items-center justify-end pt-6 border-t border-gray-100 mt-6">
                            <a href="{{ route('mahasiswa.sidang.index') }}" class="text-gray-500 hover:text-gray-700 font-bold py-2 px-4 rounded mr-4 transition">
                                Batal
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg transform transition hover:-translate-y-0.5 hover:shadow-blue-200 flex items-center gap-2">
                                <i class="fas fa-paper-plane"></i> Kirim Pengajuan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>