@extends('layouts.template')

@push('css')
    <style>
    /* Pastikan semua dialog dan popover TinyMCE muncul di atas Bootstrap modal */
    .tox.tox-silver-sink {
        z-index: 2000 !important;
        position: absolute !important;
        pointer-events: auto !important;
    }
    .tox .tox-dialog {
        pointer-events: auto !important;
    }
    </style>
@endpush

@section('content')
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h3 class="card-title mb-2 mb-md-0">{{ $page->title }}</h3>
                <div class="btn-toolbar flex-wrap gap-2" role="toolbar" aria-label="Aksi Informasi" style="gap: 0.50rem">
                    
                    <button onclick="modalAction('{{ url('/informasi/import') }}')" class="btn btn-sm shadow-sm rounded-pill"
                        style="background-color: #6f42c1; color: white; font-size: 0.95rem;">
                        <i class="fa fa-upload me-1"></i> Impor Excel
                    </button>

                    <a href="{{ url('/informasi/export_excel') }}" class="btn btn-sm shadow-sm rounded-pill"
                        style="background-color: #004085; color: white; font-size: 0.95rem;">
                        <i class="fa fa-file-excel me-1"></i> Ekspor Excel
                    </a>

                    <a href="{{ url('/informasi/export_pdf') }}" class="btn btn-sm shadow-sm rounded-pill"
                        style="background-color: #20c997; color: black; font-size: 0.95rem;">
                        <i class="fa fa-file-pdf me-1"></i> Ekspor PDF
                    </a>

                    <button onclick="modalAction('{{ url('/informasi/create_ajax') }}')" class="btn btn-sm shadow-sm rounded-pill"
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
                <table class="table table-bordered table-striped table-hover table-sm" id="table_informasi">
                    <thead class="table-primary text-center">
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th>Judul</th>
                            <th>Isi</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Placeholder untuk modal yang dimuat via AJAX --}}
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
    {{-- Pastikan TinyMCE CDN dimuat sekali di sini --}}
    {{-- Ganti YOUR_API_KEY dengan API Key TinyMCE Anda yang sebenarnya --}}
    <script src="https://cdn.tiny.cloud/1/eu6v750ytcjw7d6oof3b013nk02tbwqkumu8zv5s7n9nsu5b/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

    <script>
        // Fungsi untuk memuat konten modal dan menginisialisasi TinyMCE
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                $('#myModal').modal('show');

                // Pastikan TinyMCE didestroy dulu jika sudah ada instance,
                // sebelum diinisialisasi ulang
                if (tinymce.get('isi')) {
                    tinymce.get('isi').destroy();
                }

               tinymce.init({
                    selector: '#isi',
                    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                    height: 300,
                    menubar: false,
                    modal: false,
                    api_key: 'eu6v750ytcjw7d6oof3b013nk02tbwqkumu8zv5s7n9nsu5b',
                    setup: function (editor) {
                        editor.on('init', function () {
                            editor.execCommand('mceRepaint');
                        });
                    }
                });
            });
        }

        $(document).ready(function () {
            var dataInformasi = $('#table_informasi').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('informasi/list') }}",
                    type: "POST",
                    data: function (d) {
                        d.search_query = $('#searchInput').val();
                        d._token = '{{ csrf_token() }}';
                    }
                },
                 columns: [
                    { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                    { data: "judul", className: "text-nowrap" },
                    { 
                        data: "isi", 
                        className: "text-wrap",
                        render: function(data, type, row) {
                            return data; // Mengembalikan data mentah (HTML)
                        }
                    },
                    { data: "aksi", className: "text-center text-nowrap", orderable: false, searchable: false }
                ]
            });

            $('#searchInput').on('keyup', function () {
                dataInformasi.ajax.reload();
            });

            // Event listener saat modal ditutup, untuk menghancurkan instance TinyMCE
            // Ini penting karena konten modal dimuat secara dinamis
            $('#myModal').on('hidden.bs.modal', function () {
                // Hapus instance TinyMCE dari elemen '#isi'
                if (tinymce.get('isi')) {
                    tinymce.get('isi').destroy();
                }
                // Opsional: kosongkan konten modal untuk mencegah duplikasi ID jika modal dibuka lagi
                $(this).html('');
            });
        });
        $.fn.modal.Constructor.prototype._enforceFocus = function() {};
    </script>
@endpush