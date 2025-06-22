@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h3 class="card-title mb-2 mb-md-0">{{ $page->title }}</h3>
                <div class="btn-toolbar flex-wrap gap-2" role="toolbar" aria-label="Aksi Prodi" style="gap: 0.50rem">
 
                    <button onclick="modalAction('{{ url('/prodi/import') }}')" class="btn btn-sm shadow-sm rounded-pill"
                        style="background-color: #6f42c1; color: white; font-size: 0.95rem;">
                        <i class="fa fa-upload me-1"></i> Impor Excel
                    </button>

                    <a href="{{ url('/prodi/export_excel') }}" class="btn btn-sm shadow-sm rounded-pill"
                        style="background-color: #004085; color: white; font-size: 0.95rem;">
                        <i class="fa fa-file-excel me-1"></i> Ekspor Excel
                    </a>

                    <a href="{{ url('/prodi/export_pdf') }}" class="btn btn-sm shadow-sm rounded-pill"
                        style="background-color: #20c997; color: black; font-size: 0.95rem;">
                        <i class="fa fa-file-pdf me-1"></i> Ekspor PDF
                    </a>

                    <button onclick="modalAction('{{ url('/prodi/create_ajax') }}')" class="btn btn-sm shadow-sm rounded-pill"
                        style="background-color: #d63384; color: white; font-size: 0.95rem;">
                        <i class="fa fa-plus-circle me-1"></i> Tambah Data
                    </button>
                
                </div>
            </div>
            <!-- untuk Filter data -->
            <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group form-group-sm row text-sm mb-0">
                            <label for="filter_jurusan" class="col-md-2 col-form-label">Filter Jurusan</label>
                            <div class="col-md-4">
                                <select name="filter_jurusan" id="filter_jurusan" class="form-control form-control-sm filter_jurusan">
                                    <option value="">- Semua Jurusan -</option>
                                    @foreach($jurusan as $j)
                                        <option value="{{ $j->jurusan_id }}">{{ $j->jurusan_nama }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">Pilih Jurusan untuk memfilter program studi</small>
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
                <table class="table table-bordered table-striped table-hover table-sm" id="table_prodi">
                    <thead class="table-primary text-center">
                        <tr>
                            <th style="width: 40px">No</th>
                            <th style="width: 50px">Kode Program Studi</th>
                            <th style="width: 200px">Nama Program Studi</th>
                            <th style="width: 10px">Nama Jurusan</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function () {
                var myModal = new bootstrap.Modal(document.getElementById('myModal'), {
                    backdrop: 'static',
                    keyboard: false
                });
                myModal.show();
            });
        }

        $(document).ready(function () {
            var dataProdi = $('#table_prodi').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ url('prodi/list') }}",
                    type: "POST",
                    data: function (d) {
                        d.search_query = $('#searchInput').val();
                        d.filter_jurusan = $('#filter_jurusan').val();
                        d._token = '{{ csrf_token() }}';
                    }
                },
                columns: [
                    { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                    { data: "prodi_kode", className: "text-nowrap" },
                    { data: "prodi_nama", className: "text-nowrap" },
                    { data: "jurusan_nama", className: "text-nowrap" },
                    { data: "aksi", className: "text-center text-nowrap", orderable: false, searchable: false }
                ]
            });

            $('#filter_jurusan').on('change', function () { 
                dataProdi.ajax.reload();
            });
        });
    </script>
@endpush
