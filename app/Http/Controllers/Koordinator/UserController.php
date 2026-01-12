<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Fitur Pencarian
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        // Urutkan terbaru & Paginate
        $users = $query->latest()->paginate(10);

        return view('koordinator.users.index', compact('users'));
    }

    public function updateRole(Request $request, $id)
    {
        // 1. Validasi Input
        $request->validate([
            'role' => 'required|in:mahasiswa,dosen,koordinator',
        ]);

        $user = User::findOrFail($id);

        // 2. Proteksi: Jangan biarkan admin mengubah role dirinya sendiri (biar gak terkunci)
        if ($user->id == auth()->id()) {
            return back()->with('error', 'Anda tidak dapat mengubah role akun Anda sendiri!');
        }

        // 3. Update Role
        $user->update([
            'role' => $request->role
        ]);

        return back()->with('success', 'Role pengguna berhasil diperbarui!');
    }
}