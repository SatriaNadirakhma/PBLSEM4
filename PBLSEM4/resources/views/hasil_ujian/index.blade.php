@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary shadow-sm">
    <div class="card-header">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <h3 class="card-title mb-2 mb-md-0">{{ $page->title }}</h3>
            <div class="btn-toolbar flex-wrap gap-2" role="toolbar" aria-label="Aksi Hasil Ujian" style="gap: 0.50rem;">
                <a href="{{ url('/hasil_ujian/import') }}" class="btn btn-sm shadow-sm rounded-pill"
                    style="background-color: #6f42c1; color: white; font-size: 0.95rem;">
                    <i class="fa fa-upload me-1"></i> Impor Excel
                </a>
                <a href="{{ url('/hasil_ujian/export_excel') }}" class="btn btn-sm shadow-sm rounded-pill"
                    style="background-color: #004085; color: white; font-size: 0.95rem;">
                    <i class="fa fa-file-excel me-1"></i> Ekspor Excel
                </a>
                <a href="{{ url('/hasil_ujian/export_pdf') }}" class="btn btn-sm shadow-sm rounded-pill"
                    style="background-color: #20c997; color: black; font-size: 0.95rem;">
                    <i class="fa fa-file-pdf me-1"></i> Ekspor PDF
                </a>
                <a href="{{ url('/hasil_ujian/create_ajax') }}" class="btn btn-sm shadow-sm rounded-pill"
                    style="background-color: #d63384; color: white; font-size: 0.95rem;">
                    <i class="fa fa-plus-circle me-1"></i> Tambah Data
                </a>
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
            <table class="table table-bordered table-striped table-hover table-sm" id="table_hasil_ujian">
                <thead class="table-primary text-center">
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>Nama Peserta</th>
                        <th>Jadwal</th>
                        <th>Nilai Listening</th>
                        <th>Nilai Reading</th>
                        <th>Total Nilai</th>
                        <th>Status</th>
                        <th>Role</th>
                        <th style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
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
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });

        var dataHasilUjian = $('#table_hasil_ujian').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('hasil_ujian/list') }}", // DIPERBAIKI: dari hasil-ujian jadi hasil_ujian
                type: "POST",
                data: function (d) {
                    d.search_query = $('#searchInput').val();
                },
                error: function(xhr, error, thrown) {
                    console.log('Ajax Error Details:');
                    console.log('Status:', xhr.status);
                    console.log('Response:', xhr.responseText);
                    console.log('Error:', error);
                    alert('Error loading data. Check console for details.');
                }
            },
            columns: [
                { data: "DT_RowIndex", className: "text-center", orderable: false, searchable: false },
                { data: "nama", className: "text-nowrap" },
                { data: "tanggal_pelaksanaan", className: "text-nowrap" },
                { data: "nilai_listening", className: "text-center" },
                { data: "nilai_reading", className: "text-center" },
                { data: "nilai_total", className: "text-center fw-bold" },
                { data: "status_lulus", className: "text-center text-nowrap" },
                { data: "role", className: "text-center text-nowrap" },
                { data: "aksi", className: "text-center text-nowrap", orderable: false, searchable: false }
            ],
            language: {
                emptyTable: "Tidak ada data",
                zeroRecords: "Data tidak cocok dengan pencarian",
                processing: "Memuat data..."
            }
        });

        // Debounce search
        let typingTimer;
        $('#searchInput').on('keyup', function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(() => {
                dataHasilUjian.ajax.reload();
            }, 500);
        });
    });
</script>
@endpush