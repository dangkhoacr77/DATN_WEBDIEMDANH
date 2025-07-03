@extends('layout.admin')

@section('title', 'Quản lý Biểu mẫu')
@section('page-title', 'Quản lý biểu mẫu')

@section('content')
    <div style="background: white; border-radius: 16px; padding: 40px; max-width: 100%; width: 95%; margin: auto;">
        <!-- Thanh tìm kiếm -->
        <div style="margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
            <input id="searchInput"
                   style="width: 240px; border-radius: 12px; border: 1px solid #ddd; padding: 10px 14px;"
                   type="text" placeholder="🔍 Tìm kiếm...">

            <label style="font-size: 14px;">
                Hiển thị:
                <select id="rowsPerPageSelect" style="padding: 6px 12px; border-radius: 6px;">
                    <option value="7"selected>7 dòng</option>
                    <option value="15">15 dòng</option>
                    <option value="20">20 dòng</option>
                </select>
            </label>
        </div>

        <!-- Table -->
        <div id="table-container" style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <thead style="background: #f1f5f9;">
                    <tr>
                        <th style="text-align:left; padding: 12px;">Tiêu đề</th>
                        <th style="text-align:left; padding: 12px;">Màu</th>
                        <th style="text-align:left; padding: 12px;">Người tạo</th>
                        <th style="text-align:left; padding: 12px;">Ngày tạo</th>
                    </tr>
                </thead>
                <tbody id="form-body"></tbody>
            </table>
        </div>

        <!-- Custom Pagination -->
        <div class="custom-pagination" style="display: flex; justify-content: center; gap: 8px; flex-wrap: wrap; margin-top: 24px;"></div>
    </div>
@endsection

@push('scripts')
@php
    $mappedBieuMaus = $bieuMaus->map(function ($bm) {
        return [
            'tieu_de' => $bm->tieu_de,
            'mau' => $bm->mau,
            'nguoi_tao' => $bm->taiKhoan->ho_ten ?? 'Không rõ',
            'ngay_tao' => \Carbon\Carbon::parse($bm->ngay_tao)->format('d/m/Y'),
        ];
    });
@endphp

<script>
    let formData = @json($mappedBieuMaus);
    let rowsPerPage = 7;
    let currentPage = 1;
    let searchValue = "";

    let filteredData = [...formData];

    function renderFormTable() {
        const tbody = document.getElementById("form-body");
        tbody.innerHTML = "";

        const start = (currentPage - 1) * rowsPerPage;
        const rows = filteredData.slice(start, start + rowsPerPage);

        if (rows.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" style="text-align:center; padding: 20px;">&nbsp;</td></tr>`;
            return;
        }

        rows.forEach(row => {
            const tr = document.createElement("tr");
            tr.setAttribute("style", "background-color: #fff; border-bottom: 1px solid #eee;");
            tr.innerHTML = `
                <td style="padding:12px;">${row.tieu_de}</td>
                <td style="padding:12px;">
                    <span style="display:inline-block;width:12px;height:12px;border-radius:4px;margin-right:6px;background-color:${row.mau}"></span>
                    ${row.mau}
                </td>
                <td style="padding:12px;">${row.nguoi_tao}</td>
                <td style="padding:12px;">${row.ngay_tao}</td>
            `;
            tbody.appendChild(tr);
        });
    }

    function renderPagination() {
        const pagination = document.querySelector(".custom-pagination");
        pagination.innerHTML = "";
        const pageCount = Math.ceil(filteredData.length / rowsPerPage);

        for (let i = 1; i <= pageCount; i++) {
            const btn = document.createElement("button");
            btn.textContent = i;
            btn.className = "page" + (i === currentPage ? " active" : "");
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
            btn.onclick = () => {
                currentPage = i;
                renderFormTable();
                renderPagination();
            };
            pagination.appendChild(btn);
        }
    }

    function applySearch(keyword) {
        searchValue = keyword.toLowerCase();
        filteredData = formData.filter(row =>
            row.tieu_de.toLowerCase().includes(searchValue) ||
            row.mau.toLowerCase().includes(searchValue) ||
            row.nguoi_tao.toLowerCase().includes(searchValue) ||
            row.ngay_tao.includes(searchValue)
        );
        currentPage = 1;
        renderFormTable();
        renderPagination();
    }

    document.addEventListener("DOMContentLoaded", () => {
        document.getElementById("searchInput").addEventListener("input", e => {
            applySearch(e.target.value);
        });

        document.getElementById("rowsPerPageSelect").addEventListener("change", e => {
            rowsPerPage = parseInt(e.target.value);
            currentPage = 1;
            renderFormTable();
            renderPagination();
        });

        renderFormTable();
        renderPagination();
    });
</script>
@endpush
