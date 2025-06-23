<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Thông tin cá nhân</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body style="background-color: #f8f9fa;">

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

<!-- Main content -->
<div class="container-fluid mt-4">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-3 col-sm-12 mb-4">
      <div style="background-color: #fff; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.05);">
        <nav class="nav flex-column">
          <a class="nav-link active" href="{{ route('nguoidung.tt-canhan') }}"style="color: #0d6efd; background-color: #e9f1ff; font-weight: 500; border-left: 4px solid #0d6efd; padding: 12px 16px;">Thông tin cá nhân</a>
          <a class="nav-link" href="{{ route('nguoidung.ql-bieumau') }}" style="color: #333; font-weight: 500; border-left: 4px solid transparent; padding: 12px 16px;">Danh sách biểu mẫu</a>
          <a class="nav-link" href="{{ route('nguoidung.ql-danhsach') }}" style="color: #333; font-weight: 500; border-left: 4px solid transparent; padding: 12px 16px;">Danh sách điểm danh</a>
          <a class="nav-link" href="{{ route('nguoidung.ls-diemdanh') }}" style="color: #333; font-weight: 500; border-left: 4px solid transparent; padding: 12px 16px;">Lịch sử điểm danh</a>
        </nav>
      </div>
    </div>

    <!-- Form content -->
    <div class="col-md-9 col-sm-12">
      <div style="background-color: white; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.05); padding: 30px;">
        <h4 class="mb-4">Thông tin cá nhân</h4>
        <form id="infoForm">
          <div class="row mb-3">
            <div class="col-md-6">
              <label style="font-weight: 500;" class="form-label">Họ và tên</label>
              <input type="text" class="form-control" id="name" value="Võ Thành Đăng Khoa" readonly>
            </div>
            <div class="col-md-6">
              <label style="font-weight: 500;" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" value="khoa@example.com" readonly>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <label style="font-weight: 500;" class="form-label">Số điện thoại</label>
              <input type="tel" class="form-control" id="phone" value="0123456789" readonly>
            </div>
            <div class="col-md-6">
              <label style="font-weight: 500;" class="form-label">Ngày tạo</label>
              <input type="text" class="form-control" id="created_at" value="2023-06-01" readonly>
            </div>
          </div>
          <div class="mt-3 text-end">
            <button type="button" class="btn btn-primary" id="editBtn" onclick="toggleEdit()">Cập nhật</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<footer class="text-white text-center py-5 mt-5" style="background: #1c1f3c;">
  <div class="d-flex flex-column align-items-center">
    <div class="d-flex align-items-center mb-2">
      <div style="width: 40px; height: 40px; background-color: #2dc5c5; color: white; border-radius: 50%; font-weight: bold; display: flex; align-items: center; justify-content: center; margin-right: 10px;">LOGO</div>
      <span class="text-white">Dự án lập trình web</span>
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

<script>
  function toggleMenu() {
    const menu = document.getElementById("avatarDropdown");
    menu.style.display = menu.style.display === "block" ? "none" : "block";
  }

  window.onclick = function (event) {
    if (!event.target.closest('.avatar-menu')) {
      const menu = document.getElementById("avatarDropdown");
      if (menu && menu.style.display === "block") {
        menu.style.display = "none";
      }
    }
  }

  let isEditing = false;

  function toggleEdit() {
    const fields = ['name', 'email', 'phone'];
    const btn = document.getElementById('editBtn');

    if (!isEditing) {
      fields.forEach(id => document.getElementById(id).removeAttribute('readonly'));
      btn.textContent = "Lưu";
    } else {
      fields.forEach(id => document.getElementById(id).setAttribute('readonly', true));
      btn.textContent = "Cập nhật";

      console.log("Dữ liệu cập nhật:", {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        created_at: document.getElementById('created_at').value
      });
    }

    isEditing = !isEditing;
  }
</script>

</body>
</html>