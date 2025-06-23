<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Thống kê</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    * {
      box-sizing: border-box;
    }
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f5f7fa;
    }
  </style>
</head>
<body>
  <div style="display: flex; min-height: 100vh;">
    <!-- Sidebar -->
    <div style="width: 220px; background: white; padding: 30px 0; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column;">
      <div style="text-align: center; font-weight: bold; font-size: 22px; margin-bottom: 40px;">Logo</div>
      <div style="display: flex; flex-direction: column;">
        <div onclick="window.location.href='{{ route('admin.thong-ke') }}'"style="padding: 12px 24px; display: flex; align-items: center; gap: 10px; font-size: 14px; color: #0047ff; background-color: #eef3ff; font-weight: bold; cursor: pointer;">🏠 Thống kê</div>
        <div onclick="window.location.href='{{ route('admin.ql-bieumau') }}'"style="padding: 12px 24px; display: flex; align-items: center; gap: 10px;font-size: 14px; color: #333; cursor: pointer;">📄 Biểu mẫu</div>
        <div onclick="window.location.href='{{ route('admin.ql-taikhoan') }}'"style="padding: 12px 24px; display: flex; align-items: center; gap: 10px; font-size: 14px; color: #333; cursor: pointer;">👤 Tài Khoản</div>
        <div onclick="window.location.href='{{ route('admin.tt-canhan') }}'"style="padding: 12px 24px; display: flex; align-items: center; gap: 10px; font-size: 14px; color: #333; cursor: pointer;">⚙️ Thông tin cá nhân</div>
      </div>
    </div>

    <!-- Main content -->
    <div style="flex: 1; display: flex; flex-direction: column; background: #f9fafc;">
      <!-- Header -->
      <div style="background: #7da4ff; height: 72px; padding: 0 40px; color: white; font-weight: bold; display: flex; justify-content: space-between; align-items: center;">
        <span>Thống kê</span>
        <div style="width: 50px; height: 50px; background: #ccc; border-radius: 50%;"></div>
      </div>

      <!-- Content -->
      <div style="padding: 40px;">
        <!-- Biểu đồ lượt truy cập -->
        <div style="background: white; border-radius: 16px; padding: 40px; max-width: 100%; width: 95%; margin: auto; margin-bottom: 40px;">
          <h3 style="margin-bottom: 24px; font-size: 18px; font-weight: 600;">Biểu đồ lượt truy cập</h3>
          <canvas id="trafficChart" height="100"></canvas>
        </div>

        <!-- Biểu đồ thiết bị (dạng cột) -->
        <div style="background: white; border-radius: 16px; padding: 40px; max-width: 100%; width: 95%; margin: auto;">
          <h3 style="margin-bottom: 24px; font-size: 18px; font-weight: 600;">Biểu đồ thiết bị truy cập</h3>
          <canvas id="deviceChartBar" height="120"></canvas>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Biểu đồ lượt truy cập
    const ctx = document.getElementById('trafficChart').getContext('2d');
    const trafficChart = new Chart(ctx, {
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
            grid: { color: '#e5e7eb' }
          },
          x: {
            grid: { color: '#e5e7eb' }
          }
        },
        plugins: {
          legend: { display: false }
        }
      }
    });

    // Biểu đồ thiết bị truy cập (cột)
    const deviceBarCtx = document.getElementById('deviceChartBar').getContext('2d');
    const deviceChartBar = new Chart(deviceBarCtx, {
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
            ticks: { stepSize: 10 },
            grid: { color: '#e5e7eb' }
          },
          x: {
            grid: { color: '#f3f4f6' }
          }
        },
        plugins: {
          legend: {
            display: false
          }
        }
      }
    });
  </script>
</body>
</html>
