@extends('layouts.app')
@section('title','Dashboard')

@section('content')

{{-- Informasi Tanggal & Data --}}
<div class="card">
    <h3 style="margin-bottom:8px;">📅 Informasi Tanggal</h3>
    <p>Tanggal hari ini: <strong>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</strong></p>
</div>

<div style="display:flex; gap:20px; margin-top:20px;">
    <div class="card" style="flex:1;">
        <h3>Total Dokumen</h3>
        <strong style="font-size:24px;">34</strong>
        <p style="font-size:12px;">Termasuk dokumen masuk dan pending</p>
    </div>

    <div class="card" style="flex:1;">
        <h3>Dokumen Diproses</h3>
        <strong style="font-size:24px;">12</strong>
        <p style="font-size:12px;">Butuh konfirmasi</p>
    </div>

    <div class="card" style="flex:1;">
        <h3>Selesai</h3>
        <strong style="font-size:24px;">22</strong>
        <p style="font-size:12px; color:#1abc9c;">Sudah final</p>
    </div>
</div>

@endsection
