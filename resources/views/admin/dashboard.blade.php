@extends('layouts.app')

@section('auth')
    <div class="min-height-300 bg-primary position-absolute w-100"></div>
    @include('layouts.navbars.auth.sidebar')
    
    <main class="main-content position-relative border-radius-lg">
        @include('layouts.navbars.auth.nav', ['title' => 'Dashboard'])
        
        <div class="container-fluid py-4">
            {{-- Bagian Kartu Statistik (SUDAH DIMODIFIKASI) --}}
            <div class="row">
                {{-- Total Kapasitas --}}
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-4 d-flex align-items-center">
                                    <div class="icon icon-lg icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="col-8 text-end">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Kapasitas</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            {{ \App\Http\Controllers\Admin\UserController::formatBytes($totalKapasitas) }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Total User --}}
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-4 d-flex align-items-center">
                                    <div class="icon icon-lg icon-shape bg-gradient-danger shadow text-center border-radius-md">
                                        <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="col-8 text-end">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Total User</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            {{ $totalUser }} Pengguna
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- User Aktif --}}
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-4 d-flex align-items-center">
                                    <div class="icon icon-lg icon-shape bg-gradient-success shadow text-center border-radius-md">
                                        <i class="ni ni-circle-08 text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="col-8 text-end">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">User Aktif</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            {{ $userAktif }} Pengguna
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Penyimpanan Penuh --}}
                <div class="col-xl-3 col-sm-6">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-4 d-flex align-items-center">
                                    <div class="icon icon-lg icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                        <i class="ni ni-archive-2 text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                                <div class="col-8 text-end">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Penyimpanan Penuh</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            {{ $userPenuh }} Pengguna
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bagian Grafik (SUDAH DIMODIFIKASI) --}}
            <div class="row mt-4">
                <div class="col-lg-12 mb-lg-0 mb-4">
                    <div class="card z-index-2">
                        <div class="card-header pb-0">
                            <h6>Users Storage Overview</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="chart">
                                <canvas id="storage-chart" class="chart-canvas" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('layouts.footers.auth.footer')
        </div>
    </main>
@endsection

@push('dashboard')
<script>
    // Inisialisasi data dari Controller
    const chartData = @json($chartData);

    // Siapkan data untuk Chart.js
    const labels = chartData.map(user => user.name);
    const usageData = chartData.map(user => (user.storage_used / 1073741824).toFixed(2)); // Konversi ke GB
    const quotaData = chartData.map(user => (user.storage_quota / 1073741824).toFixed(2)); // Konversi ke GB

    // Inisialisasi Grafik
    var ctx = document.getElementById("storage-chart").getContext("2d");

    // Membuat warna gradient
    var gradientStroke1 = ctx.createLinearGradient(0, 230, 0, 50);
    gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
    gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
    gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)');

    new Chart(ctx, {
        type: "bar", // Anda bisa ganti ke 'line' jika lebih suka
        data: {
            labels: labels,
            datasets: [{
                label: "Penggunaan (GB)",
                tension: 0.4,
                borderWidth: 0,
                borderRadius: 4,
                borderSkipped: false,
                backgroundColor: "rgba(251, 99, 64, 0.8)", // Warna oranye solid
                data: usageData,
                maxBarThickness: 30
            },
            {
                label: "Kuota (GB)",
                type: 'line', // Jadikan dataset ini sebagai grafik garis
                tension: 0.4,
                borderWidth: 3,
                borderColor: "#3A416F", // Warna garis biru gelap
                backgroundColor: gradientStroke1, // Warna area di bawah garis
                data: quotaData,
                fill: true
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top', // Pindahkan legenda ke atas
                },
                tooltip: {
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleFont: {
                        size: 16,
                        weight: 'bold',
                    },
                    bodyFont: {
                        size: 14,
                    },
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += context.parsed.y + ' GB';
                            }
                            return label;
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [5, 5]
                    },
                    ticks: {
                        display: true,
                        padding: 10,
                        color: '#b2b9bf',
                        font: {
                            size: 14,
                            family: "Open Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                    }
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false
                    },
                    ticks: {
                        display: true,
                        color: '#b2b9bf',
                    },
                },
            },
        },
    });
</script>
@endpush