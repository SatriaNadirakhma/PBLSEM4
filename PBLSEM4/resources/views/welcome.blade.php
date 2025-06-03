@extends('layouts.template')
@push('css')
<style>
    .animated {
        animation-duration: 0.4s;
        animation-fill-mode: both;
    }

    .fadeIn {
        animation-name: fadeIn;
    }

    .judul-animasi {
    text-transform: uppercase; /* Huruf kapital semua */
    background-color: #007bff; /* Biru (Bootstrap primary blue) */
    color: white;              /* Warna teks putih supaya kontras */
    padding: 3px 8px;          /* Ruang di dalam kotak */
    display: inline-block;     /* Biar kotak pas di belakang teks */
    border-radius: 4px;        /* Sudut kotak agak membulat */
    font-weight: bold;         /* Pasti bold */
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endpush

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
        <!-- Debug Info - Remove this after fixing -->
        <!-- <div class="alert alert-info" style="font-size: 0.85rem;">
            <strong>Debug Info:</strong><br>
            Role: {{ $role }}<br>
            Data Pendaftar: {{ json_encode($dataPendaftarPerBulan ?? 'Not Set') }}<br>
            Jumlah Lolos: {{ $jumlah_lolos ?? 'Not Set' }}<br>
            Jumlah Tidak Lolos: {{ $jumlah_tidak_lolos ?? 'Not Set' }}
        </div> -->

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

        <!-- Charts Section -->
        <div class="mt-5 container">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Statistik Pendaftar Per Bulan {{ \Carbon\Carbon::now()->year }}</h5>
                        </div>
                        <div class="card-body">
                            <div style="height: 300px; position: relative;">
                                <canvas id="lineChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Persentase Peserta Lolos vs Tidak Lolos</h5>
                        </div>
                        <div class="card-body">
                            <div style="height: 300px; position: relative;">
                                <canvas id="pieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($role === 'mahasiswa')Add commentMore actions
        <div class="mt-5">
            <h4 class="mb-3 font-weight-bold text-primary">📢 Informasi Terkini</h4>

            @forelse($informasi as $index => $item)
                <div class="card border-left-info shadow-sm mb-3 animate__animated animate__fadeInLeft" 
                    style="animation-delay: {{ $index * 0.2 }}s; animation-duration: 0.8s;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="card-title mb-0 judul-animasi">
                                <i class="fas fa-bullhorn text-white mr-2"></i>{{ $item->judul }}
                            </h5>
                        </div>
                        <p class="card-text text-dark mb-0" style="font-size: 1.25rem;">{{ $item->isi }}</p>
                    </div>
                </div>
            @empty
                <div class="alert alert-info shadow-sm mt-3 animate__animated animate__fadeInLeft">
                    <i class="fas fa-info-circle mr-2"></i>Tidak ada informasi terbaru saat ini.Add commentMore actions
                </div>
            @endforelse
        </div>
    @endif

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
// Wait for everything to load including Chart.js
window.addEventListener('load', function() {
    console.log('Window fully loaded, Chart.js available:', typeof Chart !== 'undefined');
    
    // Check if we're admin
    const userRole = '{{ $role }}';
    console.log('User role:', userRole);
    
    if (userRole === 'admin') {
        // Add small delay to ensure DOM is ready
        setTimeout(function() {
            initializeCharts();
        }, 100);
    }
});

function initializeCharts() {
    console.log('Initializing charts...');
    
    // Get data from PHP
    const dataPendaftarPerBulan = {!! json_encode($dataPendaftarPerBulan ?? array_fill(0, 12, 0)) !!};
    const jumlahLolos = {{ $jumlah_lolos ?? 0 }};
    const jumlahTidakLolos = {{ $jumlah_tidak_lolos ?? 0 }};
    
    console.log('Chart data received:', {
        dataPendaftarPerBulan: dataPendaftarPerBulan,
        jumlahLolos: jumlahLolos,
        jumlahTidakLolos: jumlahTidakLolos
    });

    // Check if Chart.js is loaded
    if (typeof Chart === 'undefined') {
        console.error('Chart.js is not loaded');
        return;
    }

    // Initialize charts
    try {
        initLineChart(dataPendaftarPerBulan);
        initPieChart(jumlahLolos, jumlahTidakLolos);
    } catch (error) {
        console.error('Error initializing charts:', error);
    }
}

function initLineChart(data) {
    console.log('Initializing line chart with data:', data);
    
    const lineCanvas = document.getElementById('lineChart');
    if (!lineCanvas) {
        console.error('Line chart canvas not found');
        return;
    }

    const bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const ctx = lineCanvas.getContext('2d');

    // Destroy existing chart if any
    if (window.lineChartInstance) {
        window.lineChartInstance.destroy();
    }

    window.lineChartInstance = new Chart(ctx, {
        type: 'line',
        data: {
            labels: bulanLabels,
            datasets: [{
                label: 'Jumlah Pendaftar',
                data: data,
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
            maintainAspectRatio: false,
            interaction: {
                intersect: false
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0,
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    mode: 'index'
                }
            }
        }
    });
    
    console.log('Line chart initialized successfully');
}

function initPieChart(lolos, tidakLolos) {
    console.log('Initializing pie chart with data:', { lolos, tidakLolos });
    
    const pieCanvas = document.getElementById('pieChart');
    if (!pieCanvas) {
        console.error('Pie chart canvas not found');
        return;
    }

    const ctx = pieCanvas.getContext('2d');
    
    // Handle case when no data
    const totalData = lolos + tidakLolos;
    let chartData, chartLabels, backgroundColors, borderColors;
    
    if (totalData === 0) {
        chartData = [1];
        chartLabels = ['Belum Ada Data'];
        backgroundColors = ['rgba(128, 128, 128, 0.7)'];
        borderColors = ['rgba(128, 128, 128, 1)'];
    } else {
        chartData = [lolos, tidakLolos];
        chartLabels = ['Lolos (' + lolos + ')', 'Tidak Lolos (' + tidakLolos + ')'];
        backgroundColors = [
            'rgba(75, 192, 192, 0.7)',
            'rgba(255, 99, 132, 0.7)'
        ];
        borderColors = [
            'rgba(75, 192, 192, 1)',
            'rgba(255, 99, 132, 1)'
        ];
    }

    // Destroy existing chart if any
    if (window.pieChartInstance) {
        window.pieChartInstance.destroy();
    }

    window.pieChartInstance = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Status Kelulusan',
                data: chartData,
                backgroundColor: backgroundColors,
                borderColor: borderColors,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });
    
    console.log('Pie chart initialized successfully');
}
</script>

@endsection