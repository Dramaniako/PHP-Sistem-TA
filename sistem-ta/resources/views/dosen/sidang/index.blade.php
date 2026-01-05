<x-app-layout title="Jadwal Sidang & Request">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Sidang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- BAGIAN 1: NOTIFIKASI PENGAJUAN PERUBAHAN (Hanya muncul jika ada request) --}}
            @if($pengajuans->count() > 0)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg shadow-md">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-bell text-yellow-600 text-2xl mr-3"></i>
                        <h3 class="text-lg font-bold text-yellow-800">
                            Ada {{ $pengajuans->count() }} Permintaan Perubahan Jadwal
                        </h3>
                    </div>

                    <div class="grid gap-4">
                        @foreach($pengajuans as $p)
                            <div class="bg-white p-4 rounded-md shadow-sm border border-yellow-200 flex flex-col md:flex-row justify-between items-center gap-4">
                                
                                {{-- Info Request --}}
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-bold text-gray-800">{{ $p->sidang->mahasiswa->name }}</span>
                                        <span class="text-xs bg-gray-200 px-2 py-0.5 rounded text-gray-600">
                                            {{ $p->sidang->jenis_sidang }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        <span class="line-through text-red-400 mr-2">
                                            {{ $p->sidang->tanggal }} ({{ $p->sidang->jam_mulai }})
                                        </span>
                                        <i class="fas fa-arrow-right text-gray-400 mx-1"></i>
                                        <span class="font-bold text-green-600">
                                            {{ $p->tanggal_saran }} ({{ $p->jam_saran }})
                                        </span>
                                    </p>
                                    <p class="text-sm italic text-gray-500 mt-1">
                                        "{{ $p->alasan }}"
                                    </p>
                                </div>

                                {{-- Tombol Aksi --}}
                                <div class="flex items-center gap-2">
                                    {{-- Form Terima --}}
                                    <form action="{{ route('dosen.sidang.proses_pengajuan', $p->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="keputusan" value="terima">
                                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-bold transition">
                                            <i class="fas fa-check mr-1"></i> Setuju
                                        </button>
                                    </form>

                                    {{-- Form Tolak --}}
                                    <form action="{{ route('dosen.sidang.proses_pengajuan', $p->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="keputusan" value="tolak">
                                        <button type="submit" class="px-4 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 text-sm font-bold transition">
                                            <i class="fas fa-times mr-1"></i> Tolak
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- BAGIAN 2: DAFTAR JADWAL SIDANG SAYA --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-800">Jadwal Menguji Saya</h3>
                        {{-- Tombol buat manual jika perlu --}}
                        <a href="{{ route('dosen.sidang.create') }}" class="text-sm text-blue-600 hover:underline">
                            + Buat Jadwal Manual
                        </a>
                    </div>

                    @if($jadwals->isEmpty())
                        <p class="text-center text-gray-500 py-8">Belum ada jadwal sidang.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal & Jam</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mahasiswa</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul TA</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lokasi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($jadwals as $jadwal)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $jadwal->tanggal }} <br>
                                            <span class="text-gray-500">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $jadwal->mahasiswa->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ Str::limit($jadwal->judul_ta, 30) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $jadwal->lokasi }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $jadwal->status == 'dijadwalkan' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($jadwal->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>