@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Peserta Tendik</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped" id="table_tendik">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIP</th>
                    <th>NIK</th>
                    <th>No. Telp</th>
                    <th>Alamat Asal</th>
                    <th>Alamat Sekarang</th>
                    <th>Jenis Kelamin</th>
                    <th>Kampus</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    $('#table_tendik').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("biodata.tendik.data") }}',
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'tendik_nama' },
            { data: 'nip' },
            { data: 'nik' },
            { data: 'no_telp' },
            { data: 'alamat_asal' },
            { data: 'alamat_sekarang' },
            { data: 'jenis_kelamin' },
            { data: 'kampus_id' }
        ]
    });
});
</script>
@endpush
