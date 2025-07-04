@extends('layout.admin')

@section('title', 'Quản lý Tài khoản')
@section('page-title', 'Quản lý tài khoản')

@section('content')
    <div style="background: white; border-radius: 16px; padding: 40px; width: 95%; margin: auto;">
        <!-- Thanh tìm kiếm -->
        <div style="margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
            <input id="searchInput"
                   style="width: 240px; border-radius: 12px; border: 1px solid #ddd; padding: 10px 14px;"
                   type="text" placeholder="🔍 Tìm kiếm...">

            <label style="font-size: 14px;">
                Hiển thị:
                <select id="rowsPerPageSelect" style="padding: 6px 12px; border-radius: 6px;">
                    <option value="7" selected>7 dòng</option>
                    <option value="15">15 dòng</option>
                    <option value="20">20 dòng</option>
                </select>
            </label>
        </div>

        <!-- Bảng -->
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 18px;">
                <thead style="background: #f1f5f9;">
                    <tr>
                        <th style="text-align:left; padding: 12px;">Tên</th>
                        <th style="text-align:left; padding: 12px;">Loại</th>
                        <th style="text-align:left; padding: 12px;">SĐT</th>
                        <th style="text-align:left; padding: 12px;">Email</th>
                        <th style="text-align:left; padding: 12px;">Ngày tạo</th>
                        <th style="text-align:left; padding: 12px;">Trạng thái</th>
                        <th style="text-align:left; padding: 12px;">Hành động</th>
                    </tr>
                </thead>
                <tbody id="account-body"></tbody>
            </table>
        </div>

        <!-- Phân trang -->
        <div id="pagination" style="display: flex; justify-content: center; gap: 8px; margin-top: 24px; flex-wrap: wrap;"></div>
    </div>

    <!-- Thông báo -->
    <div id="alertBox"
        style="display: none; position: fixed; top: 20px; right: 20px; background-color: #22c55e; color: white; padding: 12px 24px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.2); font-size: 14px; z-index: 1000;">
        ✅ Cập nhật thành công!
    </div>
@endsection

@push('scripts')
@php
    $mappedTaiKhoans = $taiKhoans->map(function ($tk) {
        return [
            'ma_tai_khoan' => $tk->ma_tai_khoan,
            'ho_ten' => $tk->ho_ten,
            'loai_tai_khoan' => $tk->loai_tai_khoan,
            'so_dien_thoai' => $tk->so_dien_thoai,
            'mail' => $tk->mail,
            'ngay_tao' => \Carbon\Carbon::parse($tk->ngay_tao)->format('d/m/Y'),
            'trang_thai' => $tk->trang_thai == 1 ? 'Hoạt động' : 'Khóa',
        ];
    });
@endphp

