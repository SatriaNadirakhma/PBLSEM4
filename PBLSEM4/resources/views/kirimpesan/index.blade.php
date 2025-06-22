@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
        </div>

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
                            <th>Pendaftaran</th>
                            <th>Status Pengiriman</th>
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
    var currentPendaftaranId = null;

    const cutoffDate = new Date('2025-06-11');

    function modalAction(url = '', pendaftaranId = null) {
        currentPendaftaranId = pendaftaranId;
        $('#myModal').load(url, function () {
            $('#myModal').modal('show');
        });
    }

    function updateDeliveryStatusDisplay(pendaftaranId, statusPengiriman) {
        var targetCell = $('#status-pengiriman-' + pendaftaranId);
        if (targetCell.length) {
            var statusText = '';
            var statusClass = '';

            if (statusPengiriman === 'antrean') {
                statusText = 'Antrean';
                statusClass = 'badge bg-warning';
            } else if (statusPengiriman === 'terkirim') {
                statusText = 'Terkirim';
                statusClass = 'badge bg-success';
            } else if (statusPengiriman === 'gagal') {
                statusText = 'Gagal';
                statusClass = 'badge bg-danger';
            }
            targetCell.html('<span class="' + statusClass + '">' + statusText + '</span>');
        }
    }

    $(document).ready(function () {
        // Muat status pengiriman dari Local Storage saat halaman dimuat
        const savedStatuses = JSON.parse(localStorage.getItem('whatsappDeliveryStatuses') || '{}');

        var dataRiwayat = $('#table_riwayat').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('kirimpesan/list') }}",
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
                // *** PERUBAHAN DI SINI: Ganti 'status' menjadi 'pendaftaran_status' ***
                { data: "pendaftaran_status", className: "text-center text-capitalize" },
                {
                    data: "status_pengiriman",
                    className: "text-center text-capitalize",
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        const pendaftaranId = row.pendaftaran_id;
                        const pendaftaranDate = new Date(row.tanggal_pendaftaran);

                        let displayText = 'Antrean';
                        let displayClass = 'badge bg-warning';

                        if (pendaftaranDate < cutoffDate) {
                            displayText = 'Terkirim';
                            displayClass = 'badge bg-success';
                            if (!savedStatuses[pendaftaranId] || savedStatuses[pendaftaranId] !== 'terkirim') {
                                savedStatuses[pendaftaranId] = 'terkirim';
                                localStorage.setItem('whatsappDeliveryStatuses', JSON.stringify(savedStatuses));
                            }
                        } else {
                            const savedStatus = savedStatuses[pendaftaranId];
                            if (savedStatus) {
                                if (savedStatus === 'antrean') {
                                    displayText = 'Antrean';
                                    displayClass = 'badge bg-warning';
                                } else if (savedStatus === 'terkirim') {
                                    displayText = 'Terkirim';
                                    displayClass = 'badge bg-success';
                                } else if (savedStatus === 'gagal') {
                                    displayText = 'Gagal';
                                    displayClass = 'badge bg-danger';
                                }
                            }
                        }

                        return '<span id="status-pengiriman-' + pendaftaranId + '" class="' + displayClass + '">' + displayText + '</span>';
                    }
                },
                { data: "aksi", className: "text-center text-nowrap", orderable: false, searchable: false }
            ],
            drawCallback: function() {
                // Logic is mostly handled by the render function for status_pengiriman
            }
        });

        $('#filterStatus').on('change', function () {
            dataRiwayat.ajax.reload();
        });

        $(document).on('whatsappSent', function(event, data) {
            // Perbarui Local Storage
            savedStatuses[data.pendaftaran_id] = data.pengiriman_status;
            localStorage.setItem('whatsappDeliveryStatuses', JSON.stringify(savedStatuses));
            updateDeliveryStatusDisplay(data.pendaftaran_id, data.pengiriman_status);
            $('#myModal').modal('hide');
        });
    });
</script>
@endpush