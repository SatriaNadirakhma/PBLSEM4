<form action="{{ url('/hasil_ujian/ajax') }}" method="POST" id="form-tambah-hasil_ujian">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Hasil Ujian</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Pilih Role <span class="text-danger">*</span></label>
                            <select id="role" class="form-control" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="mahasiswa">Mahasiswa</option>
                                <option value="dosen">Dosen</option>
                                <option value="tendik">Tendik</option>
                            </select>
                            <small id="error-role" class="error-text text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Peserta <span class="text-danger">*</span></label>
                            <select name="user_id" id="user_id" class="form-control select2" required >
                                <option value="">-- Pilih Role Terlebih Dahulu --</option>
                                @foreach($user as $u)
                                    @if($u->role == 'mahasiswa' && $u->mahasiswa)
                                        <option value="{{ $u->user_id }}" data-role="mahasiswa" style="display:none;">
                                            {{ $u->mahasiswa->mahasiswa_nama }} ({{ $u->mahasiswa->nim ?? 'N/A' }} - Mahasiswa)
                                        </option>
                                    @elseif($u->role == 'dosen' && $u->dosen)
                                        <option value="{{ $u->user_id }}" data-role="dosen" style="display:none;">
                                            {{ $u->dosen->dosen_nama }} ({{ $u->dosen->nidn ?? 'N/A' }} - Dosen)
                                        </option>
                                    @elseif($u->role == 'tendik' && $u->tendik)
                                         <option value="{{ $u->user_id }}" data-role="tendik" style="display:none;">
                                            {{ $u->tendik->tendik_nama }} ({{ $u->tendik->nip ?? 'N/A' }} - Tendik)
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <small id="error-user_id" class="error-text text-danger"></small>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Jadwal Ujian <span class="text-danger">*</span></label>
                    <select name="jadwal_id" id="jadwal_id" class="form-control" required>
                        <option value="">-- Pilih Jadwal --</option>
                        @foreach($jadwal as $j)
                            <option value="{{ $j->jadwal_id }}"> {{-- Menggunakan jadwal_id sebagai value --}}
                                {{ \Carbon\Carbon::parse($j->tanggal_pelaksanaan)->format('d/m/Y') }} - {{-- Menggunakan tanggal_pelaksanaan --}}
                                {{ $j->jam_mulai ?? '' }} {{-- Menggunakan jam_mulai --}}
                                @if($j->keterangan)
                                    ({{ $j->keterangan }}) {{-- Menggunakan keterangan sebagai pengganti ruangan --}}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <small id="error-jadwal_id" class="error-text text-danger"></small>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nilai Listening <span class="text-danger">*</span></label>
                            <input type="number" name="nilai_listening" id="nilai_listening" 
                                   class="form-control" min="0" max="495" required
                                   placeholder="Masukkan nilai listening (0-495)">
                            <small class="form-text text-muted">Rentang nilai: 0 - 495</small>
                            <small id="error-nilai_listening" class="error-text text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nilai Reading <span class="text-danger">*</span></label>
                            <input type="number" name="nilai_reading" id="nilai_reading" 
                                   class="form-control" min="0" max="495" required
                                   placeholder="Masukkan nilai reading (0-495)">
                            <small class="form-text text-muted">Rentang nilai: 0 - 495</small>
                            <small id="error-nilai_reading" class="error-text text-danger"></small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Total Nilai</label>
                            <input type="number" id="nilai_total" class="form-control" readonly 
                                placeholder="Otomatis terhitung" data-max="990">
                            <small class="form-text text-muted">Total otomatis = Listening + Reading</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Status Kelulusan</label>
                            <input type="text" id="status_preview" class="form-control" readonly 
                                placeholder="Otomatis berdasarkan total nilai">
                            <small class="form-text text-muted">Lulus jika total ≥ 500</small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Catatan</label>
                    <textarea name="catatan" id="catatan" class="form-control" rows="3" 
                              placeholder="Tambahkan catatan atau komentar (opsional)"></textarea>
                    <small id="error-catatan" class="error-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-secondary">
                    <i class="fa fa-times me-1"></i> Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-1"></i> Simpan Data
                </button>
            </div>
        </div>
    </div>
</form>

