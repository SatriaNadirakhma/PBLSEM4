@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary shadow-sm">
    <div class="card-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <h3 class="card-title mb-2 mb-md-0">{{ $page->title }}</h3>
            <div class="btn-toolbar flex-wrap gap-2" role="toolbar" aria-label="Aksi User">
                <button onclick="modalAction('{{ url('/user/import') }}')"   
                        class="btn btn-sm shadow-sm rounded-pill" 
                        style="background-color: #6f42c1; color: white; font-size: 0.95rem;">
                        <i class="fa fa-upload me-1"></i> Import Excel
                </button>
                <a href="{{ url('/user/export_excel') }}"    
                        class="btn btn-sm shadow-sm rounded-pill" 
                        style="background-color: #004085; color: white; font-size: 0.95rem;">
                        <i class="fa fa-file-excel me-1"></i> Export Excel
                </a>
                <a href="{{ url('/user/export_pdf') }}" 
                        class="btn btn-sm shadow-sm rounded-pill" 
                        style="background-color: #20c997; color: black; font-size: 0.95rem;">
                        <i class="fa fa-file-pdf me-1"></i> Export PDF
                </a>
                <button onclick="modalAction('{{ url('/user/create_ajax') }}')" 
                        class="btn btn-sm shadow-sm rounded-pill" 
                        style="background-color: #d63384; color: white; font-size: 0.95rem;">
                        <i class="fa fa-plus-circle me-1"></i> Tambah
                </button>
            </div>
        </div>

        <!-- Filter -->
        <div id="filter" class="form-horizontal p-2 border-bottom mb-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-sm row text-sm mb-0">
                        <label for="filter_role" class="col-md-2 col-form-label">Filter Role</label>
                        <div class="col-md-4">
                            <select name="filter_role" id="filter_role" class="form-control form-control-sm">
                                <option value="">- Semua Role -</option>
                                <option value="admin">Admin</option>
                                <option value="mahasiswa">Mahasiswa</option>
                                <option value="dosen">Dosen</option>
                                <option value="tendik">Tendik</option>
                            </select>
                            <small class="form-text text-muted">Pilih Role untuk memfilter User</small>
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
            <table class="table table-bordered table-striped table-hover table-sm" id="table_user">
                <thead class="table-primary text-center">
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Profile</th>
                        <th style="width: 150px;">Aksi</th>
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
    var dataUser = $('#table_user').DataTable({
    processing: true,
    serverSide: true,
    searchDelay: 500, // <-- ini delay 500ms setelah user ngetik
    ajax: {
        url: "{{ url('user/list') }}",
        type: "POST",
        data: function (d) {
            d.filter_role = $('#filter_role').val();
            d._token = '{{ csrf_token() }}';
        }
    },
    columns: [
        { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
        { data: "username", className: "text-nowrap" },
        { data: "nama_lengkap", className: "text-nowrap" },
        { data: "email", className: "text-nowrap" },
        { data: "role", className: "text-center text-capitalize" },
        { data: "profile", className: "text-center" },
        { data: "aksi", className: "text-center text-nowrap", orderable: false, searchable: false }
    ]
});

    $('#filter_role').on('change', function () {
        dataUser.ajax.reload();
    });
});
</script>
@endpush
