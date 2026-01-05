@extends('layouts.app')

@section('content')

<h2>Detail Dokumen Mahasiswa</h2>

<p><strong>NIM:</strong> {{ $mhs->nim }}</p>
<p><strong>Nama:</strong> {{ $mhs->nama }}</p>
<p><strong>Prodi:</strong> {{ $mhs->prodi }}</p>
<p><strong>Judul TA:</strong> {{ $mhs->judul_ta }}</p>
<p><strong>Status:</strong> {{ $mhs->status }}</p>

<p>
    <a href="{{ asset('storage/' . $mhs->file_dokumen) }}" target="_blank">
        Lihat Dokumen
    </a>
</p>

<form action="/dosen/setujui" method="POST" style="display:inline;">
    @csrf
    <input type="hidden" name="nim" value="{{ $mhs->nim }}">
    <button type="submit">Setujui</button>
</form>

<form action="/dosen/tolak" method="POST" style="display:inline;">
    @csrf
    <input type="hidden" name="nim" value="{{ $mhs->nim }}">
    <button type="submit">Tolak</button>
</form>

@endsection
