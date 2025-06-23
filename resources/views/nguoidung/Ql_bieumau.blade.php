<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý biểu mẫu</title>
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

<!-- Nội dung quản lý biểu mẫu -->
<div class="container-fluid mt-4">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-3 col-sm-12 mb-4">
      <div style="background-color: #fff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
        <nav class="nav flex-column">
          <a class="nav-link" href="{{ route('nguoidung.tt-canhan') }}"style="color: #333; font-weight: 500; border-left: 4px solid transparent; padding: 12px 16px;">Thông tin cá nhân</a>
          <a class="nav-link active" href="{{ route('nguoidung.ql-bieumau') }}" style="color: #0d6efd; background-color: #e9f1ff; font-weight: 500; border-left: 4px solid #0d6efd; padding: 12px 16px;">Danh sách biểu mẫu</a>
          <a class="nav-link" href="{{ route('nguoidung.ql-danhsach') }}" style="color: #333; font-weight: 500; border-left: 4px solid transparent; padding: 12px 16px;">Danh sách điểm danh</a>
          <a class="nav-link" href="{{ route('nguoidung.ls-diemdanh') }}" style="color: #333; font-weight: 500; border-left: 4px solid transparent; padding: 12px 16px;">Lịch sử điểm danh</a>
        </nav>
      </div>
    </div>

    <!-- Main content -->
    <div class="col-md-9 col-sm-12">
      <div style="background-color: white; border-radius: 10px; padding: 30px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h4 class="mb-0">Danh sách biểu mẫu</h4>
          <button class="btn btn-danger btn-sm" onclick="deleteSelectedRows()">🗑 Xóa</button>
        </div>

        <input type="text" class="form-control mb-3" id="searchInput" onkeyup="searchForm()" placeholder="🔍 Tìm kiếm biểu mẫu..." style="border: none; background: #efefef; border-radius: 8px; padding: 8px 16px; width: 300px;">

        <div class="table-responsive">
          <table class="table align-middle text-center" id="formTable">
            <thead>
              <tr>
                <th><input type="checkbox" id="selectAll" onclick="toggleAll(this)"></th>
                <th>Tiêu đề</th>
                <th>Màu</th>
                <th>Hình ảnh</th>
                <th>Ngày tạo</th>
              </tr>
            </thead>
            <tbody>
              <tr><td><input type="checkbox" class="row-checkbox"></td><td>Biểu mẫu 1</td><td>Xanh</td><td>Hình ảnh</td><td>01/01/2025</td></tr>
              <tr><td><input type="checkbox" class="row-checkbox"></td><td>Biểu mẫu 2</td><td>Đỏ</td><td>Hình ảnh</td><td>02/01/2025</td></tr>
              <tr><td><input type="checkbox" class="row-checkbox"></td><td>Biểu mẫu 3</td><td>Vàng</td><td>Hình ảnh</td><td>03/01/2025</td></tr>
              <tr><td><input type="checkbox" class="row-checkbox"></td><td>Biểu mẫu 4</td><td>Tím</td><td>Hình ảnh</td><td>04/01/2025</td></tr>
              <tr><td><input type="checkbox" class="row-checkbox"></td><td>Biểu mẫu 5</td><td>Xanh lá</td><td>Hình ảnh</td><td>05/01/2025</td></tr>
              <tr><td><input type="checkbox" class="row-checkbox"></td><td>Biểu mẫu 6</td><td>Cam</td><td>Hình ảnh</td><td>06/01/2025</td></tr>
              <tr><td><input type="checkbox" class="row-checkbox"></td><td>Biểu mẫu 7</td><td>Hồng</td><td>Hình ảnh</td><td>07/01/2025</td></tr>
              <tr><td><input type="checkbox" class="row-checkbox"></td><td>Biểu mẫu 8</td><td>Xám</td><td>Hình ảnh</td><td>08/01/2025</td></tr>
            </tbody>
          </table>
        </div>

        <nav aria-label="Phân trang">
          <ul class="pagination mt-3" id="pagination" style="justify-content: end;"></ul>
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

  function toggleAll(master) {
    const checkboxes = document.querySelectorAll(".row-checkbox");
    checkboxes.forEach(cb => cb.checked = master.checked);
  }

  function deleteSelectedRows() {
    const selected = document.querySelectorAll(".row-checkbox:checked");
    if (selected.length === 0) {
      alert("Bạn chưa chọn dòng nào để xóa.");
      return;
    }
    if (confirm("Bạn có chắc chắn muốn xóa các dòng đã chọn không?")) {
      selected.forEach(cb => cb.closest("tr").remove());
      updatePagination();
    }
  }

  function searchForm() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const rows = document.querySelectorAll("#formTable tbody tr");
    rows.forEach(row => {
      row.style.display = row.textContent.toLowerCase().includes(input) ? "" : "none";
    });
  }

  const rowsPerPage = 7;
  const table = document.getElementById("formTable");
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
      const link = document.createElement("a");
      link.className = "page-link";
      link.href = "#";
      link.textContent = i;
      link.onclick = function (e) {
        e.preventDefault();
        displayPage(i);
      };
      li.appendChild(link);
      pagination.appendChild(li);
    }
  }

  function updatePagination() {
    displayPage(1);
  }

  displayPage(1);
</script>

</body>
</html>
