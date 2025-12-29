<?php

namespace App\Http\Controllers;

use App\Models\User;  // <-- TAMBAHKAN INI
use Illuminate\Support\Facades\Storage; // <-- PENTING
use Illuminate\Validation\Rule;
// ... imports lainnya

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request): View
{
    // Ambil user beserta data proposal dan dosen pembimbingnya
    $user = $request->user()->load(['proposal.dosenPembimbing']);

    return view('profile.index', [
        'user' => $user,
        'proposal' => $user->proposal, // Kirim variabel proposal ke view
    ]);
}

    /**
     * Menampilkan Form Edit Profil
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    // app/Http/Controllers/ProfileController.php

public function update(Request $request): RedirectResponse
{
    $user = $request->user();

    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        'photo' => ['nullable', 'image', 'max:2048'], // Validasi: harus gambar, maks 2MB
    ]);

    // Cek jika ada file 'photo' yang diupload
    if ($request->hasFile('photo')) {
        // Hapus foto lama jika ada (agar storage tidak penuh sampah)
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // Simpan foto baru ke folder 'profile-photos' di disk public
        $path = $request->file('photo')->store('profile-photos', 'public');
        
        // Simpan pathnya ke database
        $user->profile_photo_path = $path;
    }

    $user->fill($request->only(['name', 'email']));

    if ($user->isDirty('email')) {
        $user->email_verified_at = null;
    }

    $user->save();

    return Redirect::route('profile.edit')->with('status', 'profile-updated');
}

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
