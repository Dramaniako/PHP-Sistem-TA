@extends('layouts.app')

@section('title', 'Mahasiswa - Upload Dokumen')

@section('content')

<h2>Upload Dokumen Tugas Akhir</h2>

@if (session('success'))
    <p style="color: green">{{ session('success') }}</p>
@endif

<p>
    <strong>Status Dokumen:</strong>
    <span style="
        color:
        {{ $status === 'disetujui' ? 'green' :
           ($status === 'ditolak' ? 'red' : 'black') }}">
        {{ strtoupper($status) }}
    </span>
</p>

<a href="/mahasiswa/upload"
   style="
        display: inline-block;
        padding: 10px 20px;
        background: black;
        color: white;
        text-decoration: none;
        border-radius: 6px;
   ">
   Upload / Update Dokumen
</a>

@endsection
