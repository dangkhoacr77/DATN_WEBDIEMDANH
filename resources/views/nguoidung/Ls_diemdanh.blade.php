<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Lịch sử điểm danh</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body style="background: #f8f9fa; font-family: 'Segoe UI', sans-serif;">

<!-- Header -->
<nav class="navbar navbar-light bg-primary text-white px-3 d-flex justify-content-between">
  <div class="navbar-brand mb-0 h1 text-white">Logo</div>
  <div class="d-flex align-items-center gap-3">

    <!-- Avatar & menu -->
    <div class="avatar-menu" onclick="toggleMenu()" style="position: relative; display: flex; align-items: center; gap: 10px; cursor: pointer;">
      @guest
        <img src="{{ asset('images/default-avatar.png') }}" alt="avatar" class="rounded-circle" width="32">
        <span><a class="text-white text-decoration-none">Đăng nhập</a></span>
      @else
        <img src="{{ Auth::user()->avatar_url ?? asset('images/default-avatar.png') }}" alt="avatar" class="rounded-circle" width="32">
        <span class="text-white">{{ Auth::user()->name }}</span>
      @endguest

      <div id="avatarDropdown" style="position: absolute; right: 0; top: 50px; display: none; background: white; border: 1px solid #ccc; border-radius: 5px; z-index: 100; min-width: 120px;">
        @guest
          <a href="{{ route('xacthuc.dang-nhap') }}" style="display: block; padding: 10px 15px; text-decoration: none; color: black;">Đăng nhập</a>
          <a href="{{ route('xacthuc.dang-ky') }}" style="display: block; padding: 10px 15px; text-decoration: none; color: black;">Đăng ký</a>
          <a href="{{ route('admin.thong-ke') }}" style="display: block; padding: 10px 15px; text-decoration: none; color: black;">Admin</a>
          <a href="{{ route('nguoidung.tt-canhan') }}" style="display: block; padding: 10px 15px; text-decoration: none; color: black;">Người dùng</a>
        @else
          <a href="#" style="display: block; padding: 10px 15px; text-decoration: none; color: black;">Cài đặt</a>
          <form method="POST" action="#">
            @csrf
            <button type="submit" class="dropdown-item" style="display: block; padding: 10px 15px; background: none; border: none; width: 100%; text-align: left;">Đăng xuất</button>
          </form>
        @endguest
      </div>
    </div>
  </div>
</nav>

<!-- Content -->
<div class="container-fluid mt-4">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-3 col-sm-12 mb-4">
      <div style="background-color: #fff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
        <nav class="nav flex-column">
          <a class="nav-link" href="{{ route('nguoidung.tt-canhan') }}"style="color: #333; font-weight: 500; border-left: 4px solid transparent; padding: 12px 16px;">Thông tin cá nhân</a>
          <a class="nav-link" href="{{ route('nguoidung.ql-bieumau') }}" style="color: #333; font-weight: 500; border-left: 4px solid transparent; padding: 12px 16px;">Danh sách biểu mẫu</a>
          <a class="nav-link" href="{{ route('nguoidung.ql-danhsach') }}" style="color: #333; font-weight: 500; border-left: 4px solid transparent; padding: 12px 16px;">Danh sách điểm danh</a>
         <a class="nav-link active" href="{{ route('nguoidung.ls-diemdanh') }}" style="color: #0d6efd; background-color: #e9f1ff; font-weight: 500; border-left: 4px solid #0d6efd; padding: 12px 16px;">Lịch sử điểm danh</a>
        
          
        </nav>
      </div>
    </div>

    <!-- Main content -->
    <div class="col-md-9 col-sm-12">
      <div style="background-color: white; border-radius: 10px; padding: 30px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
        <h4 class="mb-3">Lịch sử điểm danh</h4>
        <div class="table-responsive">
          <table class="table table-bordered align-middle text-center" id="attendanceTable">
            <thead class="table-light">
              <tr>
                <th>Tiêu đề điểm danh</th>
                <th>Người tạo</th>
                <th>Thời gian</th>
                <th>Ngày</th>
              </tr>
            </thead>
            <tbody>
              <tr><td>Điểm danh lớp sáng</td><td>Nguyễn Văn A</td><td>07:30</td><td>01/06/2025</td></tr>
              <tr><td>Buổi học chiều</td><td>Trần Thị B</td><td>13:00</td><td>02/06/2025</td></tr>
              <tr><td>Kiểm tra cuối kỳ</td><td>Lê Văn C</td><td>09:00</td><td>03/06/2025</td></tr>
              <tr><td>Buổi học đặc biệt</td><td>Phạm Văn D</td><td>17:00</td><td>04/06/2025</td></tr>
              <tr><td>Học nhóm</td><td>Nguyễn Thị E</td><td>20:00</td><td>05/06/2025</td></tr>
              <tr><td>Buổi thuyết trình</td><td>Trương Văn F</td><td>10:30</td><td>06/06/2025</td></tr>
              <tr><td>Buổi học nâng cao</td><td>Ngô Thị G</td><td>14:00</td><td>07/06/2025</td></tr>
              <tr><td>Thực hành nhóm</td><td>Phan Văn H</td><td>16:00</td><td>08/06/2025</td></tr>
            </tbody>
          </table>
        </div>

        <nav aria-label="Phân trang">
          <ul class="pagination justify-content-end mt-3" id="pagination"></ul>
        </nav>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="text-white text-center py-5 mt-5" style="background: #1c1f3c;">
  <div class="d-flex flex-column align-items-center">
    <div class="d-flex align-items-center mb-2">
      <div style="width: 40px; height: 40px; background-color: #2dc5c5; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: white; margin-right: 10px;">LOGO</div>
      <span>Dự án lập trình web</span>
    </div>
    <p class="mt-2">Bạn có hài lòng khi sử dụng trang web</p>
    <form class="d-flex justify-content-center gap-2 mb-3">
      <input type="text" class="form-control w-200" placeholder="Đánh giá">
      <button type="submit" class="btn btn-info text-white">Gửi</button>
    </form>
    <small>Lý Thanh Duy | Võ Thành Đăng Khoa</small>
    <small>Khóa học 2022 – 2025.</small>
  </div>
</footer>

<!-- Scripts -->
<script>
  function toggleMenu() {
    const menu = document.getElementById("avatarDropdown");
    menu.style.display = menu.style.display === "block" ? "none" : "block";
  }

  window.onclick = function (event) {
    if (!event.target.closest('.avatar-menu')) {
      const menu = document.getElementById("avatarDropdown");
      if (menu) menu.style.display = "none";
    }
  }

  // Phân trang
  const rowsPerPage = 7;
  const table = document.getElementById("attendanceTable");
  const tbody = table.querySelector("tbody");
  const pagination = document.getElementById("pagination");

  function displayPage(page) {
    const rows = tbody.querySelectorAll("tr");
    const totalPages = Math.ceil(rows.length / rowsPerPage);
    const start = (page - 1) * rowsPerPage;
    const end = start + rowsPerPage;

    rows.forEach((row, index) => {
      row.style.display = index >= start && index < end ? "" : "none";
    });

    pagination.innerHTML = "";
    for (let i = 1; i <= totalPages; i++) {
      const li = document.createElement("li");
      li.className = `page-item ${i === page ? "active" : ""}`;
      const a = document.createElement("a");
      a.className = "page-link";
      a.href = "#";
      a.textContent = i;
      a.onclick = function (e) {
        e.preventDefault();
        displayPage(i);
      };
      li.appendChild(a);
      pagination.appendChild(li);
    }
  }

  displayPage(1);
</script>

</body>
</html>
