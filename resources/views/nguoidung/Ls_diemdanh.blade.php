@extends('layout.user')

@section('title', 'Lịch sử điểm danh')
@section('page-title', 'Lịch sử điểm danh')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <div class="d-flex align-items-center justify-content-between mb-3">
        <input type="text" class="form-control" id="searchInput" placeholder="🔍 Tìm kiếm lịch sử..."
            style="border: none; background: #efefef; border-radius: 8px; padding: 8px 16px; width: 300px;">
        <label style="font-size: 14px;">
            Hiển thị:
            <select id="rowsPerPageSelect" style="padding: 6px 12px; border-radius: 6px;">
                <option value="7"selected>7 dòng</option>
                <option value="15">15 dòng</option>
                <option value="20">20 dòng</option>
            </select>
        </label>
    </div>

    <div class="table-responsive">
        <table class="table align-middle text-center" id="dd-table">
            <thead class="table-light">
                <tr>
                    <th>Tiêu đề</th>
                    <th>Người tạo</th>
                    <th>Thời gian</th>
                    <th>Ngày</th>
                    <th>Thiết bị</th>
                    <th>Định vị</th>
                </tr>
            </thead>
            <tbody id="dd-body"></tbody>
        </table>
    </div>

    <nav aria-label="Phân trang">
        <ul class="pagination justify-content-center mt-3" id="pagination"></ul>
    </nav>
@endsection

@push('scripts')
    <script>
        let rawData = @json($lichSu);
        let data = rawData.map(dd => ({
            tieu_de: dd.bieu_mau?.tieu_de ?? '---',
            nguoi_tao: dd.bieu_mau?.tai_khoan?.ho_ten ?? '---',
            thoi_gian: dd.thoi_gian_diem_danh ? new Date(dd.thoi_gian_diem_danh).toLocaleTimeString('vi-VN', {
                hour: '2-digit',
                minute: '2-digit'
            }) : '',
            ngay: dd.thoi_gian_diem_danh ? new Date(dd.thoi_gian_diem_danh).toLocaleDateString('vi-VN') : '',
            thiet_bi: dd.thiet_bi_diem_danh ?? '',
            dinh_vi: dd.dinh_vi_thiet_bi ?? ''
        }));

        let rowsPerPage = 7;
        let currentPage = 1;
        let searchValue = '';
        let filteredData = [...data];

        function renderTable() {
            const tbody = document.getElementById("dd-body");
            tbody.innerHTML = "";
            const start = (currentPage - 1) * rowsPerPage;
            const rows = filteredData.slice(start, start + rowsPerPage);

            if (rows.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="text-center py-3">Không tìm thấy dữ liệu</td></tr>`;
                return;
            }

            rows.forEach(row => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                <td>${row.tieu_de}</td>
                <td>${row.nguoi_tao}</td>
                <td>${row.thoi_gian}</td>
                <td>${row.ngay}</td>
                <td>${row.thiet_bi}</td>
                <td>${row.dinh_vi}</td>
            `;
                tbody.appendChild(tr);
            });
        }

        function renderPagination() {
            const pagination = document.getElementById("pagination");
            pagination.innerHTML = "";
            const pageCount = Math.ceil(filteredData.length / rowsPerPage);

            for (let i = 1; i <= pageCount; i++) {
                const li = document.createElement("li");
                li.className = `page-item ${i === currentPage ? "active" : ""}`;
                const a = document.createElement("a");
                a.className = "page-link";
                a.href = "#";
                a.textContent = i;
                a.onclick = e => {
                    e.preventDefault();
                    currentPage = i;
                    renderTable();
                    renderPagination();
                };
                li.appendChild(a);
                pagination.appendChild(li);
            }
        }

        function applySearch(keyword) {
            searchValue = keyword.toLowerCase();
            filteredData = data.filter(row =>
                row.tieu_de.toLowerCase().includes(searchValue) ||
                row.nguoi_tao.toLowerCase().includes(searchValue) ||
                row.thiet_bi.toLowerCase().includes(searchValue) ||
                row.dinh_vi.toLowerCase().includes(searchValue) ||
                row.ngay.includes(searchValue)
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
