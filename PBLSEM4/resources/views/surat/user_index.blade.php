@extends('layouts.template')

@section('content')

    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-envelope-open-text"></i>
                <b>{{ $page->title }}</b>
            </h3>
        </div>
        <div class="card-body">
            @if ($surats->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Belum ada surat yang tersedia.
                </div>
            @else
                <div class="row">
                    @foreach ($surats as $surat)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title mb-3"><i class="fas fa-file-pdf"></i> {{ $surat->judul_surat }}</h5>
                                    <p class="card-text text-muted small">Diunggah: {{ $surat->created_at->format('d M Y') }}</p>
                                    <p class="card-text text-muted small">Nama File: {{ $surat->file_name }}</p>
                                    <div class="mt-auto"> {{-- Push button to the bottom --}}
                                        <a href="{{ route('surat.show', $surat->surat_id) }}" target="_blank" class="btn btn-primary btn-sm btn-block">
                                            <i class="fas fa-eye"></i> Lihat Surat
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection