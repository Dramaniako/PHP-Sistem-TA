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
                <p>Silakan ajukan judul dan deskripsi singkat Tugas Akhir Anda. Koordinator akan menetapkan Dosen Pembimbing & Penguji setelah Anda mengirim form ini.</p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Form Pengajuan --}}
                    <form action="{{ route('mahasiswa.proposal.store') }}" method="POST">
                        @csrf

                        {{-- Input Judul --}}
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Judul Proposal TA
                            </label>
                            <input type="text" name="judul" required 
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
                                placeholder="Jelaskan secara singkat latar belakang dan tujuan penelitian..."></textarea>
                            @error('deskripsi')
                                <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('sidang.index') }}" class="text-gray-500 hover:text-gray-700 font-bold py-2 px-4 rounded mr-2">
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