<x-app-layout title="Profil Koordinator">
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- ================= HEADER FAKULTAS ================= --}}
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 relative overflow-hidden">
                <h2 class="text-xl font-bold text-gray-800">
                    Fakultas Matematika dan Ilmu Pengetahuan Alam
                </h2>
                <p class="text-sm text-gray-500">Universitas Udayana</p>

                <div class="mt-4 flex gap-2">
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-md font-semibold">
                        {{ ucfirst($user->role) }}
                    </span>
                    <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs rounded-md font-semibold">
                        Aktif
                    </span>
                </div>
            </div>

            {{-- ================= PROFIL ================= --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="flex justify-between items-center px-6 py-4 border-b bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800">Profil Koordinator</h3>
                    <a href="{{ route('profile.edit') }}"
                       class="bg-yellow-400 hover:bg-yellow-500 text-white text-xs font-bold px-4 py-2 rounded">
                        Edit Profil
                    </a>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-12 gap-8">
                    {{-- FOTO --}}
                    <div class="md:col-span-3 flex justify-center">
                        <div class="relative">
                            <img src="{{ $user->profile_photo_url }}"
                                 class="w-44 h-44 rounded-lg object-cover border shadow">
                            <span class="absolute bottom-2 right-2 bg-blue-600 text-white text-xs px-2 py-1 rounded">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>

                    {{-- DATA --}}
                    <div class="md:col-span-9 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 bg-gray-50 rounded border">
                            <label class="text-xs font-bold text-gray-400">NIP</label>
                            <div class="font-bold">{{ $user->nim ?? '-' }}</div>
                        </div>

                        <div class="p-4 bg-gray-50 rounded border">
                            <label class="text-xs font-bold text-gray-400">Email</label>
                            <div>{{ $user->email }}</div>
                        </div>

                        <div class="p-4 bg-gray-50 rounded border">
                            <label class="text-xs font-bold text-gray-400">Nama</label>
                            <div class="font-bold">{{ $user->name }}</div>
                        </div>

                        <div class="p-4 bg-gray-50 rounded border">
                            <label class="text-xs font-bold text-gray-400">Program Studi</label>
                            <div>Informatika</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= STATISTIK ================= --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white p-4 rounded-lg shadow border text-center">
                    <div class="text-2xl font-bold">{{ $totalProposal }}</div>
                    <div class="text-sm text-gray-500">Total Proposal</div>
                </div>

                <div class="bg-white p-4 rounded-lg shadow border text-center">
                    <div class="text-2xl font-bold">{{ $pendingProposal }}</div>
                    <div class="text-sm text-gray-500">Proposal Pending</div>
                </div>

                <div class="bg-white p-4 rounded-lg shadow border text-center">
                    <div class="text-2xl font-bold">{{ $approvedProposal }}</div>
                    <div class="text-sm text-gray-500">Proposal Disetujui</div>
                </div>

                <div class="bg-white p-4 rounded-lg shadow border text-center">
                    <div class="text-2xl font-bold">{{ $totalPengajuan }}</div>
                    <div class="text-sm text-gray-500">Pengajuan Perubahan</div>
                </div>
            </div>

            {{-- ================= AKTIVITAS ================= --}}
            <div class="bg-white rounded-lg shadow border">
                <div class="px-6 py-4 border-b font-semibold">
                    Aktivitas Terbaru
                </div>
                <div class="p-6 space-y-2 text-sm">

                    @forelse($recentProposals as $proposal)
                        <div>
                            <strong>{{ $proposal->mahasiswa->name }}</strong>
                            mengajukan proposal
                            <span class="text-gray-400">
                                • {{ $proposal->created_at->diffForHumans() }}
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500">Belum ada aktivitas.</p>
                    @endforelse

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
