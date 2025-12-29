<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Universitas Udayana</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">

    {{-- KARTU REGISTER --}}
    <div class="flex w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden">
        
        {{-- KIRI: FORM --}}
        <div class="w-full md:w-1/2 p-8 md:p-12 order-2 md:order-1">
            <div class="mb-8">
                <h3 class="text-2xl font-bold text-gray-800">Buat Akun Baru</h3>
                <p class="text-gray-500 text-sm mt-2">Lengkapi data diri Anda untuk mendaftar.</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 bg-red-50 text-red-600 px-4 py-3 rounded-lg text-sm border border-red-100">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                
                {{-- Nama --}}
                <div class="mb-4">
                    <label class="block text-gray-700 text-xs font-bold mb-1 ml-1 uppercase">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 transition outline-none"
                        placeholder="Nama Lengkap Anda">
                </div>

                {{-- [BARU] Input NIM --}}
                <div class="mb-4">
                    <label class="block text-gray-700 text-xs font-bold mb-1 ml-1 uppercase">NIM</label>
                    <input type="text" name="nim" value="{{ old('nim') }}" required
                        class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 transition outline-none"
                        placeholder="Contoh: 2208561xxx">
                </div>

                {{-- [BARU] Input Email --}}
                <div class="mb-4">
                    <label class="block text-gray-700 text-xs font-bold mb-1 ml-1 uppercase">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 transition outline-none"
                        placeholder="nama@student.unud.ac.id">
                </div>

                {{-- Password & Confirm --}}
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 text-xs font-bold mb-1 ml-1 uppercase">Password</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:outline-none focus:border-blue-500" placeholder="••••••••">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-xs font-bold mb-1 ml-1 uppercase">Ulangi Password</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:outline-none focus:border-blue-500" placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-blue-200 transition duration-200">
                    Daftar Sekarang
                </button>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-500">Sudah punya akun? 
                        <a href="{{ route('login') }}" class="font-bold text-gray-800 hover:underline">Login disini</a>
                    </p>
                </div>
            </form>
        </div>

        {{-- KANAN: DEKORASI (Hitam) --}}
        <div class="hidden md:flex w-1/2 bg-neutral-900 text-white flex-col justify-center items-center p-12 relative order-1 md:order-2">
            <div class="relative z-10 text-center">
                <h2 class="text-3xl font-bold mb-4">Bergabunglah Bersama Kami</h2>
                <p class="text-gray-400 leading-relaxed px-8">
                    Sistem Tugas Akhir Terpadu untuk memudahkan administrasi dan penjadwalan sidang mahasiswa FMIPA.
                </p>
            </div>
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 24px 24px;"></div>
        </div>

    </div>

</body>
</html>