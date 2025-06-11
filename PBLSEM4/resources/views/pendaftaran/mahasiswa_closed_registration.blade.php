@extends('layouts.template')

@section('content')
<div class="container mt-4" style="max-width: 700px;">
    <div class="card card-outline card-danger shadow-sm">
        <div class="card-header">
            <h3 class="card-title">Pendaftaran Sedang Ditutup</h3>
        </div>
        <div class="card-body text-center">
            <i class="fas fa-exclamation-circle fa-4x text-danger mb-3"></i>
            <p class="lead">
                Mohon maaf, pendaftaran untuk program TOEIC gratis saat ini masih <b>ditutup</b>.  
                Silakan pantau dashboard informasi secara berkala untuk mengetahui kapan pendaftaran akan dibuka kembali.
            </p>
            <p class="text-muted">
                Jika Anda memiliki pertanyaan, silakan hubungi administrator.
            </p>
            <a href="{{ url('/') }}" class="btn btn-primary mt-3">Kembali ke Dashboard</a>
        </div>
    </div>
</div>
@endsection