<script>
    let rowsPerPage = 7;
    let currentPage = 1;
    let searchValue = '';
    let data = @json($mappedTaiKhoans).map(tk => ({ ...tk, isEditing: false }));
    let filteredData = [...data];

    const loaiTaiKhoanList = ['admin', 'nguoi_dung'];
    const trangThaiList = ['Hoạt động', 'Khóa'];

    function renderTable() {
        const tbody = document.getElementById("account-body");
        tbody.innerHTML = "";
        const start = (currentPage - 1) * rowsPerPage;
        const rows = filteredData.slice(start, start + rowsPerPage);

        if (rows.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; padding: 20px;">&nbsp;</td></tr>`;
            return;
        }

        rows.forEach((row, i) => {
            const globalIdx = start + i;
            const tr = document.createElement("tr");
            tr.style.backgroundColor = "#fff";
            tr.style.borderBottom = "1px solid #eee";

            if (row.isEditing) {
                tr.innerHTML = `
                    <td style="padding:12px;">${row.ho_ten}</td>
                    <td style="padding:12px;">
                        <select id="edit-role-${globalIdx}" style="padding: 6px 12px; border-radius: 6px;">
                            ${loaiTaiKhoanList.map(loai => `<option value="${loai}" ${row.loai_tai_khoan === loai ? 'selected' : ''}>${loai}</option>`).join('')}
                        </select>
                    </td>
                    <td style="padding:12px;">${row.so_dien_thoai}</td>
                    <td style="padding:12px;">${row.mail}</td>
                    <td style="padding:12px;">${row.ngay_tao}</td>
                    <td style="padding:12px;">
                        <select id="edit-status-${globalIdx}" style="padding: 6px 12px; border-radius: 6px;">
                            ${trangThaiList.map(st => `<option value="${st}" ${row.trang_thai === st ? 'selected' : ''}>${st}</option>`).join('')}
                        </select>
                    </td>
                    <td style="padding:12px;">
                        <button onclick="saveRow(${globalIdx}, '${row.ma_tai_khoan}')" style="padding: 6px 12px; background: #22c55e; color: white; border: none; border-radius: 6px;">Cập nhật</button>
                    </td>
                `;
            } else {
                tr.innerHTML = `
                    <td style="padding:12px;">${row.ho_ten}</td>
                    <td style="padding:12px;">${row.loai_tai_khoan}</td>
                    <td style="padding:12px;">${row.so_dien_thoai}</td>
                    <td style="padding:12px;">${row.mail}</td>
                    <td style="padding:12px;">${row.ngay_tao}</td>
                    <td style="padding:12px;">${row.trang_thai}</td>
                    <td style="padding:12px;">
                        <button onclick="editRow(${globalIdx})" style="padding: 6px 12px; background: #3b82f6; color: white; border: none; border-radius: 6px;">Sửa</button>
                    </td>
                `;
            }

            tbody.appendChild(tr);
        });
    }

    function renderPagination() {
        const pagination = document.getElementById("pagination");
        pagination.innerHTML = "";
        const pageCount = Math.ceil(filteredData.length / rowsPerPage);

        for (let i = 1; i <= pageCount; i++) {
            const btn = document.createElement("button");
            btn.textContent = i;
            btn.setAttribute("style", `
                padding: 8px 14px;
                border-radius: 12px;
                background: ${i === currentPage ? '#3b82f6' : '#f1f5f9'};
                color: ${i === currentPage ? 'white' : '#1e293b'};
                font-weight: ${i === currentPage ? '600' : '500'};
                font-size: 14px;
                box-shadow: 0 2px 6px rgba(0,0,0,0.06);
                border: none;
                cursor: pointer;
                transition: all 0.2s ease-in-out;
            `);
            btn.onclick = () => {
                currentPage = i;
                renderTable();
                renderPagination();
            };
            btn.onmouseover = () => {
                if (i !== currentPage) {
                    btn.style.background = "#3b82f6";
                    btn.style.color = "white";
                }
            };
            btn.onmouseout = () => {
                if (i !== currentPage) {
                    btn.style.background = "#f1f5f9";
                    btn.style.color = "#1e293b";
                }
            };
            pagination.appendChild(btn);
        }
    }

    function applySearch(keyword) {
        searchValue = keyword.toLowerCase();
        filteredData = data.filter(row =>
            row.ho_ten.toLowerCase().includes(searchValue) ||
            row.mail.toLowerCase().includes(searchValue) ||
            row.so_dien_thoai.toLowerCase().includes(searchValue) ||
            row.loai_tai_khoan.toLowerCase().includes(searchValue) ||
            row.trang_thai.toLowerCase().includes(searchValue) ||
            row.ngay_tao.toLowerCase().includes(searchValue)
        );
        currentPage = 1;
        renderTable();
        renderPagination();
    }

    function editRow(index) {
        data[index].isEditing = true;
        renderTable();
    }

    function saveRow(index, maTaiKhoan) {
        const confirmUpdate = confirm("⚠️ Bạn có chắc chắn muốn cập nhật thông tin tài khoản này?");
        if (!confirmUpdate) return;

        const roleEl = document.getElementById(`edit-role-${index}`);
        const statusEl = document.getElementById(`edit-status-${index}`);
        const statusValue = statusEl.value === 'Hoạt động' ? 1 : 0;

        fetch(`/admin/ql-taikhoan/${maTaiKhoan}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                loai_tai_khoan: roleEl.value,
                trang_thai: statusValue
            })
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                data[index].loai_tai_khoan = roleEl.value;
                data[index].trang_thai = statusEl.value;
                data[index].isEditing = false;
                showAlert("✅ Cập nhật thành công!");
                renderTable();
            } else {
                showAlert("❌ Cập nhật thất bại!");
            }
        })
        .catch(error => {
            console.error("Lỗi:", error);
            showAlert("❌ Có lỗi xảy ra khi gửi dữ liệu!");
        });
    }

    function showAlert(message, duration = 3000) {
        const alertBox = document.getElementById("alertBox");
        alertBox.textContent = message;
        alertBox.style.display = "block";
        setTimeout(() => alertBox.style.display = "none", duration);
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
