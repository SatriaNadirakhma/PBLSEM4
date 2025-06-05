@extends('layouts.template')

@section('content')
<div class="container mt-4" style="max-width: 700px;">

    <!-- Alert Peringatan -->
    <div class="alert alert-warning py-2" role="alert" style="font-size: 14px;">
        <strong>Perhatian!</strong> Jika Anda ingin mengubah data biodata, silakan lakukan perubahan di 
        <a href="{{ route('datadiri.index') }}" class="alert-link">Data Diri</a> terlebih dahulu sebelum mendaftar.
    </div>

    <!-- Alert Dinamis -->
    <div id="alert-container"></div>

    <form id="form-pendaftaran" action="{{ route('pendaftaran.store_ajax') }}" method="POST" enctype="multipart/form-data" style="font-size: 14px;">
        @csrf

        <!-- Informasi Mahasiswa -->
        <div class="card mb-3">
            <div class="card-header py-2" style="font-weight: 600; font-size: 15px;">Data Mahasiswa</div>
            <div class="card-body py-3">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label mb-1">Nama</label>
                        <input type="text" class="form-control form-control-sm" value="{{ $mahasiswa->mahasiswa_nama }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-1">NIM</label>
                        <input type="text" class="form-control form-control-sm" value="{{ $mahasiswa->nim }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-1">NIK</label>
                        <input type="text" class="form-control form-control-sm" value="{{ $mahasiswa->nik }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-1">No. Telp</label>
                        <input type="text" class="form-control form-control-sm" value="{{ $mahasiswa->no_telp }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-1">Alamat Asal</label>
                        <textarea class="form-control form-control-sm" rows="2" readonly>{{ $mahasiswa->alamat_asal }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-1">Alamat Sekarang</label>
                        <textarea class="form-control form-control-sm" rows="2" readonly>{{ $mahasiswa->alamat_sekarang }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-1">Program Studi</label>
                        <input type="text" class="form-control form-control-sm" value="{{ $mahasiswa->prodi->prodi_nama }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-1">Jurusan</label>
                        <input type="text" class="form-control form-control-sm" value="{{ $mahasiswa->prodi->jurusan->jurusan_nama }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label mb-1">Kampus</label>
                        <input type="text" class="form-control form-control-sm" value="{{ $mahasiswa->prodi->jurusan->kampus->kampus_nama }}" readonly>
                    </div>
                </div>
                <input type="hidden" name="mahasiswa_id" value="{{ $mahasiswa->mahasiswa_id }}">
            </div>
        </div>

        <!-- Upload Berkas -->
        <div class="card mb-3">
            <div class="card-header py-2" style="font-weight: 600; font-size: 15px;">Upload Berkas</div>
            <div class="card-body py-3">
                <div class="form-group mb-2">
                    <label class="mb-1">Scan KTP <span class="text-danger">*</span></label>
                    <input type="file" class="form-control form-control-sm" name="scan_ktp" accept="image/*,application/pdf" required>
                    <small class="form-text text-muted">Format yang diizinkan: JPG, JPEG, dan PNG</small>
                </div>
                <div class="form-group mb-2">
                    <label class="mb-1">Scan KTM <span class="text-danger">*</span></label>
                    <input type="file" class="form-control form-control-sm" name="scan_ktm" accept="image/*,application/pdf" required>
                    <small class="form-text text-muted">Format yang diizinkan: JPG, JPEG, dan PNG</small>
                </div>
                <div class="form-group mb-2">
                    <label class="mb-1">Pas Foto <span class="text-danger">*</span></label>
                    <input type="file" class="form-control form-control-sm" name="pas_foto" accept="image/*" required>
                    <small class="form-text text-muted">Format yang diizinkan: JPG, JPEG, dan PNG</small>
                </div>
            </div>
        </div>

        <!-- Pilihan Jadwal -->
        <div class="card mb-3">
            <div class="card-header py-2" style="font-weight: 600; font-size: 15px;">Pilih Jadwal</div>
            <div class="card-body py-3">
                <div class="form-group mb-2">
                    <label class="mb-1">Jadwal <span class="text-danger">*</span></label>
                    <select name="jadwal_id" class="form-control form-control-sm" required>
                        <option value="">-- Pilih Jadwal --</option>
                        @foreach($jadwalList as $jadwal)
                         @if(\Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->gte(\Carbon\Carbon::today()))
                            <option value="{{ $jadwal->jadwal_id }}">
                                {{ \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i:s') }}
                            </option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <button id="btn-submit" type="submit" class="btn btn-primary btn-sm">Kirim</button>
    </form>
</div>
@endsection 

@push('js')
<script>
$(document).ready(function () {
    $('#form-pendaftaran').on('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            icon: 'question',
            title: 'Konfirmasi Pendaftaran',
            text: 'Setelah dikirim, data tidak dapat diubah lagi. Pastikan semua data dan dokumen sudah benar.',
            confirmButtonText: 'Ya, Kirim Sekarang',
            showCancelButton: true,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const btn = $('#btn-submit');
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...');

                const formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Pendaftaran Berhasil!',
                                text: res.message,
                                confirmButtonText: 'Mengerti'
                            }).then(() => {
                                if (res.redirect_url) {
                                    window.location.href = res.redirect_url;
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Mengirim',
                                text: res.message
                            });
                        }
                        btn.prop('disabled', false).text('Kirim');
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan saat mengirim data.';
                        
                        if (xhr.responseJSON) {
                            if (xhr.responseJSON.errors) {
                                errorMessage = Object.values(xhr.responseJSON.errors).join('\n');
                            } else if (xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Mengirim',
                            text: 'Maaf, Anda sudah mendaftar. Silahkan tunggu hasil verifikasi oleh admin.'
                        });

                        btn.prop('disabled', false).text('Kirim');
                    }
                });
            }
        });
    });
});
</script>
@endpush

