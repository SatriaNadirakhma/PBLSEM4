@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
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
                            <th>Nama Lengkap</th>
                            <th>Tanggal Pendaftaran</th>
                            <th>No. Telepon</th>
                            <th>Status</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"></div>
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
                url: "{{ url('kirimemail/list') }}",
                type: "POST",
                data: function (d) {
                    d.status_filter = $('#filterStatus').val();
                    d._token = '{{ csrf_token() }}';
                }
            },
            columns: [
                { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                { data: "nim", className: "text-nowrap" },
                { data: "nama", className: "text-nowrap" },
                { data: "tanggal_daftar", className: "text-nowrap text-center" },
                { data: "no_telp", className: "text-nowrap text-center" },
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
