@extends('layouts.app')

@section('content')

<h2>Validasi Dokumen Mahasiswa</h2>

@if (session('success'))
    <p style="color: green">{{ session('success') }}</p>
@endif

@foreach ($mahasiswa as $mhs)
    <div style="margin-bottom: 15px;">
        <strong>{{ $mhs->nama }}</strong> ({{ $mhs->nim }}) <br>
        Status: <strong>{{ $mhs->status }}</strong><br>

        <a href="/dosen/validasi/detail?nim={{ $mhs->nim }}">
            Detail
        </a>
    </div>
@endforeach

@endsection
