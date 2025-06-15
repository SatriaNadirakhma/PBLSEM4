@extends('layouts.template')

@section('content')

    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-plus"></i>
                <b>{{ $page->title }}</b>
            </h3>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form action="{{ route('surat.admin.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="judul_surat">Judul Surat</label>
                    <input type="text" class="form-control" id="judul_surat" name="judul_surat" value="{{ old('judul_surat') }}" placeholder="Masukkan judul surat" required>
                </div>
                <div class="form-group">
                    <label for="surat_pdf">Unggah File Surat (PDF)</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="surat_pdf" name="surat_pdf" accept="application/pdf" required>
                            <label class="custom-file-label" for="surat_pdf">Pilih file PDF</label>
                        </div>
                    </div>
                    <small class="form-text text-muted mt-2">Ukuran file maksimal 10MB.</small>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Simpan Surat</button>
                <a href="{{ route('surat.admin.index') }}" class="btn btn-secondary mt-3">Kembali</a>
            </form>
        </div>
    </div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('surat_pdf');
        fileInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Pilih file PDF';
            const nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });
    });
</script>
@endpush