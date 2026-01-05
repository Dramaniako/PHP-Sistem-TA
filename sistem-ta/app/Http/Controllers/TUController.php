<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TUController extends Controller
{
    public function dashboard()
    {
        return view('tu.dashboard');
    }

    public function mahasiswa()
    {
        $mahasiswa = DB::table('mahasiswa')->get();
        return view('tu.mahasiswa', compact('mahasiswa'));
    }

    public function detail(Request $request)
    {
        $mhs = DB::table('mahasiswa')
                ->where('nim', $request->nim)
                ->first();

        return view('tu.detail', compact('mhs'));
    }
}
