@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary shadow-sm">
    <div class="card-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <h3 class="card-title mb-2 mb-md-0">{{ $page->title }}</h3>
            <div class="btn-toolbar flex-wrap gap-2" role="toolbar">
                <button onclick="modalAction('{{ url('/biodata/dosen/import') }}')" class="btn btn-sm shadow-sm rounded-pill" style="background-color: #6f42c1; color: white;">
                    <i class="fa fa-upload me-1"></i> Import Excel
                </button>
                <a href="{{ url('/biodata/dosen/export_excel') }}" class="btn btn-sm shadow-sm rounded-pill" style="background-color: #004085; color: white;">
                    <i class="fa fa-file-excel me-1"></i> Export Excel
                </a>
                <a href="{{ url('/biodata/dosen/export_pdf') }}" class="btn btn-sm shadow-sm rounded-pill" style="background-color: #20c997; color: black;">
                    <i class="fa fa-file-pdf me-1"></i> Export PDF
                </a>
                <button onclick="modalAction('{{ url('/biodata/dosen/create_ajax') }}')" class="btn btn-sm shadow-sm rounded-pill" style="background-color: #d63384; color: white;">
                    <i class="fa fa-plus-circle me-1"></i> Tambah
                </button>
            </div>
        </div>

        <!-- Filter Jurusan -->
        <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-sm row text-sm mb-0">
                        <label for="jurusanFilter" class="col-md-2 col-form-label">Filter Jurusan</label>
                        <div class="col-md-4">
                            <select id="jurusanFilter" class="form-control form-control-sm">
                                <option value="">- Semua Jurusan -</option>
                                @foreach ($jurusan as $k)
                                    <option value="{{ $k->jurusan_id }}">{{ $k->jurusan_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Pilih jurusan untuk memfilter data dosen</small>
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
            <table class="table table-bordered table-striped table-hover table-sm" id="table_dosen">
                <thead class="table-primary text-center">
                    <tr>
                        <th>No</th>
                        <th>NIDN</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>Jurusan</th>
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
        var dataDosen = $('#table_dosen').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('biodata.dosen.list') }}", 
                type: "GET",
                data: function (d) {
                    d.search_query = $('#searchInput').val();
                    d.jurusan_id = $('#jurusanFilter').val();
                }
            },
            columns: [
                { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                { data: "nidn", className: "text-nowrap" },
                { data: "nik", className: "text-nowrap" },
                { data: "dosen_nama", className: "text-nowrap" },
                { data: "jenis_kelamin", className: "text-center" },
                { data: "jurusan_id", className: "text-center" },
                { data: "aksi", className: "text-center text-nowrap", orderable: false, searchable: false }
            ]
        });

        $('#searchInput, #jurusanFilter').on('change keyup', function () {
            dataDosen.ajax.reload();
        });
    });
</script>
@endpush
