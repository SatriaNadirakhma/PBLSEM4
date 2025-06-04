<form action="{{ url('/biodata/mahasiswa/import_ajax') }}" method="POST" id="form-import-mahasiswa" enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="modal-import-mahasiswa" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Data Mahasiswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label>Download Template</label>
                        <a href="{{ asset('template_mahasiswa.xlsx') }}" class="btn btn-info btn-sm" download>
                            <i class="fa fa-file-excel"></i> Download
                        </a>
                        <small id="error-template" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group">
                        <label>Pilih File Excel</label>
                        <input type="file" name="file_mahasiswa" id="file_mahasiswa" class="form-control"
       accept=".xlsx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required>

                        <small id="error-file_mahasiswa" class="error-text form-text text-danger"></small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    $('#modal-import-mahasiswa').modal('show');

    $("#form-import-mahasiswa").validate({
        rules: {
            file_mahasiswa: {
                required: true,
                extension: "xlsx"
            }
        },
        messages: {
            file_mahasiswa: {
                required: "Silakan pilih file untuk diunggah",
                extension: "Format file harus .xlsx"
            }
        },
        submitHandler: function(form) {
            var formData = new FormData(form);

            $.ajax({
                url: form.action,
                type: form.method,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('.error-text').text(''); // Clear all previous error messages
                    if(response.status) {
                        $('#modal-import-mahasiswa').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });

                        if (typeof tableMahasiswa !== 'undefined') {
                            tableMahasiswa.ajax.reload(); // Reload table jika ada
                        }
                    } else {
                        if (response.msgField) {
                            $.each(response.msgField, function(key, message) {
                                $('#error-' + key).text(message[0]);
                            });
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan Server',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan saat mengunggah file.'
                    });
                }
            });

            return false; // Cegah form submit default
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        }
    });

    $('#modal-import-mahasiswa').on('hidden.bs.modal', function () {
        $(this).remove(); // Hapus modal dari DOM setelah ditutup
    });
});
</script>
