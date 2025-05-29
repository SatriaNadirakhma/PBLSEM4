@extends('layouts.template')

@section('content')
   <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h3 class="card-title mb-2 mb-md-0">{{ $page->title }}</h3>
                <div class="btn-toolbar flex-wrap gap-2" role="toolbar" aria-label="Aksi Riwayat" style="gap: 0.50rem">
                    <!-- Tombol Trigger Modal Ekspor PDF -->
                    <button type="button" class="btn btn-sm shadow-sm rounded-pill"
                        style="background-color: #20c997; color: black; font-size: 0.95rem;"
                        data-toggle="modal" data-target="#modalExportPDF">
                        <i class="fa fa-file-pdf me-1"></i> Ekspor PDF
                    </button>
                </div>
            </div>
        </div>

        <!-- Filter Status -->
        <div class="form-horizontal p-2 border-bottom mb-2">
            <div class="row align-items-center">
                <label for="filterStatus" class="col-md-2 col-form-label text-md-end">Filter Status</label>
                <div class="col-md-4">
                    <select id="filterStatus" class="form-control form-control-sm">
                        <option value="">- Semua Status -</option>
                        <option value="diterima">Diterima</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                    <small class="form-text text-muted">Pilih status pendaftaran</small>
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
                <table class="table table-bordered table-striped table-hover table-sm" id="table_riwayat">
                    <thead class="table-primary text-center">
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th>NIM</th>
                            <th>NIK</th>
                            <th>Nama Lengkap</th>
                            <th>Tanggal Pendaftaran</th>
                            <th>Status</th>
                            <th style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Ekspor PDF -->
    <div class="modal fade" id="modalExportPDF" tabindex="-1" role="dialog" aria-labelledby="modalExportPDFLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ url('/riwayat/export_pdf') }}" method="GET" target="_blank" class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalExportPDFLabel">Ekspor PDF Berdasarkan Filter</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
            <label for="status">Status Pendaftaran</label>
            <select name="status" id="status" class="form-control" required>
                <option value="">- Semua Status -</option>
                <option value="diterima">Diterima</option>
                <option value="ditolak">Ditolak</option>
            </select>
            </div>
            <div class="mb-3">
        <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
        <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" required>
        </div>
        <div class="mb-3">
        <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
        <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" required>
        </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success">Ekspor PDF</button>
        </div>
        </form>
    </div>
    </div>

    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-hidden="true"></div>
@endsection

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function () {
            $('#myModal').modal('show');
        });
    }

    $(document).ready(function () {
        var dataRiwayat = $('#table_riwayat').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('riwayat/list') }}",
                type: "POST",
                data: function (d) {
                    d.status_filter = $('#filterStatus').val();
                    d._token = '{{ csrf_token() }}';
                }
            },
            columns: [
                { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                { data: "nim", className: "text-nowrap" },
                { data: "nik", className: "text-nowrap" },
                { data: "nama", className: "text-nowrap" },
                { data: "tanggal_daftar", className: "text-nowrap text-center" },
                { data: "status", className: "text-center text-capitalize" },
                { data: "aksi", className: "text-center text-nowrap", orderable: false, searchable: false }
            ]
        });

        $('#filterStatus').on('change', function () {
            dataRiwayat.ajax.reload();
        });
    });
</script>
@endpush
