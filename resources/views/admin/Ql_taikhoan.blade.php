@extends('layout.admin')

@section('title', 'Quản lý Tài khoản')
@section('page-title', 'Quản lý tài khoản')

@section('content')
    <div style="background: white; border-radius: 16px; padding: 40px; width: 95%; margin: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <input id="searchInput" style="width: 240px; border-radius: 12px; border: 1px solid #ddd; padding: 10px 14px;"
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

        <div id="pagination" style="display: flex; justify-content: center; gap: 8px; margin-top: 24px; flex-wrap: wrap;">
        </div>
    </div>

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
                'ngay_tao' => $tk->ngay_tao,
                'trang_thai' => $tk->trang_thai == 1 ? 'Hoạt động' : 'Khóa',
            ];
        });
    @endphp

    <script>
        let rowsPerPage = 15;
        let currentPage = 1;
        let searchValue = '';
const loaiTaiKhoanList = ['admin', 'nguoi_tao_form', 'nguoi_diem_danh'];
        const trangThaiList = ['Hoạt động', 'Khóa'];

        let data = @json($mappedTaiKhoans);
        data = data.map(tk => ({
            ...tk,
            isEditing: false
        }));
        let filteredData = [...data];

        function renderTable() {
            const tbody = document.getElementById("account-body");
            tbody.innerHTML = "";
            const start = (currentPage - 1) * rowsPerPage;
            const rows = filteredData.slice(start, start + rowsPerPage);

            if (rows.length === 0) {
                tbody.innerHTML =
                    `<tr><td colspan="7" style="text-align:center; padding:12px 16px;">Không tìm thấy dữ liệu</td></tr>`;
                return;
            }

            rows.forEach((row, i) => {
                const tr = document.createElement("tr");
                const globalIdx = start + i;

                if (row.isEditing) {
                    tr.innerHTML = `
                        <td style='padding:12px 16px;'>${row.ho_ten}</td>
                        <td style='padding:12px 16px;'>
                            <select id='edit-role-${globalIdx}' style='padding: 4px 8px; border-radius: 4px;'>
                                ${loaiTaiKhoanList.map(role => `<option value='${role}' ${row.loai_tai_khoan === role ? 'selected' : ''}>${role}</option>`).join('')}
                            </select>
                        </td>
                        <td style='padding:12px 16px;'>${row.so_dien_thoai}</td>
                        <td style='padding:12px 16px;'>${row.mail}</td>
                        <td style='padding:12px 16px;'>${row.ngay_tao}</td>
                        <td style='padding:12px 16px;'>
                            <select id='edit-status-${globalIdx}' style='padding: 4px 8px; border-radius: 4px;'>
                                ${trangThaiList.map(st => `<option value='${st}' ${row.trang_thai === st ? 'selected' : ''}>${st}</option>`).join('')}
                            </select>
                        </td>
                        <td style='padding:12px 16px;'>
                            <button onclick='saveRow(${globalIdx}, ${JSON.stringify(row.ma_tai_khoan)})' style='padding: 6px 12px; background: #22c55e; border: none; border-radius: 6px; color: white; cursor: pointer;'>Cập nhật</button>
                        </td>
                    `;
                } else {
                    tr.innerHTML = `
                        <td style='padding:12px 16px;'>${row.ho_ten}</td>
                        <td style='padding:12px 16px;'>${row.loai_tai_khoan}</td>
                        <td style='padding:12px 16px;'>${row.so_dien_thoai}</td>
                        <td style='padding:12px 16px;'>${row.mail}</td>
                        <td style='padding:12px 16px;'>${row.ngay_tao}</td>
<td style='padding:12px 16px;'>${row.trang_thai}</td>
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
                row.ho_ten.toLowerCase().includes(searchValue) ||
                row.mail.toLowerCase().includes(searchValue) ||
                row.so_dien_thoai.toLowerCase().includes(searchValue) ||
                row.loai_tai_khoan.toLowerCase().includes(searchValue) || // ✅ thêm dòng này
                row.trang_thai.toLowerCase().includes(searchValue) ||
                row.ngay_tao.toLowerCase().includes(searchValue)
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