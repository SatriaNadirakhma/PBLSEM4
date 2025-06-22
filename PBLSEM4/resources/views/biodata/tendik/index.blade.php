@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h3 class="card-title mb-2 mb-md-0">{{ $page->title }}</h3>
                <div class="btn-toolbar flex-wrap gap-2" role="toolbar" aria-label="Aksi Tendik">
                    
                    <button 
                        onclick="modalAction('{{ url('/biodata/tendik/import') }}')" 
                        class="btn btn-sm shadow-sm rounded-pill" 
                        style="background-color: #6f42c1; color: white; font-size: 0.95rem;">
                        <i class="fa fa-upload me-1"></i> Import Excel
                    </button>

                    <a 
                        href="{{ url('/biodata/tendik/export_excel') }}" 
                        class="btn btn-sm shadow-sm rounded-pill" 
                        style="background-color: #004085; color: white; font-size: 0.95rem;">
                        <i class="fa fa-file-excel me-1"></i> Export Excel
                    </a>

                    <a 
                        href="{{ url('/biodata/tendik/export_pdf') }}" 
                        class="btn btn-sm shadow-sm rounded-pill" 
                        style="background-color: #20c997; color: black; font-size: 0.95rem;">
                        <i class="fa fa-file-pdf me-1"></i> Export PDF
                    </a>

                    <button 
                        onclick="modalAction('{{ url('/biodata/tendik/create_ajax') }}')" 
                        class="btn btn-sm shadow-sm rounded-pill" 
                        style="background-color: #d63384; color: white; font-size: 0.95rem;">
                        <i class="fa fa-plus-circle me-1"></i> Tambah
                    </button>
                </div>
            </div>

           <!-- untuk Filter data -->
        <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-sm row text-sm mb-0">
                        <label for="kampusFilter" class="col-md-2 col-form-label">Filter Kampus</label>
                        <div class="col-md-4">
                            <select id="kampusFilter" class="form-control form-control-sm">
                                <option value="">- Semua Kampus -</option>
                                @foreach ($kampus as $k)
                                    <option value="{{ $k->kampus_nama }}">{{ $k->kampus_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Pilih kampus untuk memfilter data tendik</small>
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
                <table class="table table-bordered table-striped table-hover table-sm" id="table_tendik">
                    <thead class="table-primary text-center">
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th>NIP</th>
                            <th>NIK</th>
                            <th>Nama Tendik</th>
                            <th>Jenis Kelamin</th>
                            <th>Nama Kampus</th>
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
            var dataTendik = $('#table_tendik').DataTable({
                processing: true,
                serverSide: true,
                order: [[0, 'asc']], // Menambahkan default sorting pada kolom pertama
                ajax: {
                    url: "{{ url('biodata/tendik/list') }}",
                    type: "POST",
                    data: function (d) {
                        d.search_query = $('#searchInput').val() || '';
                        d.kampus_nama = $('#kampusFilter').val() || '';
                        d._token = '{{ csrf_token() }}';
                    }
                },
                columns: [
                    { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                    { data: "nip", className: "text-nowrap" },
                    { data: "nik", className: "text-nowrap" },
                    { data: "tendik_nama", className: "text-nowrap" },
                    { data: "jenis_kelamin", className: "text-nowrap" },
                    { data: "kampus_nama", className: "text-nowrap" },
                    { data: "aksi", className: "text-center text-nowrap", orderable: false, searchable: false }
                ]
            });

        let delayTimer;
        $('#searchInput').on('keyup', function () {
            clearTimeout(delayTimer);
            delayTimer = setTimeout(() => {
                dataTendik.ajax.reload();
            }, 500);
        });

        $('#kampusFilter').on('change', function () {
                dataTendik.ajax.reload();
        });
        });

    </script>

@endpush
