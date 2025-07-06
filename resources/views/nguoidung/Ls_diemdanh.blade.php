@extends('layout.user')

@section('title', 'Lịch sử điểm danh')
@section('page-title', 'Lịch sử điểm danh')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
        <input type="text" class="form-control" id="searchInput" placeholder="🔍 Tìm kiếm lịch sử..."
            style="border: none; background: #efefef; border-radius: 8px; padding: 8px 16px; width: 300px;">
        <label style="font-size: 14px;">
            Hiển thị:
            <select id="rowsPerPageSelect" style="padding: 6px 12px; border-radius: 6px;">
                <option value="10" selected>10 dòng</option>
                <option value="15">15 dòng</option>
                <option value="20">20 dòng</option>
            </select>
        </label>
    </div>

    <div class="table-responsive">
        <table class="table align-middle text-center" id="dd-table">
            <thead class="table-light">
                <tr>
                    <th>
                        <div class="d-inline-flex align-items-center">
                            <span class="fw-semibold me-1">Biểu mẫu</span>
                            <select id="sortTieu_de" class="form-select form-select-sm" onchange="onSortChange('tieu_de')" style="width: 80px;">
                                <option value="">Chọn</option>
                                <option value="asc">A→Z</option>
                                <option value="desc">Z→A</option>
                            </select>
                        </div>
                    </th>
                    <th>
                        <div class="d-inline-flex align-items-center">
                            <span class="fw-semibold me-1">Người tạo</span>
                            <select id="sortNguoi_tao" class="form-select form-select-sm" onchange="onSortChange('nguoi_tao')" style="width: 80px;">
                                <option value="">Chọn</option>
                                <option value="asc">A→Z</option>
                                <option value="desc">Z→A</option>
                            </select>
                        </div>
                    </th>
                    <th>
                        <div class="d-inline-flex align-items-center">
                            <span class="fw-semibold me-1">Thiết bị</span>
                            <select id="sortThiet_bi" class="form-select form-select-sm" onchange="onSortChange('thiet_bi')" style="width: 80px;">
                                <option value="">Chọn</option>
                                <option value="asc">A→Z</option>
                                <option value="desc">Z→A</option>
                            </select>
                        </div>
                    </th>
                    <th>
                        <div class="d-inline-flex align-items-center">
                            <span class="fw-semibold me-1">Ngày</span>
                            <select id="sortNgay" class="form-select form-select-sm" onchange="onSortChange('ngay')" style="width: 80px;">
                                <option value="">Chọn</option>
                                <option value="desc" selected>Mới</option>
                                <option value="asc">Cũ</option>
                            </select>
                        </div>
                    </th>
                    <th>Thời gian</th>
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
    let data = rawData.map(dd => {
        const datetime = new Date(dd.thoi_gian_diem_danh);
        return {
            tieu_de: dd.bieu_mau?.tieu_de ?? '---',
            nguoi_tao: dd.bieu_mau?.tai_khoan?.ho_ten ?? '---',
            thiet_bi: dd.thiet_bi_diem_danh ?? '',
            dinh_vi: dd.dinh_vi_thiet_bi ?? '',
            ngay: datetime.toLocaleDateString('vi-VN'),
            thoi_gian: datetime.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' }),
            raw_datetime: datetime
        };
    });

    let rowsPerPage = 7, currentPage = 1, filteredData = [...data];
    let currentSortField = 'ngay', currentSortOrder = 'desc', searchKeyword = '';

    function renderTable() {
        const tbody = document.getElementById("dd-body");
        tbody.innerHTML = "";

        const start = (currentPage - 1) * rowsPerPage;
        const rows = filteredData.slice(start, start + rowsPerPage);

        if (!rows.length) {
            tbody.innerHTML = `<tr><td colspan="6" class="text-center py-3">Không tìm thấy dữ liệu</td></tr>`;
            return;
        }

        rows.forEach(row => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td>${row.tieu_de}</td>
                <td>${row.nguoi_tao}</td>
                <td>${row.thiet_bi}</td>
                <td>${row.ngay}</td>
                <td>${row.thoi_gian}</td>
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

    function applyFilterSortRender() {
        filteredData = data.filter(row =>
            row.tieu_de.toLowerCase().includes(searchKeyword) ||
            row.nguoi_tao.toLowerCase().includes(searchKeyword) ||
            row.thiet_bi.toLowerCase().includes(searchKeyword) ||
            row.dinh_vi.toLowerCase().includes(searchKeyword) ||
            row.ngay.includes(searchKeyword)
        );

        if (currentSortField === 'ngay') {
            filteredData.sort((a, b) => currentSortOrder === 'asc'
                ? a.raw_datetime - b.raw_datetime
                : b.raw_datetime - a.raw_datetime);
        } else {
            filteredData.sort((a, b) => currentSortOrder === 'asc'
                ? a[currentSortField].localeCompare(b[currentSortField])
                : b[currentSortField].localeCompare(a[currentSortField]));
        }

        currentPage = 1;
        renderTable();
        renderPagination();
    }

    function onSortChange(field) {
        ['tieu_de', 'nguoi_tao', 'thiet_bi', 'ngay'].forEach(id => {
            if (id !== field) document.getElementById('sort' + capitalize(id)).value = '';
        });

        const order = document.getElementById('sort' + capitalize(field)).value;
        if (!order) return;

        currentSortField = field;
        currentSortOrder = order;
        applyFilterSortRender();
    }

    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    document.addEventListener("DOMContentLoaded", () => {
        document.getElementById("searchInput").addEventListener("input", e => {
            searchKeyword = e.target.value.toLowerCase();
            applyFilterSortRender();
        });

        document.getElementById("rowsPerPageSelect").addEventListener("change", e => {
            rowsPerPage = parseInt(e.target.value);
            currentPage = 1;
            renderTable();
            renderPagination();
        });

        applyFilterSortRender(); // khởi động
    });
</script>
@endpush
