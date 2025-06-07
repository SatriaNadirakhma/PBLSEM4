@extends('layouts.template') {{-- Sesuaikan dengan layout AdminLTE Anda --}}

@section('content')

    <div class="card card-outline card-primary shadow-sm">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h3 class="card-title">
                    <i class="fas fa-file-pdf"></i>
                    <b>{{ $page->title }}</b>
                </h3>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

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

            <form action="{{ route('panduan.admin.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="panduan_pdf">Unggah/Ganti File Panduan (PDF)</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="panduan_pdf" name="panduan_pdf" accept="application/pdf">
                            <label class="custom-file-label" for="panduan_pdf">Pilih file PDF</label>
                        </div>
                    </div>
                    @if ($panduan)
                        <small class="form-text text-muted mt-2">
                            File panduan saat ini: <strong>{{ $panduan->file_name }}</strong>.
                            Anda bisa menggantinya dengan mengunggah file baru.
                        </small>
                    @else
                        <small class="form-text text-muted mt-2">
                            Belum ada file panduan yang diunggah.
                        </small>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary mt-3">Unggah/Ganti Panduan</button>
            </form>
            <hr class="my-4"> {{-- Garis pemisah --}}

            {{-- Bagian PRATINJAU PDF --}}
            @if ($panduan && $panduan->file_url) {{-- Pastikan ada panduan dan URL file tersedia --}}
                <div class="mt-4">
                    <h5><i class="fas fa-eye"></i> Pratinjau Panduan</h5>
                    <div class="embed-responsive embed-responsive-16by9" style="height: 600px;">
                        <embed class="embed-responsive-item" src="{{ $panduan->file_url }}" type="application/pdf" width="100%" height="100%">
                        <p>Browser Anda tidak mendukung preview PDF. Anda bisa <a href="{{ $panduan->file_url }}" target="_blank">klik di sini untuk mengunduh/melihat PDF</a>.</p>
                    </div>
                </div>
            @elseif ($panduan && !$panduan->file_url)
                 <div class="mt-4 alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> File PDF tidak ditemukan di lokasi penyimpanan. Pastikan file telah diunggah dengan benar.
                </div>
            @else
                <div class="mt-4 alert alert-info">
                    <i class="fas fa-info-circle"></i> Belum ada panduan yang diunggah untuk ditampilkan pratinjaunya.
                </div>
            @endif

        </div> {{-- End card-body --}}
    </div> {{-- End card --}}
@endsection

@push('js')
<script>
    // Agar nama file muncul di label input file
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('panduan_pdf');
        fileInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Pilih file PDF';
            const nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });
    });
</script>
@endpush