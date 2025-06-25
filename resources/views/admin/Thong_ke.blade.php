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

    <!-- Biểu đồ số lượng biểu mẫu theo tháng -->
    <div
        style="background: white; border-radius: 16px; padding: 40px; max-width: 100%; width: 95%; margin: auto; box-sizing: border-box;">
        <h3 style="margin-bottom: 24px; font-size: 18px; font-weight: 600;">Biểu đồ biểu mẫu được tạo</h3>
        <canvas id="formChart" height="100"></canvas>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Biểu đồ lượt truy cập 
            new Chart(document.getElementById('trafficChart'), {
                type: 'bar',
                data: {
                    labels: ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11',
                        'Th12'
                    ],
                    datasets: [{
                        label: 'Lượt truy cập',
                        data: @json($visitCounts),
                        backgroundColor: '#3b82f6',
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Biểu đồ biểu mẫu theo tháng
            const formCtx = document.getElementById('formChart')?.getContext('2d');
            if (formCtx) {
                new Chart(formCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10',
                            'Th11', 'Th12'
                        ],
                        datasets: [{
                            label: 'Biểu mẫu đã tạo',
                            data: @json($bieuMauData),
                            backgroundColor: '#6366f1',
                            borderRadius: 6,
                            barThickness: 32
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
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
