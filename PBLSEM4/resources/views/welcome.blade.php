@extends('layouts.template')

@section('content')

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title mb-0">Dashboard</h3>
    </div>
    <div class="card-body">

        @php
            $role = $user->role;
            switch ($role) {
                case 'admin':
                    $nama = $user->admin_nama ?? $user->nama_lengkap;
                    $pesan = "Terima kasih telah menjaga kualitas sistem. Anda memiliki akses penuh untuk mengelola data pengguna, jadwal, dan informasi penting lainnya.";
                    break;
                case 'mahasiswa':
                    $nama = $user->mahasiswa_nama ?? $user->nama_lengkap;
                    $pesan = "Sistem ini dirancang untuk memudahkan Anda dalam melakukan pendaftaran TOEIC. Pastikan membaca buku panduan yang tersedia agar proses berjalan lancar.";
                    break;
                case 'dosen':
                    $nama = $user->dosen_nama ?? $user->nama_lengkap;
                    $pesan = "Platform ini mempermudah Anda dalam memantau dan membantu proses pendaftaran TOEIC. Silakan baca buku panduan yang telah disediakan.";
                    break;
                case 'tendik':
                    $nama = $user->tendik_nama ?? $user->nama_lengkap;
                    $pesan = "Sistem ini mendukung tugas administrasi akademik khususnya dalam pengelolaan pendaftaran TOEIC. Silakan merujuk ke buku panduan untuk informasi lengkap.";
                    break;
                default:
                    $nama = $user->nama_lengkap;
                    $pesan = "Selamat datang di platform PBL SEM 4. Silakan sesuaikan fitur yang tersedia dengan kebutuhan peran Anda.";
            }
        @endphp

        <div class="mb-4">
            <h5 class="mb-1">Halo, <strong>{{ $nama }}</strong>!</h5>
            <span class="badge bg-info text-dark text-capitalize mb-3 px-3 py-1">{{ $role }}</span>
            <p style="font-size: 1rem; line-height: 1.6;">{{ $pesan }}</p>
        </div>

    </div>
</div>

@if($role === 'admin')
<div class="container-fluid px-3">
    <div class="d-flex flex-nowrap overflow-auto py-2" style="gap: 0;">
        @php
            $cards = [
                ['bg' => 'bg-primary text-white', 'title' => 'Jumlah User', 'count' => $jumlah_user, 'icon' => 'fa-users'],
                ['bg' => 'bg-dark text-white', 'title' => 'Admin', 'count' => $jumlah_admin ?? 0, 'icon' => 'fa-user-shield'],
                ['bg' => 'bg-success text-white', 'title' => 'Mahasiswa', 'count' => $jumlah_mahasiswa, 'icon' => 'fa-user-graduate'],
                ['bg' => 'bg-info text-white', 'title' => 'Dosen', 'count' => $jumlah_dosen, 'icon' => 'fa-chalkboard-teacher'],
                ['bg' => 'bg-warning text-dark', 'title' => 'Tendik', 'count' => $jumlah_tendik, 'icon' => 'fa-briefcase'],
                ['bg' => 'bg-secondary text-white', 'title' => 'Jumlah Pendaftar', 'count' => $jumlah_pendaftar, 'icon' => 'fa-clipboard-list'],
                ['bg' => 'bg-light text-dark', 'title' => 'Status Menunggu', 'count' => $status_menunggu, 'icon' => 'fa-clock'],
                ['bg' => 'bg-success text-white', 'title' => 'Status Diterima', 'count' => $status_diterima, 'icon' => 'fa-check-circle'],
                ['bg' => 'bg-danger text-white', 'title' => 'Status Ditolak', 'count' => $status_ditolak, 'icon' => 'fa-times-circle'],
                ['bg' => 'bg-success text-white', 'title' => 'Peserta Lolos', 'count' => $jumlah_lolos ?? 0, 'icon' => 'fa-thumbs-up'],
                ['bg' => 'bg-danger text-white', 'title' => 'Peserta Tidak Lolos', 'count' => $jumlah_tidak_lolos ?? 0, 'icon' => 'fa-thumbs-down'],
            ];
        @endphp

        @foreach ($cards as $index => $card)
        <div 
            class="card {{ $card['bg'] }} text-center shadow-sm p-2" 
            style="width: 90px; aspect-ratio: 1 / 1; flex: 0 0 auto; margin-right: {{ $index < count($cards) - 1 ? '8px' : '0' }};">
            <div class="card-body p-1 d-flex flex-column justify-content-center align-items-center">
                <div class="fs-7 mb-1" style="font-size: 0.75rem;">{{ $card['title'] }}</div>
                <div class="fs-6 fw-bold" style="font-size: 1rem;">{{ $card['count'] }}</div>
                <i class="fa {{ $card['icon'] }}" style="font-size: 1rem; margin-top: 2px;"></i>
            </div>
        </div>
        @endforeach
    </div>
</div>

<div class="mt-5 container" style="height: 400px;">
    <h5>Statistik Pendaftar Per Bulan Tahun {{ \Carbon\Carbon::now()->year }}</h5>
    <canvas id="lineChart" style="width: 100%; height: 300px;"></canvas>

    <h5 class="mt-5">Persentase Peserta Lolos dan Tidak Lolos</h5>
    <canvas id="pieChart" style="width: 100%; height: 300px;"></canvas>
</div>
@endif

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
window.onload = function() {
    const dataPendaftarPerBulan = @json($dataPendaftarPerBulan ?? Array(12).fill(0));
    console.log("Data pendaftar per bulan:", dataPendaftarPerBulan);

    const bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    const ctxLine = document.getElementById('lineChart').getContext('2d');
    const lineChart = new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: bulanLabels,
            datasets: [{
                label: 'Jumlah Pendaftar',
                data: dataPendaftarPerBulan,
                fill: false,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                tension: 0.3,
                pointRadius: 5,
                pointHoverRadius: 7,
                borderWidth: 3,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    const jumlahLolos = {{ $jumlah_lolos ?? 0 }};
    const jumlahTidakLolos = {{ $jumlah_tidak_lolos ?? 0 }};
    console.log("Jumlah Lolos:", jumlahLolos, "Jumlah Tidak Lolos:", jumlahTidakLolos);

    const ctxPie = document.getElementById('pieChart').getContext('2d');
    const pieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['Lolos', 'Tidak Lolos'],
            datasets: [{
                label: 'Status Kelulusan',
                data: [jumlahLolos, jumlahTidakLolos],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(255, 99, 132, 0.7)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
};
</script>

@endsection

