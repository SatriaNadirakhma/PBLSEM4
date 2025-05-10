<h1>Data Tendik</h1>
<table border="1">
    <tr>
        <th>ID</th>
        <th>NIP</th>
        <th>NIK</th>
        <th>Nama</th>
        <th>No Telp</th>
        <th>Alamat Asal</th>
        <th>Alamat Sekarang</th>
        <th>Jenis Kelamin</th>
        <th>Kampus ID</th>
    </tr>
    @foreach($data as $t)
    <tr>
        <td>{{ $t->tendik_id }}</td>
        <td>{{ $t->nip }}</td>
        <td>{{ $t->nik }}</td>
        <td>{{ $t->tendik_nama }}</td>
        <td>{{ $t->no_telp }}</td>
        <td>{{ $t->alamat_asal }}</td>
        <td>{{ $t->alamat_sekarang }}</td>
        <td>{{ $t->jenis_kelamin }}</td>
        <td>{{ $t->kampus_id }}</td>
    </tr>
    @endforeach
</table>
