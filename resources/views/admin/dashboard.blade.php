@extends('layouts.app')

@section('auth')
    <div class="min-height-300 bg-primary position-absolute w-100"></div>
    @include('layouts.navbars.auth.sidebar')
    
    <main class="main-content position-relative border-radius-lg">
        @include('layouts.navbars.auth.nav', ['title' => 'Dashboard'])
        
        <div class="container-fluid py-4">
            {{-- Bagian Kartu Statistik --}}
            <div class="row">
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Kapasitas</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            {{ \App\Http\Controllers\Admin\UserController::formatBytes($totalKapasitas) }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Total User</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            {{ $totalUser }} Pengguna
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">User Aktif</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            {{ $userAktif }} Pengguna
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="ni ni-circle-08 text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Penyimpanan Penuh</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            {{ $userPenuh }} Pengguna
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="ni ni-archive-2 text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bagian Grafik --}}
            <div class="row mt-4">
                <div class="col-lg-12 mb-lg-0 mb-4">
                    <div class="card">
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
    new Chart(ctx, {
        type: "bar",
        data: {
            labels: labels,
            datasets: [{
                label: "Penggunaan (GB)",
                tension: 0.4,
                borderWidth: 0,
                borderRadius: 4,
                borderSkipped: false,
                backgroundColor: "#b3ccff",
                data: usageData,
                maxBarThickness: 30
            },
            {
                label: "Kuota (GB)",
                tension: 0.4,
                borderWidth: 0,
                borderRadius: 4,
                borderSkipped: false,
                backgroundColor: "#3366ff",
                data: quotaData,
                maxBarThickness: 30
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
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
                    },
                    ticks: {
                        suggestedMin: 0,
                        suggestedMax: 500,
                        beginAtZero: true,
                        padding: 15,
                        font: {
                            size: 14,
                            family: "Open Sans",
                            style: 'normal',
                            lineHeight: 2
                        },
                        color: "#000"
                    },
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false
                    },
                    ticks: {
                        display: true
                    },
                },
            },
        },
    });
</script>
@endpush