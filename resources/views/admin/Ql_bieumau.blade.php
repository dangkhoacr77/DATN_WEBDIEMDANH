<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Quản lý Biểu mẫu</title>
</head>
<body style="margin: 0; font-family: 'Segoe UI', sans-serif; background-color: #f5f7fa;">
<div style="display: flex; min-height: 100vh;">
  <!-- Sidebar -->
  <div style="width: 220px; background: white; padding: 30px 0; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column;">
    <div style="text-align: center; font-weight: bold; font-size: 22px; margin-bottom: 40px;">Logo</div>
    <div style="display: flex; flex-direction: column;">
       <div onclick="window.location.href='{{ route('admin.thong-ke') }}'" style="padding: 12px 24px; display: flex; align-items: center; gap: 10px; font-size: 14px; color: #333; cursor: pointer;">🏠 Thống kê</div>
       <div onclick="window.location.href='{{ route('admin.ql-bieumau') }}'" style="padding: 12px 24px; display: flex; align-items: center; gap: 10px; font-size: 14px; color: #0047ff; background-color: #eef3ff; font-weight: bold; cursor: pointer;">📄 Biểu mẫu</div>
       <div onclick="window.location.href='{{ route('admin.ql-taikhoan') }}'" style="padding: 12px 24px; display: flex; align-items: center; gap: 10px; font-size: 14px; color: #333; cursor: pointer;">👤 Tài Khoản</div>
       <div onclick="window.location.href='{{ route('admin.tt-canhan') }}'" style="padding: 12px 24px; display: flex; align-items: center; gap: 10px; font-size: 14px; color: #333; cursor: pointer;">⚙️ Thông tin cá nhân</div>
    </div>
  </div>

  <!-- Main Content -->
  <div style="flex: 1; display: flex; flex-direction: column;">
    <!-- Header -->
    <div style="background: #7da4ff; height: 72px; padding: 0 40px; color: white; font-weight: bold; display: flex; justify-content: space-between; align-items: center;">
      <span>Quản lý biểu mẫu</span>
      <div style="width: 50px; height: 50px; background: #ccc; border-radius: 50%;"></div>
    </div>

    <!-- Content -->
    <div style="padding: 40px;">
      <div style="background: white; border-radius: 16px; padding: 40px; max-width: 100%; width: 95%; margin: auto;">
        <div style="margin-bottom: 12px;">
          <input id="searchInput" style="width: 240px; border-radius: 12px; border: 1px solid #ddd; padding: 10px 14px;" type="text" placeholder="🔍 Tìm kiếm...">
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
          <label style="font-size: 14px; display:flex; align-items:center; gap:8px;">
            Hiển thị:
            <select id="rowsPerPageSelect" style="padding: 6px 12px; border-radius: 6px;">
              <option value="7">7 dòng</option>
              <option value="10" selected>10 dòng</option>
              <option value="20">20 dòng</option>
            </select>
          </label>
          <button id="deleteSelected" style="padding: 8px 16px; background: #f87171; border: none; color: white; border-radius: 6px; cursor: pointer;" disabled>🗑️ Xóa đã chọn</button>
        </div>

        <!-- Table -->
        <div id="table-container" style="overflow-x: auto;">
          <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <thead>
              <tr>
                <th style="padding: 12px; text-align: center;"><input type="checkbox" id="selectAll"></th>
                <th style="text-align:left; padding: 12px;">Tiêu đề</th>
                <th style="text-align:left; padding: 12px;">Màu</th>
                <th style="text-align:left; padding: 12px;">Ngày tạo</th>
              </tr>
            </thead>
            <tbody id="table-body"></tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div id="pagination" style="display:flex; justify-content:center; margin-top: 24px; flex-wrap: wrap; gap: 8px;"></div>
      </div>
    </div>
  </div>
</div>

<!-- Success Message -->
<div id="success-message" style="display: none; position: fixed; top: 20px; right: 20px; background: #4ade80; color: white; padding: 12px 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); font-weight: 500; z-index: 999;">✅ Xóa thành công!</div>

