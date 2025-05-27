@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <!-- <h1>{{ $page->title }}</h1> -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            @foreach ($breadcrumb->list as $item)
                <li class="breadcrumb-item">{{ $item }}</li>
            @endforeach
            <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb->title }}</li>
        </ol>
    </nav>

    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center">
                    @if($user->profile)
                        <img id="profilePreview" src="{{ asset('storage/' . $user->profile) }}" alt="Profile Picture" class="img-fluid rounded" style="max-height: 250px;">
                    @else
                        <img id="profilePreview" src="{{ asset('img/default-profile.png') }}" alt="Default Profile Picture" class="img-fluid rounded" style="max-height: 250px;">
                    @endif
                </div>
                <div class="col-md-8">
                    <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <th scope="row" style="width: 150px;">Username</th>
                            <td>: {{ $user->username }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Nama Lengkap</th>
                            <td>: {{ $user->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <th scope="row">Role</th>
                            <td>: <span class="badge bg-info text-dark">{{ ucfirst($user->role) }}</span></td>
                        </tr>
                    </tbody>
                </table>

                    <form action="{{ url('/profile/update-photo') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="profile_picture" class="form-label" style="font-weight: normal;">Ganti Foto Profil</label>
                            <input type="file" name="profile_picture" id="profile_picture" class="form-control @error('profile_picture') is-invalid @enderror" accept="image/*">
                            @error('profile_picture')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Update Foto Profil</button>
                        <button type="button" class="btn btn-secondary ml-2" onclick="window.history.back()">Kembali</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Preview gambar sebelum upload
    document.getElementById('profile_picture').addEventListener('change', function(event) {
        const [file] = this.files;
        if (file) {
            const preview = document.getElementById('profilePreview');
            preview.src = URL.createObjectURL(file);
        }
    });
</script>
@endsection
