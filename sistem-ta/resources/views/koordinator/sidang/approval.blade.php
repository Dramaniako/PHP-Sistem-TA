<table class="table table-hover">
    <thead>
        <tr>
            <th>Mahasiswa</th>
            <th>Dosen Penguji</th> <th>Alasan & Dokumen</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pengajuans as $p)
        <tr>
            <td>{{ $p->sidangJadwal?->mahasiswa?->name }}</td>
            <td>{{ $p->sidangJadwal?->dosen?->name }}</td> <td>
                <p class="small italic">"{{ $p->alasan }}"</p>
                @if($p->file_pendukung)
                    <a href="{{ asset('storage/' . $p->file_pendukung) }}" class="btn btn-xs btn-info" target="_blank">
                        <i class="fas fa-download"></i> Surat Tugas
                    </a>
                @endif
            </td>
            <td>
                <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#modalAtur{{ $p->id }}">
                    Atur Jadwal Baru
                </button>
            </td>
        </tr>

        <div class="modal fade" id="modalAtur{{ $p->id }}" tabindex="-1">
            <div class="modal-dialog">
                <form action="{{ route('koordinator.proses_approval', $p->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="keputusan" value="terima">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Tetapkan Jadwal Sidang Baru</h5>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label>Tanggal Baru</label>
                                <input type="date" name="tanggal_baru" class="form-control" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Jam Mulai</label>
                                <input type="time" name="jam_baru" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Lokasi</label>
                                <input type="text" name="lokasi_baru" class="form-control" value="{{ $p->sidangJadwal?->lokasi }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan & Update Jadwal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </tbody>
</table>