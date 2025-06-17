@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary shadow-sm">
    <div class="card-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <h3 class="card-title mb-2 mb-md-0">{{ $page->title }}</h3>
            <div class="btn-toolbar flex-wrap gap-2" role="toolbar" aria-label="Aksi Hasil Ujian" style="gap: 0.50rem;">
                
            <button onclick="modalAction('{{ url('/hasil_ujian/import') }}')" class="btn btn-sm shadow-sm rounded-pill"
                        style="background-color: #6f42c1; color: white; font-size: 0.95rem;">
                        <i class="fa fa-upload me-1"></i> Impor Excel
                    </button>

                <a href="{{ url('/hasil_ujian/export_excel') }}" class="btn btn-sm shadow-sm rounded-pill"
                    style="background-color: #004085; color: white; font-size: 0.95rem;">
                    <i class="fa fa-file-excel me-1"></i> Ekspor Excel
                </a>

                <a href="{{ url('/hasil_ujian/export_pdf') }}" class="btn btn-sm shadow-sm rounded-pill"
                    style="background-color: #20c997; color: black; font-size: 0.95rem;">
                    <i class="fa fa-file-pdf me-1"></i> Ekspor PDF
                </a>
                
                {{-- Ubah tombol ini agar memicu modalAction --}}
                <button type="button" onclick="modalAction('{{ url('/hasil_ujian/create_ajax') }}')"
                    class="btn btn-sm shadow-sm rounded-pill"
                    style="background-color: #d63384; color: white; font-size: 0.95rem;">
                    <i class="fa fa-plus-circle me-1"></i> Tambah Data
                </button>
            </div>
        </div>
    </div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover table-sm" id="table_hasil_ujian">
                <thead class="table-primary text-center">
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>Nama Peserta</th>
                        <th>Jadwal</th>
                        <th>Nilai Listening</th>
                        <th>Nilai Reading</th>
                        <th>Total Nilai</th>
                        <th>Status</th>
                        <th>Role</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

{{-- Pastikan elemen modalnya ada di halaman utama ini --}}
<div id="modal-master" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true"></div>
@endsection

