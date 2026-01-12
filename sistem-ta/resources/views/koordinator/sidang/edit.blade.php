<x-app-layout title="Edit Jadwal Sidang">
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('koordinator.sidang.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Jadwal Sidang') }}</h2>
        </div>
    </x-slot>

    <div class="py-12" x-data="sidangForm()" x-init="fetchData('{{ $sidang->mahasiswa_id }}')">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- KOLOM KIRI: FORM JADWAL --}}
                <div class="lg:col-span-2">
                    <form action="{{ route('koordinator.sidang.update', $sidang->id) }}" method="POST"
                        class="bg-white rounded-xl shadow-sm border p-8">
                        @csrf
                        @method('PUT')

                        {{-- INPUT HIDDEN WAJIB --}}
                        <input type="hidden" name="proposal_id" x-model="proposal_id">
                        <template x-for="id in penguji_ids">
                            <input type="hidden" name="dosen_penguji_id[]" :value="id">
                        </template>

                        <div class="space-y-6">
                            <div>
                                <h4 class="font-bold border-b pb-2 mb-4">1. Data Mahasiswa</h4>
                                <input type="text"
                                    class="w-full bg-gray-50 border-gray-300 rounded-xl p-3 text-gray-500"
                                    value="{{ $sidang->mahasiswa->name }}" disabled>
                            </div>

                            <div>
                                <h4 class="font-bold border-b pb-2 mb-4">2. Detail Pelaksanaan</h4>
                                <div class="grid grid-cols-1 gap-4">
                                    <select name="jenis_sidang" x-model="jenis_sidang"
                                        class="w-full border-gray-300 rounded-xl p-3">
                                        <option value="Sidang Proposal">Sidang Proposal</option>
                                        <option value="Sidang Akhir">Sidang Akhir</option>
                                    </select>
                                    <div class="grid grid-cols-2 gap-4">
                                        <input type="date" name="tanggal_sidang" value="{{ $sidang->tanggal }}"
                                            class="border-gray-300 rounded-xl p-3">
                                        <input type="text" name="ruangan" value="{{ $sidang->lokasi }}"
                                            class="border-gray-300 rounded-xl p-3">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <input type="time" name="jam_mulai"
                                            value="{{ \Carbon\Carbon::parse($sidang->jam_mulai)->format('H:i') }}"
                                            class="border-gray-300 rounded-xl p-3">
                                        <input type="time" name="jam_selesai"
                                            value="{{ \Carbon\Carbon::parse($sidang->jam_selesai)->format('H:i') }}"
                                            class="border-gray-300 rounded-xl p-3">
                                    </div>

                                    {{-- STATUS: SESUAI ENUM DATABASE --}}
                                    <div>
                                        <label class="block text-sm font-bold mb-1">Status Sidang</label>
                                        <select name="status"
                                            class="w-full border-gray-300 rounded-xl p-3 bg-blue-50 border-blue-200">
                                            <option value="dijadwalkan" {{ $sidang->status == 'dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
                                            <option value="selesai" {{ $sidang->status == 'selesai' ? 'selected' : '' }}>
                                                Selesai</option>
                                            <option value="dibatalkan" {{ $sidang->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                            <option value="reschedule" {{ $sidang->status == 'reschedule' ? 'selected' : '' }}>Reschedule</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-6 border-t flex justify-end gap-3">
                                <button type="submit"
                                    class="px-8 py-3 rounded-xl bg-blue-600 text-white font-bold shadow-lg">Update
                                    Jadwal</button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- KOLOM KANAN: PREVIEW PENGUJI --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-lg border-t-4 border-blue-600 p-6">
                        <h5 class="text-xs font-bold text-gray-400 uppercase mb-4">Tim Penguji (Statis)</h5>
                        <div class="space-y-3">
                            <template x-for="(name, index) in penguji_names">
                                <div class="p-3 bg-gray-50 rounded-lg border text-sm font-semibold text-gray-700"
                                    x-text="(index+1) + '. ' + name"></div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function sidangForm() {
            return {
                loading: true,
                proposal_id: '{{ $sidang->proposal_id }}',
                penguji_names: [],
                penguji_ids: [],
                ta_link: null, // Inisialisasi link TA

                fetchData(id) {
                    fetch(`/koordinator/sidang/api/get-proposal/${id}`)
                        .then(res => res.json())
                        .then(data => {
                            this.proposal_id = data.proposal_id;
                            this.penguji_names = data.penguji_list || [];
                            this.penguji_ids = data.penguji_ids || [];

                            // Link Download mengarah ke Route (Bukan Path File Langsung)
                            this.ta_link = data.file_ta ? `/koordinator/sidang/download-ta/${data.proposal_id}` : null;
                        })
                        .finally(() => this.loading = false);
                }
            }
        }
    </script>
</x-app-layout>