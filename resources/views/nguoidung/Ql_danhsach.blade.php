@extends('layout.user')

@section('title', 'Quản lý danh sách điểm danh')
@section('page-title', 'Quản lý danh sách điểm danh')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <input id="searchInput" type="text" class="form-control" placeholder="🔍 Tìm kiếm danh sách..."
            style="max-width: 300px; border: none; background: #efefef; border-radius: 8px;">
        <button class="btn btn-outline-danger" onclick="deleteSelectedRows()">
            <i class="fas fa-trash-alt me-1"></i> Xóa đã chọn
        </button>
    </div>

    <div class="table-responsive">
        <table class="table align-middle text-center">
            <thead class="table-light align-middle">
                <tr>
                    <th style="width: 50px;"><input type="checkbox" id="selectAll" onclick="toggleAll(this)"></th>
                    <th>
                        <div class="d-inline-flex align-items-center">
                            <span class="fw-semibold me-1">Tên danh sách</span>
                            <select id="sortTen" onchange="onSortChange('ten_danh_sach')"
                                class="form-select form-select-sm" style="width: 80px;">
                                <option value="">Chọn</option>
                                <option value="asc">A→Z</option>
                                <option value="desc">Z→A</option>
                            </select>
                        </div>
                    </th>
                    <th>
                        <div class="d-inline-flex align-items-center">
                            <span class="fw-semibold me-1">Ngày tạo</span>
                            <select id="sortNgayTao" onchange="onSortChange('ngay_tao')" class="form-select form-select-sm"
                                style="width: 80px;">
                                <option value="">Chọn</option>
                                <option value="desc">Mới</option>
                                <option value="asc">Cũ</option>
                            </select>
                        </div>
                    </th>
                    <th>Thời gian</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="list-body"></tbody>
        </table>
    </div>

    <nav aria-label="Phân trang">
        <ul class="pagination justify-content-end mt-3" id="pagination"></ul>
    </nav>

    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">Chi tiết danh sách điểm danh</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body" id="modalContent">Đang tải dữ liệu...</div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const originalData = @json($danhSach);
        let originalRows = [],
            searchedRows = [],
            sortedRows = [],
            currentPage = 1;
        const rowsPerPage = 7;

        const formatDate = dateStr => {
            const d = new Date(dateStr);
            const day = String(d.getDate()).padStart(2, '0');
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const year = d.getFullYear();
            return `${day}/${month}/${year}`;
        };

        function toggleAll(master) {
            document.querySelectorAll(".row-checkbox").forEach(cb => cb.checked = master.checked);
        }

        function deleteSelectedRows() {
            const selected = document.querySelectorAll(".row-checkbox:checked");
            if (!selected.length) return alert("Bạn chưa chọn dòng nào để xóa.");

            if (confirm("Bạn có chắc chắn muốn xóa các danh sách đã chọn không?")) {
                const ids = Array.from(selected).map(cb => cb.value);
                fetch("{{ route('nguoidung.ql-danhsach.destroy') }}", {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": '{{ csrf_token() }}',
                            "X-HTTP-Method-Override": "DELETE"
                        },
                        body: new URLSearchParams(ids.map(id => ['ids[]', id]))
                    })
                    .then(res => res.json())
                    .then(data => data.success ? location.reload() : alert("Xóa thất bại."))
                    .catch(() => alert("Đã xảy ra lỗi!"));
            }
        }

        function onSortChange(type) {
            if (type === 'ten_danh_sach') document.getElementById("sortNgayTao").value = "";
            if (type === 'ngay_tao') document.getElementById("sortTen").value = "";
            applySort();
            displayPage(1);
        }

        function filterTable() {
            applySearch();
            applySort();
            displayPage(1);
        }

        function applySearch() {
            const keyword = document.getElementById("searchInput").value.toLowerCase();
            searchedRows = originalRows.filter(row => {
                const ten = row.children[1].textContent.toLowerCase();
                const ngay = row.children[2].textContent.toLowerCase();
                const tg = row.children[3].textContent.toLowerCase();
                return ten.includes(keyword) || ngay.includes(keyword) || tg.includes(keyword);
            });
        }

        function applySort() {
            const sortTen = document.getElementById("sortTen")?.value;
            const sortNgayTao = document.getElementById("sortNgayTao")?.value;
            sortedRows = [...searchedRows];

            if (sortTen) {
                sortedRows.sort((a, b) => sortTen === "asc" ?
                    a.children[1].textContent.localeCompare(b.children[1].textContent) :
                    b.children[1].textContent.localeCompare(a.children[1].textContent));
            } else if (sortNgayTao) {
                const parseDateTime = (row) => {
                    const ngay = row.children[2].textContent.trim().split('/').reverse().join('-'); // yyyy-mm-dd
                    const gio = row.children[3].textContent.trim() || '00:00';
                    return new Date(`${ngay}T${gio}`);
                };
                sortedRows.sort((a, b) => {
                    const aDateTime = parseDateTime(a);
                    const bDateTime = parseDateTime(b);
                    return sortNgayTao === "asc" ? aDateTime - bDateTime : bDateTime - aDateTime;
                });
            } else {
                // Mặc định sắp xếp theo ngày+giờ mới nhất
                const parseDateTime = (row) => {
                    const ngay = row.children[2].textContent.trim().split('/').reverse().join('-');
                    const gio = row.children[3].textContent.trim() || '00:00';
                    return new Date(`${ngay}T${gio}`);
                };
                sortedRows.sort((a, b) => {
                    const aDateTime = parseDateTime(a);
                    const bDateTime = parseDateTime(b);
                    return bDateTime - aDateTime;
                });
            }
        }

        function displayPage(page) {
            const tbody = document.querySelector("#list-body");
            tbody.innerHTML = "";

            const totalPages = Math.ceil(sortedRows.length / rowsPerPage);
            if (page > totalPages) page = totalPages;
            const start = (page - 1) * rowsPerPage;
            const pageRows = sortedRows.slice(start, start + rowsPerPage);
            pageRows.forEach(row => tbody.appendChild(row));
            renderPagination(totalPages, page);
        }

        function renderPagination(totalPages, activePage) {
            const pagination = document.getElementById("pagination");
            pagination.innerHTML = "";

            const createPageItem = (page, text = page, isActive = false, isDisabled = false) => {
                const li = document.createElement("li");
                li.className = `page-item ${isActive ? "active" : ""} ${isDisabled ? "disabled" : ""}`;
                const a = document.createElement("a");
                a.className = "page-link";
                a.href = "#";
                a.innerText = text;
                if (!isDisabled) a.onclick = e => {
                    e.preventDefault();
                    currentPage = page;
                    displayPage(currentPage);
                };
                li.appendChild(a);
                return li;
            };

            pagination.appendChild(createPageItem(currentPage - 1, "«", false, currentPage === 1));

            const visiblePages = [];
            if (totalPages <= 7)
                for (let i = 1; i <= totalPages; i++) visiblePages.push(i);
            else {
                visiblePages.push(1);
                if (currentPage <= 3) visiblePages.push(2, 3, 4, "...");
                else if (currentPage >= totalPages - 2) visiblePages.push("...", totalPages - 3, totalPages - 2,
                    totalPages - 1);
                else visiblePages.push("...", currentPage - 1, currentPage, currentPage + 1, "...");
                visiblePages.push(totalPages);
            }

            visiblePages.forEach(p => {
                pagination.appendChild(p === "..." ? createPageItem(null, "...", false, true) : createPageItem(p, p,
                    p === currentPage));
            });

            pagination.appendChild(createPageItem(currentPage + 1, "»", false, currentPage === totalPages));
        }

        document.addEventListener("DOMContentLoaded", function() {
            originalRows = originalData.map(ds => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                <td><input type="checkbox" class="row-checkbox" value="${ds.ma_danh_sach}"></td>
                <td>${ds.ten_danh_sach}</td>
                <td>${formatDate(ds.ngay_tao)}</td>
                <td>${ds.thoi_gian_tao}</td>
                <td>
                    <button type="button" class="btn btn-link text-primary p-0" onclick="downloadList('${ds.ma_danh_sach}')">
                        <i class="bi bi-download fs-5"></i>
                    </button>
                    <a href="/nguoidung/danhsach/${ds.ma_danh_sach}" class="btn btn-link text-info p-0">
                        <i class="bi bi-eye fs-5"></i>
                    </a>
                </td>`;
                return tr;
            });

            filterTable();
            document.getElementById("searchInput").addEventListener("input", () => filterTable());
        });
    </script>
@endpush