@push('js')
<script>
    // Fungsi untuk memuat konten modal
   function modalAction(url = '', init = true) {
        $('#modal-master').load(url, function () {
            $('#modal-master').modal('show');
            if (init) {
                initModalFormScripts();
            }
        });
    }

    // Fungsi yang berisi semua script untuk form modal
    function initModalFormScripts() {
        // Inisialisasi Select2 untuk elemen di dalam modal
        $('#modal-master').find('#user_id').select2({
            dropdownParent: $('#modal-master'),
            placeholder: "-- Pilih nama peserta --",
            allowClear: true
        });

        // Role filter logic dengan AJAX untuk fetch data
        $('#modal-master').find('#role').off('change').on('change', function() { // Gunakan .off('change') untuk mencegah multiple binding
            const roleSelected = $(this).val();
            const userSelect = $('#modal-master').find('#user_id'); // Pastikan scope ke dalam modal
            
            if (roleSelected) {
                userSelect.prop('disabled', true)
                          .html('<option value="">Memuat data...</option>')
                          .trigger('change');
                
                $.ajax({
                    url: "{{ url('/get-users-by-role') }}",
                    type: 'GET',
                    data: { role: roleSelected },
                    dataType: 'json',
                    success: function(response) {
                        userSelect.empty().append('<option value="">-- Pilih Peserta --</option>');
                        if (response.status && response.data.length > 0) {
                            $.each(response.data, function(index, user) {
                                let optionText = '';
                                if (roleSelected === 'mahasiswa' && user.mahasiswa) {
                                    optionText = `${user.mahasiswa.mahasiswa_nama} (${user.mahasiswa.nim ?? 'N/A'} - Mahasiswa)`;
                                } else if (roleSelected === 'dosen' && user.dosen) {
                                    optionText = `${user.dosen.dosen_nama} (${user.dosen.nidn ?? 'N/A'} - Dosen)`;
                                } else if (roleSelected === 'tendik' && user.tendik) {
                                    optionText = `${user.tendik.tendik_nama} (${user.tendik.nip ?? 'N/A'} - Tendik)`;
                                }
                                if (optionText) {
                                    userSelect.append(`<option value="${user.user_id}">${optionText}</option>`);
                                }
                            });
                            userSelect.prop('disabled', false);
                        } else {
                            userSelect.append('<option value="">Tidak ada data tersedia</option>');
                        }
                        userSelect.trigger('change');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching users:', error);
                        userSelect.empty()
                                 .append('<option value="">Error memuat data</option>')
                                 .trigger('change');
                        Swal.fire({
                            title: 'Error!',
                            text: 'Gagal memuat data peserta. Silakan coba lagi.',
                            icon: 'error',
                            timer: 3000
                        });
                    }
                });
            } else {
                userSelect.prop('disabled', true)
                          .empty()
                          .append('<option value="">-- Pilih Role Terlebih Dahulu --</option>')
                          .trigger('change');
            }
        });

        // Hitung total & status kelulusan
        function calculateTotal() {
            const listening = parseInt($('#modal-master').find('#nilai_listening').val()) || 0;
            const reading = parseInt($('#modal-master').find('#nilai_reading').val()) || 0;
            let total = listening + reading;
            const maxTotal = parseInt($('#modal-master').find('#nilai_total').data('max')) || 990;
            
            if (total > maxTotal) {
                total = maxTotal;
                $('#modal-master').find('#nilai_total').addClass('border border-warning');
            } else {
                $('#modal-master').find('#nilai_total').removeClass('border border-warning');
            }
            $('#modal-master').find('#nilai_total').val(total);
            
            if (total >= 500) {
                $('#modal-master').find('#status_preview').val('Lulus').removeClass('text-danger').addClass('text-success');
            } else if (total > 0) {
                $('#modal-master').find('#status_preview').val('Tidak Lulus').removeClass('text-success').addClass('text-danger');
            } else {
                $('#modal-master').find('#status_preview').val('').removeClass('text-success text-danger');
            }
        }
        $('#modal-master').find('#nilai_listening, #nilai_reading').off('input change').on('input change', calculateTotal);

        // Validasi & AJAX submit
        $('#modal-master').find("#form-tambah-hasil_ujian").validate({
            rules: {
                user_id: { required: true },
                jadwal_id: { required: true },
                nilai_listening: { required: true, number: true, min: 0, max: 495 },
                nilai_reading: { required: true, number: true, min: 0, max: 495 },
                catatan: { maxlength: 255 }
            },
            messages: {
                user_id: "Pilih peserta terlebih dahulu",
                jadwal_id: "Pilih jadwal ujian terlebih dahulu",
                nilai_listening: {
                    required: "Nilai listening wajib diisi",
                    number: "Masukkan angka yang valid",
                    min: "Minimal 0",
                    max: "Maksimal 495"
                },
                nilai_reading: {
                    required: "Nilai reading wajib diisi",
                    number: "Masukkan angka yang valid",
                    min: "Minimal 0",
                    max: "Maksimal 495"
                },
                catatan: { maxlength: "Maksimal 255 karakter" }
            },
            errorElement: 'small',
            errorClass: 'text-danger',
            errorPlacement: function(error, element) {
                error.insertAfter(element);
            },
            submitHandler: function(form) {
                $(form).find('button[type="submit"]').prop('disabled', true)
                    .html('<i class="fa fa-spinner fa-spin me-1"></i> Menyimpan...');
                    
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            $('#modal-master').modal('hide');
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            if (typeof dataHasilUjian !== 'undefined') {
                                dataHasilUjian.ajax.reload(null, false);
                            } else {
                                setTimeout(() => location.reload(), 1500);
                            }
                        } else {
                            // Cek apakah response.msgField ada dan bukan null
                            if (response.msgField) {
                                $('.error-text').text(''); // Bersihkan error sebelumnya
                                $.each(response.msgField, function(key, val) {
                                    // Pastikan element dengan id 'error-' + key ada
                                    $('#modal-master').find('#error-' + key).text(Array.isArray(val) ? val[0] : val);
                                });
                            }
                            Swal.fire({
                                title: 'Gagal!',
                                text: response.message || 'Gagal menyimpan data',
                                icon: 'error'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Ajax Error:', xhr.responseText);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Terjadi kesalahan saat mengirim data. Silakan coba lagi.',
                            icon: 'error'
                        });
                    },
                    complete: function() {
                        $(form).find('button[type="submit"]').prop('disabled', false)
                            .html('<i class="fa fa-save me-1"></i> Simpan Data');
                    }
                });
                return false;
            }
        });

        // Reset form saat modal ditutup
        $('#modal-master').off('hidden.bs.modal').on('hidden.bs.modal', function() { // Gunakan .off() untuk mencegah multiple binding
            const form = $('#modal-master').find('#form-tambah-hasil_ujian');
            form[0].reset();
            $('#modal-master').find('.error-text').text('');
            $('#modal-master').find('#role').val('').trigger('change'); // Reset role dan trigger change untuk reset peserta
            $('#modal-master').find('#user_id').empty()
                        .append('<option value="">-- Pilih Role Terlebih Dahulu --</option>')
                        .prop('disabled', true)
                        .trigger('change');
            $('#modal-master').find('#nilai_total, #status_preview').val('').removeClass('text-success text-danger border border-warning');
        });
    }


    $(document).ready(function () {
        // Setup CSRF token untuk semua AJAX request
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        // Inisialisasi DataTable
        var dataHasilUjian = $('#table_hasil_ujian').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('hasil_ujian/list') }}",
                type: "POST",
                data: function (d) {
                    d.search_query = $('#searchInput').val();
                },
                error: function(xhr, error, thrown) {
                    console.log('Ajax Error Details:');
                    console.log('Status:', xhr.status);
                    console.log('Response:', xhr.responseText);
                    console.log('Error:', error);
                    alert('Error loading data. Check console for details.');
                }
            },
            columns: [
                { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                { data: "nama", className: "text-nowrap" },
                { data: "tanggal_pelaksanaan", className: "text-nowrap" },
                { data: "nilai_listening", className: "text-center" },
                { data: "nilai_reading", className: "text-center" },
                { data: "nilai_total", className: "text-center fw-bold" },
                { data: "status_lulus", className: "text-center text-nowrap" },
                { data: "role", className: "text-center text-nowrap" },
                { data: "aksi", className: "text-center text-nowrap", orderable: false, searchable: false }
            ],
            language: {
                emptyTable: "Tidak ada data",
                zeroRecords: "Data tidak cocok dengan pencarian",
                processing: "Memuat data..."
            }
        });

        // Debounce search
        let typingTimer;
        $('#searchInput').on('keyup', function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                dataHasilUjian.ajax.reload();
            }, 500);
        });

        // PENTING: Inisialisasi script form modal hanya saat modal dibuka/dimuat.
        // Fungsi initModalFormScripts() akan dipanggil oleh modalAction() setelah konten dimuat.
    });
</script>
@endpush