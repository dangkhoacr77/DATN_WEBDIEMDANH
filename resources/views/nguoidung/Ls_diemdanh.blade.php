@extends('layout/user')

@section('title', 'Lịch sử điểm danh')
@section('page-title', 'Lịch sử điểm danh')

@section('content')
<div class="table-responsive">
  <table class="table table-bordered align-middle text-center">
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
@endsection

@push('scripts')
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
@endpush

