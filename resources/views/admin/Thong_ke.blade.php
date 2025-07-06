@extends('layout/admin')

@section('title', 'Thống kê')
@section('page-title', 'Thống kê')

@push('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
    <style>
        .chart-container {
            display: flex;
            flex-direction: column;
            gap: 24px;
            padding: 24px;
        }

        .chart-large {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .chart-small-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
        }

        .chart-box {
            background: #ffffff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        }

        .chart-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 14px;
            color: #1f2937;
        }

        canvas {
            max-height: 280px;
        }

        @media (max-width: 768px) {
            canvas {
                max-height: 200px;
            }
        }
    </style>

    <div class="chart-container">
        {{-- Lượt truy cập (to) --}}
        <div class="chart-large">
            <div class="chart-title">📈 Lượt truy cập theo tháng</div>
            <canvas id="visitChart"></canvas>
        </div>

        {{-- 2 biểu đồ nhỏ --}}
        <div class="chart-small-grid">
            <div class="chart-box">
                <div class="chart-title">📄 Biểu mẫu đã tạo</div>
                <canvas id="formChart"></canvas>
            </div>

            <div class="chart-box">
                <div class="chart-title">👤 Tài khoản đã tạo</div>
                <canvas id="accountChart"></canvas>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const months = ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'];

            // Biểu đồ Lượt truy cập
            new Chart(document.getElementById('visitChart'), {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Lượt truy cập',
                        data: @json($visitCounts),
                        fill: true,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59,130,246,0.1)',
                        tension: 0.3,
                        pointRadius: 3,
                        pointBackgroundColor: '#2563eb',
                        borderWidth: 2
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });

            // Biểu mẫu
            new Chart(document.getElementById('formChart'), {
                type: 'bar',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Biểu mẫu đã tạo',
                        data: @json($bieuMauData),
                        backgroundColor: '#10b981',
                        borderRadius: 6,
                        barThickness: 24
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true },
                        x: { ticks: { maxRotation: 0, minRotation: 0 } }
                    }
                }
            });

            // Tài khoản
            new Chart(document.getElementById('accountChart'), {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Tài khoản đã tạo',
                        data: @json($accountCreatedData),
                        fill: false,
                        borderColor: '#f97316',
                        tension: 0.3,
                        pointRadius: 3,
                        borderWidth: 2,
                        pointBackgroundColor: '#f97316'
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true },
                        x: { display: true }
                    }
                }
            });
        });
    </script>
@endpush