<script>
  let rowsPerPage = 10;
  let currentPage = 1;
  const forms = Array.from({length: 23}, (_, i) => ({
    title: `Biểu mẫu số ${i+1}`,
    color: ["Xanh", "Đỏ", "Tím"][i % 3],
    date: "19/06/2025"
  }));

  function showSuccessMessage() {
    const msg = document.getElementById("success-message");
    msg.style.display = "block";
    setTimeout(() => {
      msg.style.display = "none";
    }, 2000);
  }

  function confirmRemoveForm(index) {
    if (confirm("Bạn có chắc chắn muốn xóa biểu mẫu này?")) {
      forms.splice(index, 1);
      renderTable();
      renderPagination();
      showSuccessMessage();
    }
  }

  function updateDeleteButton() {
    const anyChecked = Array.from(document.querySelectorAll(".row-checkbox")).some(cb => cb.checked);
    document.getElementById("deleteSelected").disabled = !anyChecked;
  }

  function updateSelectAllState() {
    const boxes = Array.from(document.querySelectorAll(".row-checkbox"));
    document.getElementById("selectAll").checked = boxes.length && boxes.every(cb => cb.checked);
  }

  function deleteSelected() {
    if (confirm("Bạn có chắc chắn muốn xóa các biểu mẫu đã chọn?")) {
      const indices = Array.from(document.querySelectorAll(".row-checkbox"))
        .filter(cb => cb.checked)
        .map(cb => parseInt(cb.dataset.index))
        .sort((a, b) => b - a);

      indices.forEach(idx => forms.splice(idx, 1));
      currentPage = 1;
      renderTable();
      renderPagination();
      updateDeleteButton();
      showSuccessMessage();
    }
  }

  function renderTable() {
    const tbody = document.getElementById("table-body");
    tbody.innerHTML = "";
    const start = (currentPage - 1) * rowsPerPage;
    const dataSlice = forms.slice(start, start + rowsPerPage);

    dataSlice.forEach((f, idx) => {
      const globalIdx = start + idx;
      const row = document.createElement("tr");
      row.innerHTML = `
        <td style="padding: 12px; text-align: center;"><input type="checkbox" class="row-checkbox" data-index="${globalIdx}"></td>
        <td style="padding: 12px;">${f.title}</td>
        <td style="padding: 12px;">${f.color}</td>
        <td style="padding: 12px;">${f.date}</td>
      `;
      row.querySelector(".row-checkbox").onchange = () => {
        updateSelectAllState();
        updateDeleteButton();
      };
      tbody.appendChild(row);
    });
    updateSelectAllState();
  }

  function renderPagination() {
    const pageCount = Math.ceil(forms.length / rowsPerPage);
    const container = document.getElementById("pagination");
    container.innerHTML = "";

    for (let i = 1; i <= pageCount; i++) {
      const btn = document.createElement("button");
      btn.textContent = i;
      btn.style.padding = "6px 12px";
      btn.style.border = "none";
      btn.style.borderRadius = "6px";
      btn.style.cursor = "pointer";
      btn.style.background = i === currentPage ? "#3b82f6" : "#e5e7eb";
      btn.style.color = i === currentPage ? "white" : "black";
      btn.onclick = () => {
        currentPage = i;
        renderTable();
        renderPagination();
      };
      container.appendChild(btn);
    }
  }

  document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("rowsPerPageSelect").onchange = e => {
      rowsPerPage = parseInt(e.target.value);
      currentPage = 1;
      renderTable();
      renderPagination();
    };
    document.getElementById("selectAll").onchange = e => {
      const checked = e.target.checked;
      document.querySelectorAll(".row-checkbox").forEach(cb => (cb.checked = checked));
      updateDeleteButton();
    };
    document.getElementById("deleteSelected").onclick = deleteSelected;
    renderTable();
    renderPagination();
  });
</script>
</body>
</html>
