@extends('layouts.template')

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Dashboard</h3>
    </div>
    <div class="card-body">

        @php
            $role = auth()->user()->role;
        @endphp

        @if ($role === 'admin')
            <p>Selamat datang, Admin!</p>
            <p>Anda dapat mengelola seluruh data dalam aplikasi ini.</p>

        @elseif ($role === 'mahasiswa')
            <p>Selamat datang, Mahasiswa!</p>
            <p>Silakan lihat jadwal dan mata kuliah Anda.</p>

        @elseif ($role === 'dosen')
            <p>Selamat datang, Dosen!</p>
            <p>Silakan kelola materi dan nilai mahasiswa Anda.</p>

        @elseif ($role === 'tendik')
            <p>Selamat datang, Tenaga Kependidikan!</p>
            <p>Anda dapat melihat dan mengatur data administrasi akademik.</p>

        @else
            <p>Selamat datang di aplikasi PBL SEM 4.</p>
        @endif

    </div>
</div>

@endsection
