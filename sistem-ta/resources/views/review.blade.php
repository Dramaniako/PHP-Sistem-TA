<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Proposal - FMIPA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Custom Scrollbar agar lebih estetik */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 20px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #d1d5db;
        }
    </style>
</head>
<body class="bg-gray-100 h-screen flex flex-col font-sans">

    <nav class="bg-[#2D2D2D] text-white p-4 shadow-lg shrink-0">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <div class="bg-white p-2 rounded-lg">
                    <img src="https://upload.wikimedia.org/wikipedia/id/2/2d/Logo-Unud.png" alt="Logo" class="h-10">
                </div>
                <div>
                    <p class="text-xs text-gray-400">FMIPA</p>
                    <h1 class="text-xl font-bold uppercase tracking-wide leading-tight">Universitas Udayana</h1>
                    <span class="bg-gray-700 text-[10px] px-3 py-0.5 rounded-full uppercase">Akses Proposal</span>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <div class="text-right">
                    <p class="font-semibold">I Dewa Contoh</p>
                    <p class="text-[10px] text-gray-400 font-bold uppercase text-right">Dosen</p>
                </div>
                <div class="h-12 w-12 bg-green-600 rounded-full flex items-center justify-center text-xl font-bold shadow-inner border-2 border-green-400">D</div>
            </div>
        </div>
    </nav>

    <div class="flex-1 overflow-hidden flex flex-col container mx-auto px-4 py-6 max-w-7xl">
        
        <div class="bg-white p-6 rounded-3xl shadow-sm mb-6 border border-gray-100 shrink-0">
            <h2 class="text-2xl font-black text-gray-800 mb-4 tracking-tight">Cari Mahasiswa</h2>
            <div class="relative">
                <i class="fas fa-search-plus absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                <input type="text" placeholder="Silakan cari data mahasiswa berdasarkan NIM atau nama." 
                       class="w-full pl-14 pr-6 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all placeholder:text-gray-400">
            </div>
        </div>

        <div class="flex-1 flex gap-8 overflow-hidden">
            
            <div class="w-1/3 overflow-y-auto pr-3 custom-scrollbar">
                <h3 class="font-bold text-gray-700 mb-4 flex items-center">
                    <span class="w-1.5 h-1.5 bg-black rounded-full mr-2"></span> Daftar Proposal
                </h3>
                
                <div class="space-y-4">
                    @php
                        $proposals = [
                            ['nama' => 'Amba Penat', 'nim' => '1908561034', 'judul' => 'Analisis Sentimen Masyarakat Terhadap Kebijakan Energi Terbarukan...'],
                            ['nama' => 'Made Adi Wirawan', 'nim' => '2108561067', 'judul' => 'Implementasi Machine Learning untuk Klasifikasi Data Genomik'],
                            ['nama' => 'Sie Imut', 'nim' => '2408561029', 'judul' => 'Analisis Tingkat Optimasi Algoritma Genetika Dalam Hukum...'],
                        ];
                    @endphp

                    @foreach($proposals as $p)
                    <div class="bg-white p-5 rounded-3xl shadow-sm border-2 border-transparent hover:border-blue-500 cursor-pointer transition-all group active:scale-95">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-sm font-bold text-gray-800 flex items-center">
                                <i class="far fa-user mr-2 text-gray-400"></i>{{ $p['nama'] }}
                            </span>
                            <span class="text-[10px] bg-gray-100 px-3 py-1 rounded-full font-bold text-gray-500">{{ $p['nim'] }}</span>
                        </div>
                        <p class="text-[13px] font-bold leading-relaxed text-gray-700 mb-3 group-hover:text-blue-600">"{{ $p['judul'] }}"</p>
                        <p class="text-[10px] text-gray-400 flex items-center uppercase font-semibold">
                            Diajukan : <span class="ml-1 text-gray-500">32 Februari 2025</span>
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="w-2/3 bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-y-auto custom-scrollbar p-10">
                <h3 class="font-bold text-gray-700 mb-6 flex items-center">
                    <span class="w-1.5 h-1.5 bg-black rounded-full mr-2"></span> Detail Proposal
                </h3>
                
                <div class="flex gap-8 p-8 border border-gray-100 rounded-[2rem] mb-10 items-center bg-gray-50/30">
                    <img src="https://i.pravatar.cc/150?u=amba" class="w-28 h-28 rounded-[1.5rem] object-cover bg-gray-200 border-4 border-white shadow-sm">
                    <div class="flex-1 grid grid-cols-2 gap-y-6 gap-x-4">
                        <div>
                            <p class="text-[10px] uppercase font-black text-gray-400 tracking-wider">Nomor Induk Mahasiswa</p>
                            <span class="bg-white px-4 py-1.5 rounded-xl text-sm font-bold border border-gray-100 mt-1 inline-block">1908561034</span>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase font-black text-gray-400 tracking-wider">Program Studi</p>
                            <p class="font-bold text-gray-800 mt-1 uppercase text-sm">Informatika</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-[10px] uppercase font-black text-gray-400 tracking-wider">Nama Tanpa Gelar Akademik</p>
                            <p class="text-xl font-black text-gray-800 mt-1">Amba Penat</p>
                            <p class="text-[11px] text-blue-600 font-bold mt-2 flex items-center bg-blue-50 w-fit px-3 py-1 rounded-full border border-blue-100 italic">
                                <i class="fas fa-graduation-cap mr-2"></i> Mahasiswa Bimbingan
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-8 mb-12">
                    <div>
                        <p class="text-[10px] uppercase font-black text-gray-400 mb-2 flex items-center">
                            <i class="fas fa-bookmark mr-2"></i> Judul Proposal
                        </p>
                        <p class="text-lg font-black leading-snug text-gray-800">
                            "Analisis Sentimen Masyarakat Terhadap Kebijakan Energi Terbarukan Menggunakan Metode Deep Learning"
                        </p>
                    </div>

                    <div>
                        <p class="text-[10px] uppercase font-black text-gray-400 mb-3 flex items-center">
                            <i class="fas fa-file-pdf mr-2 text-red-500"></i> Dokumen Proposal
                        </p>
                        <div class="flex items-center justify-between p-4 bg-gray-50 border border-dashed border-gray-300 rounded-3xl group hover:border-blue-400 transition-all">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-red-100 text-red-600 rounded-2xl flex items-center justify-center mr-4">
                                    <i class="far fa-file-pdf text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">Proposal_TA_AmbaPenat_1908561034.pdf</p>
                                    <p class="text-[10px] text-gray-400 font-bold">2.4 MB • Diunggah 2 hari lalu</p>
                                </div>
                            </div>
                            <button class="w-10 h-10 bg-white border border-gray-200 rounded-full flex items-center justify-center text-gray-400 hover:text-blue-600 hover:border-blue-600 transition-all shadow-sm">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-gray-100">
                    <h3 class="font-bold text-gray-700 mb-6 flex items-center uppercase tracking-widest text-xs">
                        <span class="w-1.5 h-1.5 bg-black rounded-full mr-2"></span> Formulir Review
                    </h3>
                    
                    <form action="#" method="POST" class="space-y-4">
                        <p class="text-sm font-bold text-gray-600 mb-3">Status Keputusan <span class="text-red-500 font-black">*</span></p>
                        
                        <label class="relative block p-5 border border-gray-100 rounded-3xl cursor-pointer hover:bg-gray-50 transition-all group overflow-hidden">
                            <input type="radio" name="status" value="setuju" class="peer hidden">
                            <div class="flex justify-between items-center relative z-10">
                                <div>
                                    <p class="font-black text-gray-800">Setuju</p>
                                    <p class="text-[11px] text-gray-400 font-medium">Proposal diterima tanpa revisi</p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white opacity-20 group-hover:opacity-100 peer-checked:opacity-100 transition-all shadow-sm">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                            <div class="absolute inset-0 border-2 border-transparent peer-checked:border-green-500 rounded-3xl pointer-events-none transition-all"></div>
                        </label>

                        <label class="relative block p-5 border border-gray-100 rounded-3xl cursor-pointer hover:bg-gray-50 transition-all group overflow-hidden">
                            <input type="radio" name="status" value="revisi" class="peer hidden">
                            <div class="flex justify-between items-center relative z-10">
                                <div>
                                    <p class="font-black text-gray-800">Revisi</p>
                                    <p class="text-[11px] text-gray-400 font-medium">Perlu perbaikan (Mayor/Minor)</p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-yellow-400 flex items-center justify-center text-white opacity-20 group-hover:opacity-100 peer-checked:opacity-100 transition-all shadow-sm">
                                    <i class="fas fa-pen-nib text-sm"></i>
                                </div>
                            </div>
                            <div class="absolute inset-0 border-2 border-transparent peer-checked:border-yellow-400 rounded-3xl pointer-events-none transition-all"></div>
                        </label>

                        <label class="relative block p-5 border border-gray-100 rounded-3xl cursor-pointer hover:bg-gray-50 transition-all group overflow-hidden">
                            <input type="radio" name="status" value="tolak" class="peer hidden">
                            <div class="flex justify-between items-center relative z-10">
                                <div>
                                    <p class="font-black text-gray-800 text-red-600">Tolak</p>
                                    <p class="text-[11px] text-gray-400 font-medium">Proposal tidak layak dilanjutkan</p>
                                </div>
                                <div class="w-10 h-10 rounded-full bg-red-500 flex items-center justify-center text-white opacity-20 group-hover:opacity-100 peer-checked:opacity-100 transition-all shadow-sm">
                                    <i class="fas fa-times"></i>
                                </div>
                            </div>
                            <div class="absolute inset-0 border-2 border-transparent peer-checked:border-red-500 rounded-3xl pointer-events-none transition-all"></div>
                        </label>

                        <div class="mt-8">
                            <div class="flex justify-between items-center mb-3">
                                <label class="text-sm font-black text-gray-800 uppercase tracking-tight">Komentar / Catatan <span class="text-red-500">*</span></label>
                                <span class="text-[9px] bg-gray-100 px-3 py-1 rounded-full text-gray-400 font-black uppercase border border-gray-200">Required</span>
                            </div>
                            <textarea rows="5" class="w-full p-6 bg-gray-50 border border-gray-100 rounded-[2rem] focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all placeholder:text-gray-300 shadow-inner"
                                      placeholder="Tuliskan detail masukan, poin revisi, atau alasan penolakan di sini..."></textarea>
                        </div>

                        <div class="mt-10 flex justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-black py-5 px-12 rounded-3xl shadow-xl shadow-blue-200 transition-all hover:-translate-y-1 active:scale-95 flex items-center">
                                Simpan Keputusan <i class="fas fa-paper-plane ml-3"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</body>
</html>