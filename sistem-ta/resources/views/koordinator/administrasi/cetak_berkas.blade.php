<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Berkas - {{ $mhs->name }}</title>
    <style>
        body { font-family: 'Times New Roman', serif; padding: 40px; line-height: 1.6; }
        .header { text-align: center; border-bottom: 3px double #000; pb: 10px; margin-bottom: 20px; }
        .content { margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; padding: 8px; text-align: left; }
        .footer { mt: 50px; text-align: right; margin-top: 60px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="background: #fff3cd; padding: 10px; margin-bottom: 20px; border: 1px solid #ffeeba;">
        <button onclick="window.print()">Klik Tombol Print Ini</button> | 
        <a href="{{ route('koordinator.monitoring.index') }}">Kembali ke Dashboard</a>
    </div>

    <div class="header">
        <h2>SURAT KETERANGAN KELENGKAPAN ADMINISTRASI TA</h2>
        <p>Program Studi Teknik Informatika - Universitas XYZ</p>
    </div>

    <div class="content">
        <p>Berdasarkan data sistem, mahasiswa di bawah ini:</p>
        <p>Nama : <strong>{{ $mhs->name }}</strong></p>
        <p>NIM  : {{ $mhs->nim ?? '....................' }}</p>

        <p>Telah dinyatakan <strong>LENGKAP</strong> dalam mengunggah dokumen administrasi Tugas Akhir dengan rincian sebagai berikut:</p>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Dokumen</th>
                    <th>Status Validasi Dosen</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mhs->dokumens as $no => $doc)
                <tr>
                    <td>{{ $no + 1 }}</td>
                    <td>{{ $doc->jenis_dokumen }}</td>
                    <td>{{ $doc->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Jimbaran, {{ date('d F Y') }}</p>
        <br><br><br>
        <p>( Koordinator Program Studi )</p>
    </div>
</body>
</html>