@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Daftar Pendaftaran</h1>

    @if($data->isEmpty())
        <div class="alert alert-warning">
            Tidak ada data pendaftaran tersedia.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Kode Pendaftaran</th>
                        <th>Tanggal Pendaftaran</th>
                        <th>Scan KTP</th>
                        <th>Scan KTM</th>
                        <th>Pas Foto</th>
                        <th>Mahasiswa ID</th>
                        <th>Jadwal ID</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $pendaftaran)
                        <tr>
                            <td>{{ $pendaftaran->pendaftaran_id }}</td>
                            <td>{{ $pendaftaran->pendaftaran_kode }}</td>
                            <td>{{ $pendaftaran->tanggal_pendaftaran }}</td>
                            <td>
                                @if($pendaftaran->scan_ktp)
                                    <a href="{{ asset('storage/' . $pendaftaran->scan_ktp) }}" target="_blank">Lihat KTP</a>
                                @else
                                    Tidak ada
                                @endif
                            </td>
                            <td>
                                @if($pendaftaran->scan_ktm)
                                    <a href="{{ asset('storage/' . $pendaftaran->scan_ktm) }}" target="_blank">Lihat KTM</a>
                                @else
                                    Tidak ada
                                @endif
                            </td>
                            <td>
                                @if($pendaftaran->pas_foto)
                                    <a href="{{ asset('storage/' . $pendaftaran->pas_foto) }}" target="_blank">Lihat Foto</a>
                                @else
                                    Tidak ada
                                @endif
                            </td>
                            <td>{{ $pendaftaran->mahasiswa_id }}</td>
                            <td>{{ $pendaftaran->jadwal_id }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
