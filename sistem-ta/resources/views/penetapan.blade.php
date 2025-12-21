<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penetapan Penguji - FMIPA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">

    <nav class="bg-[#2D2D2D] text-white p-4 shadow-lg sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="bg-white p-2 rounded-lg">
                    <img src="https://upload.wikimedia.org/wikipedia/id/2/2d/Logo-Unud.png" alt="Logo" class="h-10">
                </div>
                <div>
                    <p class="text-xs text-gray-400">FMIPA</p>
                    <h1 class="text-xl font-bold uppercase tracking-wide">Universitas Udayana</h1>
                    <span class="bg-gray-700 text-[10px] px-3 py-1 rounded-full uppercase">Penetapan Penguji Tugas Akhir</span>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <div class="text-right">
                    <p class="font-semibold">I Made Contoh</p>
                    <p class="text-xs text-gray-400">MAHASISWA</p>
                </div>
                <div class="h-12 w-12 bg-blue-600 rounded-full flex items-center justify-center text-xl font-bold shadow-inner">M</div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto my-8 px-4 max-w-5xl">
        <div class="container mx-auto my-8 px-4 max-w-5xl">
            <form action="{{ route('penetapan.store') }}" method="POST">
                @csrf
        
        <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-200 mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Status Penetapan</h2>
            <div class="flex items-center space-x-4">
                <span class="bg-orange-100 text-orange-600 px-4 py-2 rounded-full text-sm font-bold border border-orange-200">
                    <i class="fas fa-exclamation-circle mr-2"></i>Belum ditetapkan
                </span>
                <p class="text-gray-500 italic">Lakukan Penetapan Dosen Penguji.</p>
            </div>
        </div>

        <div class="mb-10">
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <span class="w-2 h-2 bg-black rounded-full mr-3"></span> Data Mahasiswa
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div>
                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">NIM</label>
                    <div class="relative mt-1">
                        <i class="fas fa-id-card absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="nim" value="2408561xxx" readonly class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Nama Lengkap</label>
                    <div class="relative mt-1">
                        <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="nama_mahasiswa" value="I Made Contoh" readonly class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Tanggal Ujian</label>
                    <div class="relative mt-1">
                        <i class="fas fa-calendar-alt absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="tanggal_ujian" value="2023-10-27" readonly class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none">
                    </div>
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                <span class="w-2 h-2 bg-black rounded-full mr-3"></span> Dosen Penguji
            </h3>
            
            <div class="space-y-6">
                @for ($i = 1; $i <= 3; $i++)
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200 relative overflow-hidden">
                    <p class="text-xs font-bold text-gray-400 uppercase mb-4">Penguji {{ $i }}</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider text-[10px]">NIDN</label>
                            <div class="relative mt-1">
                                <i class="fas fa-id-card absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="nidn_penguji_{{ $i }}" placeholder="2408561xxx" class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider text-[10px]">Nama Lengkap Penguji</label>
                            <div class="relative mt-1">
                                <i class="fas fa-user-tie absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="nama_penguji_{{ $i }}" placeholder="Masukkan Nama Dosen" class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider text-[10px]">Status</label>
                            <div class="relative mt-1">
                                <i class="fas fa-check-circle absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="text" name="status_penguji_{{ $i }}" value="Bersedia" readonly class="w-full pl-12 pr-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-green-600 font-bold">
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        <div class="mt-10 flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-10 py-4 rounded-2xl font-bold shadow-lg hover:bg-blue-700 transition-all transform hover:-translate-y-1">
                Simpan Penetapan <i class="fas fa-save ml-2"></i>
            </button>
        </div>
    </form></div>

</body>
</html>