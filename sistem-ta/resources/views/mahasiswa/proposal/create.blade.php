<x-app-layout title="Ajukan Judul Proposal">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengajuan Judul Tugas Akhir') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Info Box --}}
            <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-6" role="alert">
                <p class="font-bold">Informasi</p>
                <p>Silakan ajukan judul dan deskripsi singkat Tugas Akhir Anda. File proposal wajib dalam format PDF.</p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Form Pengajuan --}}
                    {{-- PENTING: enctype ditambahkan di sini --}}
                    <form action="{{ route('mahasiswa.proposal.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Input Judul --}}
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

                        {{-- Input Deskripsi / Abstrak --}}
                        <div class="mb-6">
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

                        {{-- [BARU] Input File Proposal --}}
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Upload File Proposal (PDF)
                            </label>
                            <input type="file" name="file_proposal" accept=".pdf" required
                                class="block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-blue-50 file:text-blue-700
                                hover:file:bg-blue-100 border border-gray-300 rounded cursor-pointer">
                            <p class="text-xs text-gray-500 mt-1">*Format wajib PDF, Maksimal 5MB.</p>
                            
                            @error('file_proposal')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Upload KHS Terakhir (PDF)</label>
                            <input type="file" name="file_khs" accept=".pdf" required
                                class="w-full border border-gray-300 rounded-lg p-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">*Sebagai bukti pemenuhan SKS.</p>
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('mahasiswa.sidang.index') }}" class="text-gray-500 hover:text-gray-700 font-bold py-2 px-4 rounded mr-2">
                                Batal
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow-lg transform transition hover:scale-105">
                                🚀 Kirim Pengajuan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>