@extends('layout.admin')

@section('title', 'Quản lý Tài khoản')

@section('page-title', 'Quản lý tài khoản')

@section('content')
<div style="background: white; border-radius: 16px; padding: 40px; width: 95%; margin: auto;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <input id="searchInput"
            style="width: 240px; border-radius: 12px; border: 1px solid #ddd; padding: 10px 14px;"
            type="text" placeholder="🔍 Tìm kiếm">
        <label style="font-size: 14px;">
            Hiển thị:
            <select id="rowsPerPageSelect" style="padding: 6px 12px; border-radius: 6px;">
                <option value="7">7 dòng</option>
                <option value="15" selected>15 dòng</option>
                <option value="20">20 dòng</option>
            </select>
        </label>
    </div>

    <!-- Table -->
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <thead>
                <tr>
                    <th style="text-align:left; padding: 12px 16px;">Tên</th>
                    <th style="text-align:left; padding: 12px 16px;">Loại</th>
                    <th style="text-align:left; padding: 12px 16px;">SĐT</th>
                    <th style="text-align:left; padding: 12px 16px;">Email</th>
                    <th style="text-align:left; padding: 12px 16px;">Ngày tạo</th>
                    <th style="text-align:left; padding: 12px 16px;">Trạng thái</th>
                    <th style="text-align:left; padding: 12px 16px;">Hành động</th>
                </tr>
            </thead>
            <tbody id="account-body"></tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div id="pagination"
        style="display: flex; justify-content: center; gap: 8px; margin-top: 24px; flex-wrap: wrap;">
    </div>
</div>

<!-- Alert Notification -->
<div id="alertBox"
    style="display: none; position: fixed; top: 20px; right: 20px; background-color: #22c55e; color: white; padding: 12px 24px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.2); font-size: 14px; z-index: 1000;">
    ✅ Cập nhật thành công!
</div>
@endsection

@push('scripts')
<script>
    let rowsPerPage = 15;
    let currentPage = 1;
    let searchValue = '';

    const data = Array.from({ length: 23 }, (_, i) => ({
        name: `Người dùng ${i+1}`,
        role: i % 2 === 0 ? 'Admin' : 'Người dùng',
        phone: '0900000000',
        email: `user${i+1}@gmail.com`,
        created: '19/06/2025',
        status: i % 3 === 0 ? 'Khóa' : 'Hoạt động',
        isEditing: false
    }));

    let filteredData = [...data];

    function renderTable() {
        const tbody = document.getElementById("account-body");
        tbody.innerHTML = "";
        const start = (currentPage - 1) * rowsPerPage;
        const rows = filteredData.slice(start, start + rowsPerPage);

        if (rows.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; padding:12px 16px;">Không tìm thấy dữ liệu</td></tr>`;
            return;
        }

        rows.forEach((row, i) => {
            const tr = document.createElement("tr");
            const globalIdx = start + i;

            if (row.isEditing) {
                tr.innerHTML = `
                    <td style='padding:12px 16px;'>${row.name}</td>
                    <td style='padding:12px 16px;'>
                      <select id='edit-role-${globalIdx}' style='padding: 4px 8px; border-radius: 4px;'>
                        <option ${row.role === 'Admin' ? 'selected' : ''}>Admin</option>
                        <option ${row.role === 'Người dùng tạo form' ? 'selected' : ''}>Người dùng tạo form</option>
                        <option ${row.role === 'Người dùng điểm danh' ? 'selected' : ''}>Người dùng điểm danh</option>
                      </select>
                    </td>
                    <td style='padding:12px 16px;'>${row.phone}</td>
                    <td style='padding:12px 16px;'>${row.email}</td>
                    <td style='padding:12px 16px;'>${row.created}</td>
                    <td style='padding:12px 16px;'>
                      <select id='edit-status-${globalIdx}' style='padding: 4px 8px; border-radius: 4px;'>
                        <option ${row.status === 'Hoạt động' ? 'selected' : ''}>Hoạt động</option>
                        <option ${row.status === 'Khóa' ? 'selected' : ''}>Khóa</option>
                      </select>
                    </td>
                    <td style='padding:12px 16px;'>
                      <button onclick='saveRow(${globalIdx})' style='padding: 6px 12px; background: #22c55e; border: none; border-radius: 6px; color: white; cursor: pointer;'>Cập nhật</button>
                    </td>
                `;
            } else {
                tr.innerHTML = `
                    <td style='padding:12px 16px;'>${row.name}</td>
                    <td style='padding:12px 16px;'>${row.role}</td>
                    <td style='padding:12px 16px;'>${row.phone}</td>
                    <td style='padding:12px 16px;'>${row.email}</td>
                    <td style='padding:12px 16px;'>${row.created}</td>
                    <td style='padding:12px 16px;'>${row.status}</td>
                    <td style='padding:12px 16px;'>
                      <button onclick='editRow(${globalIdx})' style='padding: 6px 12px; background: #60a5fa; border: none; border-radius: 6px; color: white; cursor: pointer;'>Sửa</button>
                    </td>
                `;
            }
            tbody.appendChild(tr);
        });
    }

    function editRow(index) {
        data[index].isEditing = true;
        renderTable();
    }

    function saveRow(index) {
        const confirmUpdate = confirm("Bạn có chắc muốn cập nhật thông tin người dùng này?");
        if (!confirmUpdate) return;

        const roleEl = document.getElementById(`edit-role-${index}`);
        const statusEl = document.getElementById(`edit-status-${index}`);
        if (roleEl && statusEl) {
            data[index].role = roleEl.value;
            data[index].status = statusEl.value;
            data[index].isEditing = false;
            renderTable();
            showAlert("✅ Cập nhật thành công!");
        }
    }

    function showAlert(message, duration = 3000) {
        const alertBox = document.getElementById("alertBox");
        alertBox.textContent = message;
        alertBox.style.display = "block";
        setTimeout(() => {
            alertBox.style.display = "none";
        }, duration);
    }

    function renderPagination() {
        const pagination = document.getElementById("pagination");
        pagination.innerHTML = "";
        const pageCount = Math.ceil(filteredData.length / rowsPerPage);

        for (let i = 1; i <= pageCount; i++) {
            const btn = document.createElement("button");
            btn.textContent = i;
            btn.style.border = "none";
            btn.style.background = i === currentPage ? "#4f46e5" : "#f3f3f3";
            btn.style.color = i === currentPage ? "white" : "black";
            btn.style.padding = "6px 12px";
            btn.style.borderRadius = "6px";
            btn.style.cursor = "pointer";
            btn.style.fontSize = "14px";

            btn.addEventListener("click", () => {
                currentPage = i;
                renderTable();
                renderPagination();
            });

            pagination.appendChild(btn);
        }
    }

    function applySearch(keyword) {
        searchValue = keyword.toLowerCase();
        filteredData = data.filter(row =>
            row.name.toLowerCase().includes(searchValue) ||
            row.email.toLowerCase().includes(searchValue) ||
            row.status.toLowerCase().includes(searchValue)
        );
        currentPage = 1;
        renderTable();
        renderPagination();
    }

    document.addEventListener("DOMContentLoaded", () => {
        document.getElementById("rowsPerPageSelect").addEventListener("change", (e) => {
            rowsPerPage = parseInt(e.target.value);
            currentPage = 1;
            renderTable();
            renderPagination();
        });

        document.getElementById("searchInput").addEventListener("input", (e) => {
            applySearch(e.target.value);
        });

        renderTable();
        renderPagination();
    });
</script>
@endpush
