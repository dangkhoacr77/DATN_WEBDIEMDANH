@extends('layout/user')

@section('title', 'Quản lý biểu mẫu')
@section('page-title', 'Danh sách biểu mẫu')

@section('content')
  <input
    type="text"
    class="form-control mt-n2 mb-3"
    id="searchInput"
    onkeyup="searchForm()"
    placeholder="🔍 Tìm kiếm biểu mẫu..."
    style="border: none; background: #efefef; border-radius: 8px; padding: 8px 16px; width: 300px;"
  >

  <div class="table-responsive">
    <table class="table align-middle text-center" id="formTable">
      <thead class="table-light">
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
    <ul class="pagination justify-content-end mt-3" id="pagination"></ul>
  </nav>
@endsection

@push('scripts')
<script>
  // Ẩn/hiện dropdown avatar
  function toggleMenu() {
    const menu = document.getElementById("avatarDropdown");
    menu.style.display = menu.style.display === "block" ? "none" : "block";
  }
  window.onclick = event => {
    if (!event.target.closest('.avatar-menu')) {
      const menu = document.getElementById("avatarDropdown");
      if (menu) menu.style.display = "none";
    }
  };

  // Chọn/bỏ chọn tất cả
  function toggleAll(master) {
    document.querySelectorAll(".row-checkbox")
      .forEach(cb => cb.checked = master.checked);
  }

  // Xóa các dòng đã chọn
  function deleteSelectedRows() {
    const selected = document.querySelectorAll(".row-checkbox:checked");
    if (!selected.length) {
      return alert("Bạn chưa chọn dòng nào để xóa.");
    }
    if (confirm("Bạn có chắc chắn muốn xóa các dòng đã chọn không?")) {
      selected.forEach(cb => cb.closest("tr").remove());
      displayPage(1);
    }
  }

  // Tìm kiếm
  function searchForm() {
    const q = document.getElementById("searchInput").value.toLowerCase();
    document.querySelectorAll("#formTable tbody tr")
      .forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? "" : "none";
      });
  }

  // Phân trang
  const rowsPerPage = 7;
  const tbody = document.querySelector("#formTable tbody");
  const pagination = document.getElementById("pagination");
  function displayPage(page) {
    const rows = tbody.querySelectorAll("tr");
    const totalPages = Math.ceil(rows.length / rowsPerPage);
    const start = (page - 1) * rowsPerPage;
    rows.forEach((r, i) => {
      r.style.display = (i >= start && i < start + rowsPerPage) ? "" : "none";
    });
    pagination.innerHTML = "";
    for (let i = 1; i <= totalPages; i++) {
      const li = document.createElement("li");
      li.className = `page-item ${i === page ? "active" : ""}`;
      const a = document.createElement("a");
      a.className = "page-link";
      a.href = "#";
      a.textContent = i;
      a.onclick = e => { e.preventDefault(); displayPage(i); };
      li.appendChild(a);
      pagination.appendChild(li);
    }
  }
  displayPage(1);
</script>
@endpush
