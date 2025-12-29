<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Universitas Udayana</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">

    {{-- KARTU KECIL MENGAMBANG --}}
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 relative overflow-hidden">
        
        {{-- Hiasan Atas --}}
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 to-purple-600"></div>

        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                🔑
            </div>
            <h3 class="text-xl font-bold text-gray-800">Lupa Password?</h3>
            <p class="text-gray-500 text-sm mt-2 leading-relaxed">
                Jangan khawatir. Masukkan alamat email Anda dan kami akan mengirimkan link untuk mereset password.
            </p>
        </div>

        @if (session('status'))
            <div class="mb-4 bg-green-50 text-green-600 px-4 py-3 rounded-lg text-sm border border-green-100 text-center">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 bg-red-50 text-red-600 px-4 py-3 rounded-lg text-sm border border-red-100">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-6">
                <label class="block text-gray-700 text-xs font-bold mb-2 ml-1 uppercase tracking-wide">Email Terdaftar</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 transition outline-none"
                    placeholder="nama@email.com">
            </div>

            <button type="submit" class="w-full bg-neutral-800 hover:bg-black text-white font-bold py-3 rounded-xl shadow-lg transition duration-200">
                Kirim Link Reset
            </button>
        </form>

        <div class="mt-6 text-center border-t border-gray-100 pt-6">
            <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 flex items-center justify-center gap-2">
                <span>←</span> Kembali ke Login
            </a>
        </div>
    </div>

</body>
</html>