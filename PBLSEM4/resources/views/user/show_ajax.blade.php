@empty($user)
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal">
                <span>&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">Data tidak ditemukan.</div>
        </div>
    </div>
</div>
@else
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Detail Data User</h5>
            <button type="button" class="close" data-dismiss="modal">
                <span>&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <table class="table table-bordered table-sm">
                <tr>
                    <th width="35%">ID User</th>
                    <td>{{ $user->user_id }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $user->email }}</td>
                </tr>
                <tr>
                    <th>Username</th>
                    <td>{{ $user->username }}</td>
                </tr>
                <tr>
                    <th>Nama Lengkap</th>
                    <td>
                        @if($user->role === 'admin')
                            {{ $user->admin->admin_nama ?? '-' }}
                        @elseif($user->role === 'mahasiswa')
                            {{ $user->mahasiswa->mahasiswa_nama ?? '-' }}
                        @elseif($user->role === 'dosen')
                            {{ $user->dosen->dosen_nama ?? '-' }}
                        @elseif($user->role === 'tendik')
                            {{ $user->tendik->tendik_nama ?? '-' }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Role</th>
                    <td>{{ ucfirst($user->role) }}</td>
                </tr>

                {{-- Tambahkan baris baru untuk profile --}}
                <tr>
                    <th>Profile</th>
                    <td>
                        @php
                            $profileFilename = $user->profile ?? '';
                            if (str_starts_with($profileFilename, 'profile/')) {
                                $profilePath = storage_path('app/public/' . $profileFilename);
                                $profileUrl = asset('storage/' . $profileFilename);
                            } else {
                                $profilePath = storage_path('app/public/profile/' . $profileFilename);
                                $profileUrl = asset('storage/profile/' . $profileFilename);
                            }
                        @endphp

                        @if($profileFilename && file_exists($profilePath))
                            <img src="{{ $profileUrl }}" alt="Profile" style="max-height: 150px; border-radius: 8px;">
                        @else
                            <em>Tidak ada foto profil</em>
                        @endif
                    </td>
                </tr>
            </table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
    </div>
</div>
@endempty
