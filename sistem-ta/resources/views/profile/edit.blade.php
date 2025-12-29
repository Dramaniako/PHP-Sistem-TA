<x-app-layout title="Profil Pengguna">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengaturan Profil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- HEADER SAMBUTAN (Opsional, seperti sebelumnya) --}}
            <div class="p-6 bg-white shadow sm:rounded-lg border-l-4 border-indigo-500 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Halo, {{ Auth::user()->name }}!</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Kelola informasi profil Anda dan keamanan akun di sini.
                    </p>
                </div>
                <div class="text-4xl">⚙️</div>
            </div>

            {{-- KARTU 1: INFORMASI PROFIL UTAMA & FOTO --}}
            <div class="p-8 bg-white shadow sm:rounded-lg">
                <header class="mb-6 border-b pb-4">
                    <h2 class="text-lg font-medium text-gray-900">Informasi Publik</h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Perbarui foto profil, nama, dan alamat email akun Anda.
                    </p>
                </header>

                {{-- PENTING: enctype="multipart/form-data" wajib ada untuk upload file --}}
                <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @csrf
                    @method('patch')

                    {{-- BAGIAN KIRI: FOTO PROFIL --}}
                    <div class="md:col-span-1 flex flex-col items-center">
                        <div class="relative group">
                            {{-- Gambar Preview --}}
                            <img id="preview-image" 
                                 src="{{ $user->profile_photo_url }}" 
                                 alt="{{ $user->name }}" 
                                 class="w-40 h-40 rounded-full object-cover border-4 border-gray-200 shadow-sm">
                            
                            {{-- Overlay Tombol Ubah --}}
                            <label for="photo" class="absolute inset-0 w-40 h-40 rounded-full bg-black bg-opacity-0 group-hover:bg-opacity-40 flex items-center justify-center transition cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white opacity-0 group-hover:opacity-100 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{-- Input File Tersembunyi --}}
                                <input type="file" name="photo" id="photo" class="hidden" accept="image/*" onchange="previewImage(event)">
                            </label>
                        </div>
                        <p class="text-xs text-gray-500 mt-3">Klik gambar untuk mengubah. Maks 2MB.</p>
                        @error('photo') <span class="text-red-600 text-sm mt-2">{{ $message }}</span> @enderror
                    </div>

                    {{-- BAGIAN KANAN: FORM DATA --}}
                    <div class="md:col-span-2 space-y-6">
                        {{-- Nama --}}
                        <div>
                            <x-input-label for="name" :value="__('Nama Lengkap')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        {{-- Email --}}
                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full bg-gray-50 text-gray-500" :value="old('email', $user->email)" readonly />
                            <p class="text-xs text-gray-500 mt-1">*Email tidak dapat diubah untuk keamanan.</p>
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="mt-2">
                                    <p class="text-sm text-gray-800">
                                        {{ __('Your email address is unverified.') }}
                                        <button form="send-verification" class="underline text-sm text-indigo-600 hover:text-indigo-800 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            {{ __('Click here to re-send the verification email.') }}
                                        </button>
                                    </p>
                                    @if (session('status') === 'verification-link-sent')
                                        <p class="mt-2 font-medium text-sm text-green-600">
                                            {{ __('A new verification link has been sent to your email address.') }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- Role (Read Only) --}}
                        <div>
                             <x-input-label for="role" value="Role Akun" />
                             <x-text-input id="role" type="text" class="mt-1 block w-full bg-gray-50 text-gray-500 uppercase" :value="$user->role" readonly />
                        </div>

                        {{-- Tombol Simpan --}}
                        <div class="flex items-center gap-4 pt-4">
                            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900">
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>

                            @if (session('status') === 'profile-updated')
                                <p
                                    x-data="{ show: true }"
                                    x-show="show"
                                    x-transition
                                    x-init="setTimeout(() => show = false, 2000)"
                                    class="text-sm text-green-600 font-medium flex items-center"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                    {{ __('Berhasil disimpan.') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center gap-4 pt-4 border-t border-gray-100 mt-6">
                        
                        {{-- Tombol Simpan (Primary) --}}
                        <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">
                            {{ __('Simpan Perubahan') }}
                        </x-primary-button>

                        {{-- Tombol Selesai / Kembali (Secondary) --}}
                        <a href="{{ route('profile.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Selesai') }}
                        </a>

                        {{-- Pesan Sukses --}}
                        @if (session('status') === 'profile-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 font-medium ml-auto">
                                ✓ Tersimpan
                            </p>
                        @endif
                    </div>
                </form>
            </div>

            {{-- KARTU 2: UPDATE PASSWORD --}}
            <div class="p-8 bg-white shadow sm:rounded-lg">
                <header class="mb-6 border-b pb-4">
                    <h2 class="text-lg font-medium text-gray-900">Keamanan Password</h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Pastikan akun Anda aman dengan menggunakan password yang kuat.
                    </p>
                </header>
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- KARTU 3: DELETE ACCOUNT --}}
            <div class="p-8 bg-white shadow sm:rounded-lg border-l-4 border-red-500">
                 <header class="mb-6 border-b pb-4">
                    <h2 class="text-lg font-medium text-red-600">Zona Bahaya</h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Menghapus akun bersifat permanen dan tidak dapat dibatalkan.
                    </p>
                </header>
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>

    {{-- Script untuk Preview Gambar sebelum upload --}}
    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('preview-image');
                output.src = reader.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</x-app-layout>