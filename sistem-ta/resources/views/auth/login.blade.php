<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Universitas Udayana</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">

    {{-- KARTU MENGAMBANG --}}
    <div class="flex w-full max-w-4xl bg-white rounded-2xl shadow-2xl overflow-hidden transform hover:scale-[1.01] transition-transform duration-300">
        
        {{-- BAGIAN KIRI (GAMBAR/LOG0) --}}
        <div class="hidden md:flex w-1/2 bg-neutral-900 text-white flex-col justify-center items-center p-12 relative">
            <div class="relative z-10 text-center">
                <div class="w-24 h-24 bg-neutral-800 rounded-2xl mx-auto mb-6 flex items-center justify-center text-5xl shadow-lg border border-neutral-700">
                    🛡️
                </div>
                <h2 class="text-3xl font-bold tracking-wider mb-2">SISTEM TA</h2>
                <p class="text-gray-400 text-sm">Fakultas Matematika dan Ilmu Pengetahuan Alam</p>
                <div class="mt-8 w-16 h-1 bg-blue-600 mx-auto rounded-full"></div>
            </div>
            {{-- Efek Pattern --}}
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#4b5563 1px, transparent 1px); background-size: 20px 20px;"></div>
        </div>

        {{-- BAGIAN KANAN (FORM) --}}
        <div class="w-full md:w-1/2 p-8 md:p-12">
            <div class="text-center mb-8">
                <h3 class="text-2xl font-bold text-gray-800">Selamat Datang Kembali!</h3>
                <p class="text-gray-500 text-sm mt-2">Silakan masuk untuk mengakses akun Anda.</p>
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

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="mb-5">
                    <label class="block text-gray-700 text-sm font-bold mb-2 ml-1">NIM / Email</label>
                    <input type="text" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 transition outline-none"
                        placeholder="Masukkan NIM atau Email">
                </div>

                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2 ml-1">
                        <label class="text-gray-700 text-sm font-bold">Password</label>
                        <a href="{{ route('password.request') }}" class="text-xs text-blue-600 hover:text-blue-800 hover:underline">Lupa Password?</a>
                    </div>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 transition outline-none"
                        placeholder="••••••••">
                </div>

                <button type="submit" class="w-full bg-neutral-900 hover:bg-black text-white font-bold py-3.5 rounded-xl shadow-lg shadow-gray-300/50 transition duration-200 transform hover:-translate-y-0.5">
                    Masuk Sekarang
                </button>

                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-500">Belum punya akun? 
                        <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:text-blue-800">Daftar disini</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

</body>
</html>