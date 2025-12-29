@extends('layouts.app')
@section('title', 'Profil Mahasiswa')

@section('content')

<div class="container">

{{-- ================= PROFIL MAHASISWA ================= --}}
<div class="card mb-4 shadow-sm">
    <div class="card-header d-flex justify-content-between align-items-center fw-semibold">
        Profil Mahasiswa
        <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-warning">
            Edit Profil
        </a>
    </div>

    <div class="card-body">
        <div class="row align-items-center">

            {{-- FOTO --}}
            <div class="col-md-3 text-center mb-3 mb-md-0">
                <img
                    src="{{ $mahasiswa->foto
                        ? asset('storage/'.$mahasiswa->foto)
                        : asset('images/default.png') }}"
                    class="img-thumbnail"
                    style="width:150px;height:200px;object-fit:cover;">
            </div>

            {{-- DATA --}}
            <div class="col-md-9">
                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">NIM</label>
                        <input class="form-control"
                               value="{{ $mahasiswa->nim ?? '-' }}"
                               disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Fakultas</label>
                        <input class="form-control"
                               value="{{ $mahasiswa->prodi->fakultas->nama_fakultas ?? '-' }}"
                               disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap</label>
                        <input class="form-control"
                               value="{{ $mahasiswa->nama_lengkap ?? '-' }}"
                               disabled>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Program Studi</label>
                        <input class="form-control"
                               value="{{ $mahasiswa->prodi->nama_prodi ?? '-' }}"
                               disabled>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

{{-- ================= DOSEN & DOKUMEN ================= --}}
<div class="row mb-4">

    {{-- DOSEN --}}
    <div class="col-md-6 mb-3 mb-md-0">
        <div class="card shadow-sm h-100">
            <div class="card-header fw-semibold">
                Profil Dosen Pembimbing
            </div>
            <div class="card-body">

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input class="form-control"
                           value="{{ $dosen->nama ?? '-' }}"
                           disabled>
                </div>

                <div>
                    <label class="form-label">NIDN</label>
                    <input class="form-control"
                           value="{{ $dosen->nidn ?? '-' }}"
                           disabled>
                </div>

            </div>
        </div>
    </div>

    {{-- DOKUMEN --}}
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-header fw-semibold">
                Dokumen
            </div>
            <div class="card-body">

                @forelse ($dokumen as $file)
                    <div class="d-flex justify-content-between align-items-center
                                border rounded px-3 py-2 mb-2 bg-light">
                        <span>{{ $file->nama }}</span>
                        <a href="{{ asset('storage/'.$file->path) }}"
                           class="btn btn-sm btn-outline-primary">
                            📄
                        </a>
                    </div>
                @empty
                    <p class="text-muted mb-0">
                        Belum ada dokumen
                    </p>
                @endforelse

            </div>
        </div>
    </div>

</div>

{{-- ================= INFORMASI TAMBAHAN ================= --}}
<div class="card shadow-sm">
    <div class="card-header fw-semibold">
        Informasi Tambahan
    </div>
    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Status Pengajuan</label>
                <input class="form-control"
                       value="{{ $statusPengajuan ?? '-' }}"
                       disabled>
            </div>

            <div class="col-md-4">
                <label class="form-label">Status Kelulusan</label>
                <input class="form-control"
                       value="{{ $statusKelulusan ?? '-' }}"
                       disabled>
            </div>

            <div class="col-md-4">
                <label class="form-label">Jadwal Presentasi</label>
                <input class="form-control"
                       value="{{ $jadwal ?? '-' }}"
                       disabled>
            </div>

        </div>

    </div>
</div>

</div>
@endsection
