@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <h3 class="card-title mb-2 mb-md-0">{{ $page->title }}</h3>
        </div>

        <div class="card-body">
            <table class="table table-bordered w-100" style="background-color: #e6f0ff;">
                <tr>
                    <th width="30%">Mahasiswa ID</th>
                    <td>{{ $mahasiswa->mahasiswa_id }}</td>
                </tr>
                <tr>
                    <th>NIM</th>
                    <td>{{ $mahasiswa->nim }}</td>
                </tr>
                <tr>
                    <th>NIK</th>
                    <td>{{ $mahasiswa->nik }}</td>
                </tr>
                <tr>
                    <th>Nama</th>
                    <td>{{ $mahasiswa->mahasiswa_nama }}</td>
                </tr>
                <tr>
                    <th>Angkatan</th>
                    <td>{{ $mahasiswa->angkatan }}</td>
                </tr>
                <tr>
                    <th>No. Telepon</th>
                    <td>{{ $mahasiswa->no_telp }}</td>
                </tr>
                <tr>
                    <th>Alamat Asal</th>
                    <td>{{ $mahasiswa->alamat_asal }}</td>
                </tr>
                <tr>
                    <th>Alamat Sekarang</th>
                    <td>{{ $mahasiswa->alamat_sekarang }}</td>
                </tr>
                <tr>
                    <th>Jenis Kelamin</th>
                    <td>{{ $mahasiswa->jenis_kelamin }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $mahasiswa->status }}</td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td>{{ $mahasiswa->keterangan }}</td>
                </tr>
                <tr>
                    <th>Program Studi</th>
                    <td>{{ $mahasiswa->prodi->nama_prodi ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>
@endsection
