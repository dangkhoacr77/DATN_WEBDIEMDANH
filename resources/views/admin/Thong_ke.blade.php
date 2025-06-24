@extends('layout/admin')

@section('title', 'Thống kê')
@section('page-title', 'Thống kê')

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
    <!-- Biểu đồ lượt truy cập -->
    <div
        style="background: white; border-radius: 16px; padding: 40px; max-width: 100%; width: 95%; margin: auto; margin-bottom: 40px; box-sizing: border-box;">
        <h3 style="margin-bottom: 24px; font-size: 18px; font-weight: 600;">Biểu đồ lượt truy cập</h3>
        <canvas id="trafficChart" height="100"></canvas>
    </div>

    <!-- Biểu đồ thiết bị truy cập -->
    <div
        style="background: white; border-radius: 16px; padding: 40px; max-width: 100%; width: 95%; margin: auto; box-sizing: border-box;">
        <h3 style="margin-bottom: 24px; font-size: 18px; font-weight: 600;">Biểu đồ thiết bị truy cập</h3>
        <canvas id="deviceChartBar" height="120"></canvas>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('trafficChart')?.getContext('2d');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan'],
                        datasets: [{
                            label: 'Lượt truy cập',
                            data: [120, 340, 500, 800, 210, 560, 680],
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#e5e7eb'
                                }
                            },
                            x: {
                                grid: {
                                    color: '#e5e7eb'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }

            const deviceCtx = document.getElementById('deviceChartBar')?.getContext('2d');
            if (deviceCtx) {
                new Chart(deviceCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Máy tính', 'Điện thoại', 'Máy tính bảng'],
                        datasets: [{
                            label: 'Thiết bị truy cập',
                            data: [55, 35, 10],
                            backgroundColor: ['#3b82f6', '#10b981', '#f59e0b'],
                            borderRadius: 8,
                            barThickness: 40
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 10
                                },
                                grid: {
                                    color: '#e5e7eb'
                                }
                            },
                            x: {
                                grid: {
                                    color: '#f3f4f6'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        });
    </script>
@endpush
