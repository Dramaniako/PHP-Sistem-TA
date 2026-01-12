<x-app-layout title="Jadwal Sidang & Request">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Sidang Dosen') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ openModal: false, mhsName: '', actionUrl: '' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- Alert Notifikasi --}}
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 p-4 text-green-700 shadow-sm rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Menampilkan Error Validasi (Misal file terlalu besar) --}}
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 p-4 text-red-700 shadow-sm rounded">
                    <ul class="list-disc ml-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- SECTION 2: DAFTAR JADWAL MENGUJI --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden border border-gray-100">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-calendar-alt mr-2 text-blue-600"></i> Jadwal Menguji Saya
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Waktu</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Mahasiswa</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($jadwals as $jadwal)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 text-sm">
                                            <span class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}</span><br>
                                            <span class="text-gray-500">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 font-medium">
                                            {{ $jadwal->mahasiswa->name }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <button @click="openModal = true; mhsName = '{{ $jadwal->mahasiswa->name }}'; actionUrl = '{{ route('dosen.sidang.ajukan_perubahan', $jadwal->id) }}'" 
                                                class="inline-flex items-center text-amber-600 hover:text-amber-800 text-sm font-bold bg-amber-50 px-3 py-1.5 rounded-lg border border-amber-100 transition">
                                                <i class="fas fa-exchange-alt mr-2"></i> Reschedule
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL RESCHEDULE --}}
        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4" x-cloak>
            <div class="bg-white rounded-2xl p-8 max-w-md w-full shadow-2xl transform transition-all" @click.away="openModal = false">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Ajukan Perubahan</h3>
                    <button @click="openModal = false" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                </div>
                <p class="text-sm text-gray-500 mb-6 italic">Mahasiswa: <span x-text="mhsName" class="font-bold text-gray-800 not-italic"></span></p>
                
                <form :action="actionUrl" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Berhalangan</label>
                        <textarea name="alasan" rows="3" required placeholder="Contoh: Menghadiri Konferensi / Tugas Luar Kota..." 
                            class="w-full border-gray-300 rounded-xl text-sm focus:ring-amber-500 focus:border-amber-500 shadow-sm"></textarea>
                    </div>
                    
                    <div class="p-4 bg-blue-50 rounded-xl border-2 border-dashed border-blue-200">
                        <label class="block text-xs font-bold text-blue-800 mb-2 uppercase tracking-wide">Lampirkan Surat Tugas (PDF/JPG)</label>
                        <input type="file" name="surat_kerja" required class="text-xs block w-full file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 transition">
                        <p class="mt-2 text-[10px] text-blue-600 italic">*Wajib untuk memvalidasi permintaan Anda ke Koordinator.</p>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" @click="openModal = false" class="px-4 py-2 text-sm font-medium text-gray-500">Batal</button>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-lg hover:bg-blue-700 transition flex items-center">
                            <i class="fas fa-paper-plane mr-2"></i> Kirim Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>