@extends('layouts.app')
@section('title', 'Edit Profil')

@section('content')

<div class="container">
<div class="card shadow-sm">

    <div class="card-header fw-semibold">
        Edit Profil Mahasiswa
    </div>

    <form method="POST"
          action="{{ route('profile.update') }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card-body">

            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text"
                       name="nama_lengkap"
                       class="form-control"
                       value="{{ old('nama_lengkap', $mahasiswa->nama_lengkap ?? '') }}"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Foto</label>
                <input type="file"
                       name="foto"
                       class="form-control">
            </div>

        </div>

        <div class="card-footer text-end">
            <a href="{{ route('profile.show') }}"
               class="btn btn-secondary">
               Batal
            </a>
            <button class="btn btn-primary">
                Simpan
            </button>
        </div>

    </form>

</div>
</div>

@endsection
