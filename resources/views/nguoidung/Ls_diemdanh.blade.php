@extends('layout.user')

@section('title', 'Lịch sử điểm danh')
@section('page-title', 'Lịch sử điểm danh')

@section('content')
    <div style="background: white; border-radius: 16px; padding: 40px; max-width: 100%; width: 95%; margin: auto;">
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

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <thead>
                <tr>
                    <th style="text-align:left; padding: 12px;">Tiêu đề</th>
                    <th style="text-align:left; padding: 12px;">Người tạo</th>
                    <th style="text-align:left; padding: 12px;">Thời gian</th>
                    <th style="text-align:left; padding: 12px;">Ngày</th>
                    <th style="text-align:left; padding: 12px;">Thiết bị</th>
                    <th style="text-align:left; padding: 12px;">Định vị</th>
                </tr>
                </thead>
                <tbody id="dd-body"></tbody>
            </table>
        </div>

        <div id="pagination" style="display: flex; justify-content: center; gap: 8px; margin-top: 24px; flex-wrap: wrap;"></div>
    </div>
@endsection

@push('scripts')
<script>
    let rawData = @json($lichSu);
    let data = rawData.map(dd => ({
        tieu_de: dd.bieu_mau?.tieu_de ?? '---',
        nguoi_tao: dd.bieu_mau?.tai_khoan?.ho_ten ?? '---',
        thoi_gian: dd.thoi_gian_diem_danh ? new Date(dd.thoi_gian_diem_danh).toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' }) : '',
        ngay: dd.thoi_gian_diem_danh ? new Date(dd.thoi_gian_diem_danh).toLocaleDateString('vi-VN') : '',
        thiet_bi: dd.thiet_bi_diem_danh ?? '',
        dinh_vi: dd.dinh_vi_thiet_bi ?? ''
    }));

    let rowsPerPage = 15;
    let currentPage = 1;
    let searchValue = '';
    let filteredData = [...data];

    function renderTable() {
        const tbody = document.getElementById("dd-body");
        tbody.innerHTML = "";
        const start = (currentPage - 1) * rowsPerPage;
        const rows = filteredData.slice(start, start + rowsPerPage);

        if (rows.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" style="text-align:center; padding:12px;">Không tìm thấy dữ liệu</td></tr>`;
            return;
        }

        rows.forEach(row => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td style='padding:12px;'>${row.tieu_de}</td>
                <td style='padding:12px;'>${row.nguoi_tao}</td>
                <td style='padding:12px;'>${row.thoi_gian}</td>
                <td style='padding:12px;'>${row.ngay}</td>
                <td style='padding:12px;'>${row.thiet_bi}</td>
                <td style='padding:12px;'>${row.dinh_vi}</td>
            `;
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
