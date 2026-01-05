@extends('layouts.app')

@section('title', 'Upload Dokumen')

@section('content')

<h2>Form Upload Dokumen</h2>

<form action="{{ route('mahasiswa.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <p>
        <input type="text" name="nim" placeholder="NIM" required>
    </p>

    <p>
        <input type="text" name="nama" placeholder="Nama" required>
    </p>

    <p>
        <input type="text" name="prodi" placeholder="Prodi" required>
    </p>

    <p>
        <input type="text" name="judul_ta" placeholder="Judul TA" required>
    </p>

    <p>
        <input type="file" name="file_dokumen" required>
    </p>

    <button type="submit">Upload</button>
</form>

@endsection
