@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary shadow-sm">
    <div class="card-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <h3 class="card-title mb-2 mb-md-0">{{ $breadcrumb->title ?? 'Data Mahasiswa' }}</h3>
            <div class="btn-toolbar flex-wrap gap-2" role="toolbar">
                <button onclick="modalAction('{{ route('biodata.mahasiswa.import') }}')" class="btn btn-sm shadow-sm rounded-pill" style="background-color: #6f42c1; color: white;">
                    <i class="fa fa-upload me-1"></i> Import Excel
                </button>
                <a href="{{ route('biodata.mahasiswa.export_excel') }}" class="btn btn-sm shadow-sm rounded-pill" style="background-color: #004085; color: white;">
                    <i class="fa fa-file-excel me-1"></i> Export Excel
                </a>
                <a href="{{ route('biodata.mahasiswa.export_pdf') }}" class="btn btn-sm shadow-sm rounded-pill" style="background-color: #20c997; color: black;">
                    <i class="fa fa-file-pdf me-1"></i> Export PDF
                </a>
                <button onclick="modalAction('{{ route('biodata.mahasiswa.create_ajax') }}')" class="btn btn-sm shadow-sm rounded-pill" style="background-color: #d63384; color: white;">
                    <i class="fa fa-plus-circle me-1"></i> Tambah
                </button>
            </div>
        </div>

        <!-- Filter Prodi -->
        <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-sm row text-sm mb-0">
                        <label for="prodiFilter" class="col-md-2 col-form-label">Filter Prodi</label>
                        <div class="col-md-4">
                            <select id="prodiFilter" class="form-control form-control-sm">
                                <option value="">- Semua Prodi -</option>
                                @foreach ($prodi as $p)
                                    <option value="{{ $p->prodi_id }}">{{ $p->prodi_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Pilih prodi untuk memfilter data mahasiswa</small>
                        </div>
                    </div>
                </div>
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
            <table class="table table-bordered table-striped table-hover table-sm" id="table_mahasiswa">
                <thead class="table-primary text-center">
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Angkatan</th>
                        <th>Jenis Kelamin</th>
                        <th>Status</th>
                        <th>Prodi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function () {
            $('#myModal').modal('show');
        });
    }

    $(document).ready(function () {
        var dataMahasiswa = $('#table_mahasiswa').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('biodata.mahasiswa.list') }}", // gunakan route yang sudah didefinisikan
                type: "GET",
                data: function (d) {
                    d.search_query = $('#searchInput').val();
                    d.prodi_id = $('#prodiFilter').val();
                }
            },
            columns: [
                { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                { data: "nim", className: "text-nowrap" },
                { data: "nik", className: "text-nowrap" },
                { data: "mahasiswa_nama", className: "text-nowrap" },
                { data: "angkatan", className: "text-center" },
                { data: "jenis_kelamin", className: "text-center" },
                { data: "status", className: "text-center" },
                { data: "prodi_id", className: "text-center" },
                { data: "aksi", className: "text-center text-nowrap", orderable: false, searchable: false }
            ]
        });

        $('#searchInput, #prodiFilter').on('change keyup', function () {
            dataMahasiswa.ajax.reload();
        });
    });
</script>
@endpush
