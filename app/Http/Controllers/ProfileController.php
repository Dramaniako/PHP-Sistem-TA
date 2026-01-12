<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Proposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * HALAMAN PROFIL (ROLE BASED)
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // ===============================
        // MAHASISWA
        // ===============================
        if ($user->role === 'mahasiswa') {

            $user->load(['proposal.dosenPembimbing']);

            return view('profile.role.mahasiswa', [
                'user' => $user,
                'proposal' => $user->proposal,
            ]);
        }

        // ===============================
        // DOSEN
        // ===============================
        if ($user->role === 'dosen') {

            $proposalBimbingan = Proposal::with([
                    'mahasiswaUser.mahasiswa'
                ])
                ->where('dosen_pembimbing_id', $user->id)
                ->get();

            return view('profile.role.dosen', [
                'user' => $user,
                'proposalBimbingan' => $proposalBimbingan,
            ]);
        }

        // ===============================
        // KOORDINATOR
        // ===============================
        if ($user->role === 'koordinator') {

            // STATISTIK
            $totalProposal = \App\Models\Proposal::count();

            $pendingProposal = \App\Models\Proposal::where('status', 'pending')->count();

            $approvedProposal = \App\Models\Proposal::where('status', 'disetujui')->count();

            $totalPengajuan = \App\Models\PengajuanPerubahan::count();

            // AKTIVITAS TERBARU
            $recentProposals = \App\Models\Proposal::with('mahasiswaUser')
                ->latest()
                ->take(5)
                ->get();

            $recentPengajuan = \App\Models\PengajuanPerubahan::with('mahasiswaUser')
                ->latest()
                ->take(5)
                ->get();

            return view('profile.role.koordinator', [
                'user' => $user,
                'totalProposal'     => $totalProposal,
                'pendingProposal'   => $pendingProposal,
                'approvedProposal'  => $approvedProposal,
                'totalPengajuan'    => $totalPengajuan,
                'recentProposals'   => $recentProposals,
                'recentPengajuan'   => $recentPengajuan,
            ]);
        }


        // ===============================
        // FALLBACK (ANTI ERROR)
        // ===============================
        abort(403, 'Role tidak dikenali');
    }

    /**
     * FORM EDIT PROFIL (UMUM SEMUA ROLE)
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * UPDATE PROFIL
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {

            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        $user->fill($request->only(['name', 'email']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated');
    }

    /**
     * HAPUS AKUN
     */
    public function destroy(Request $request)
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
