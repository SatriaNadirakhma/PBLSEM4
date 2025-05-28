@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h3 class="card-title mb-2 mb-md-0">{{ $page->title }}</h3>
                <div class="btn-toolbar flex-wrap gap-2" role="toolbar" aria-label="Aksi Jadwal" style="gap: 0.50rem">
                    
                    <button onclick="modalAction('{{ url('/jadwal/import') }}')" class="btn btn-sm shadow-sm rounded-pill"
                        style="background-color: #6f42c1; color: white; font-size: 0.95rem;">
                        <i class="fa fa-upload me-1"></i> Impor Excel
                    </button>

                    <a href="{{ url('/jadwal/export_excel') }}" class="btn btn-sm shadow-sm rounded-pill"
                        style="background-color: #004085; color: white; font-size: 0.95rem;">
                        <i class="fa fa-file-excel me-1"></i> Ekspor Excel
                    </a>

                    <a href="{{ url('/jadwal/export_pdf') }}" class="btn btn-sm shadow-sm rounded-pill"
                        style="background-color: #20c997; color: black; font-size: 0.95rem;">
                        <i class="fa fa-file-pdf me-1"></i> Ekspor PDF
                    </a>

                    <button onclick="modalAction('{{ url('/jadwal/create_ajax') }}')" class="btn btn-sm shadow-sm rounded-pill"
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
                <table class="table table-bordered table-striped table-hover table-sm" id="table_jadwal">
                    <thead class="table-primary text-center">
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th>Tanggal Pelaksanaan</th>
                            <th>Jam Mulai</th>
                            <th>Keterangan</th>
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
            var dataKampus = $('#table_jadwal').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('jadwal/list') }}",
                    type: "POST",
                    data: function (d) {
                        d.search_query = $('#searchInput').val();
                        d._token = '{{ csrf_token() }}';
                    }
                },
                columns: [
                    { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                    { data: "tanggal_pelaksanaan", className: "text-center" },
                    { data: "jam_mulai", className: "text-center" },
                    { data: "keterangan" },
                    { data: "aksi", className: "text-center text-nowrap", orderable: false, searchable: false }
                ]
            });

            $('#searchInput').on('keyup', function () {
                dataKampus.search(this.value).draw();
            });
        });
    </script>
@endpush
