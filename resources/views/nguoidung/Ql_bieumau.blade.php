@extends('layout/user')

@section('title', 'Quản lý biểu mẫu')
@section('page-title', 'Danh sách biểu mẫu')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        .form-select,
        .form-control {
            font-size: 14px;
        }

        .pagination .page-item.active .page-link {
            background-color: #0e42ff;
            border-color: #0e42ff;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #094afa;
            box-shadow: 0 0 0 0.2rem rgba(4, 0, 255, 0.25);
        }
    </style>

    <div class="d-flex align-items-center justify-content-between mb-3">
        <input type="text" class="form-control" id="searchInput" onkeyup="filterTable()" placeholder="🔍 Tìm kiếm biểu mẫu..."
            style="border: none; background: #efefef; border-radius: 8px; padding: 8px 16px; width: 300px;">
        <button class="btn btn-outline-danger" onclick="deleteSelectedRows()">
            <i class="fas fa-trash-alt me-1"></i> Xóa đã chọn
        </button>
    </div>

    <div class="table-responsive">
        <table class="table align-middle text-center" id="formTable">
            <thead class="table-light align-middle">
                <tr>
                    <th style="width: 50px;">
                        <input type="checkbox" id="selectAll" onclick="toggleAll(this)">
                    </th>
                    <th>
                        <div class="d-inline-flex align-items-center">
                            <span class="fw-semibold me-1">Tiêu đề</span>
                            <select id="sortTieuDe" onchange="onSortChange('tieu_de')" class="form-select form-select-sm"
                                style="width: 80px;">
                                <option value="">Chọn</option>
                                <option value="asc">A→Z</option>
                                <option value="desc">Z→A</option>
                            </select>
                        </div>
                    </th>
                    <th>
                        <div class="d-inline-flex align-items-center">
                            <span class="fw-semibold me-1">Giao diện</span>
                            <select id="sortGiaoDien" onchange="onSortChange('giao_dien')"
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
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bieumau as $bm)
                    <tr>
                        <td><input type="checkbox" class="row-checkbox" value="{{ $bm->ma_bieu_mau }}"></td>
                        <td>{{ $bm->tieu_de }}</td>
                        <td>{{ $bm->mau }}</td>
                        <td data-time="{{ \Carbon\Carbon::parse($bm->ngay_tao)->format('Y-m-d H:i:s') }}">
                            {{ \Carbon\Carbon::parse($bm->ngay_tao)->format('d/m/Y') }}
                        </td>
                        <td>
                            <button type="button" class="btn btn-link text-info p-0"
                                onclick="window.location.href='{{ route('bieumau.show', ['ma_bieu_mau' => $bm->ma_bieu_mau]) }}'">
                                <i class="bi bi-eye fs-5"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <nav aria-label="Phân trang">
        <ul class="pagination justify-content-end mt-3" id="pagination"></ul>
    </nav>
@endsection

@push('scripts')
    <script>
        let originalRows = [];
        let searchedRows = [];
        let sortedRows = [];
        let currentPage = 1;
        const rowsPerPage = 7;

        function toggleAll(master) {
            document.querySelectorAll(".row-checkbox").forEach(cb => cb.checked = master.checked);
        }

        function deleteSelectedRows() {
            const selected = document.querySelectorAll(".row-checkbox:checked");
            if (!selected.length) return alert("Bạn chưa chọn dòng nào để xóa.");

            if (confirm("Bạn có chắc chắn muốn xóa các biểu mẫu đã chọn không?")) {
                const ids = Array.from(selected).map(cb => cb.value);
                fetch("{{ url()->route('nguoidung.bieumau.xoaDaChon', [], false) }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        },
                        body: JSON.stringify({
                            ids
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(error => {
                                throw new Error(error.message || 'Lỗi không xác định');
                            });
                        }
                        return response.json();
                    })
                    .then(() => location.reload())
                    .catch(err => alert("Đã xảy ra lỗi: " + err.message));

            }
        }

        function onSortChange(type) {
            if (type === 'tieu_de') {
                document.getElementById("sortGiaoDien").value = "";
                document.getElementById("sortNgayTao").value = "";
            } else if (type === 'giao_dien') {
                document.getElementById("sortTieuDe").value = "";
                document.getElementById("sortNgayTao").value = "";
            } else if (type === 'ngay_tao') {
                document.getElementById("sortTieuDe").value = "";
                document.getElementById("sortGiaoDien").value = "";
            }

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
                const tieuDe = row.children[1].textContent.toLowerCase();
                const giaoDien = row.children[2].textContent.toLowerCase();
                const ngayTao = row.children[3].textContent.toLowerCase();
                return tieuDe.includes(keyword) || giaoDien.includes(keyword) || ngayTao.includes(keyword);
            });
        }

        function parseVNDateTime(text) {
            const [datePart, timePart] = text.split(' ');
            const [day, month, year] = datePart.split('/');
            const [hour = 0, minute = 0, second = 0] = (timePart || '').split(':');
            return new Date(year, month - 1, day, hour, minute, second);
        }

        function applySort() {
            const sortTieuDe = document.getElementById("sortTieuDe").value;
            const sortGiaoDien = document.getElementById("sortGiaoDien").value;
            const sortNgayTao = document.getElementById("sortNgayTao").value;

            sortedRows = [...searchedRows];

            if (sortTieuDe) {
                sortedRows.sort((a, b) => {
                    const aText = a.children[1].textContent.toLowerCase();
                    const bText = b.children[1].textContent.toLowerCase();
                    return sortTieuDe === "asc" ? aText.localeCompare(bText) : bText.localeCompare(aText);
                });
            } else if (sortGiaoDien) {
                sortedRows.sort((a, b) => {
                    const aText = a.children[2].textContent.toLowerCase();
                    const bText = b.children[2].textContent.toLowerCase();
                    return sortGiaoDien === "asc" ? aText.localeCompare(bText) : bText.localeCompare(aText);
                });
            } else if (sortNgayTao) {
                sortedRows.sort((a, b) => {
                    const aDate = new Date(a.children[3].dataset.time);
                    const bDate = new Date(b.children[3].dataset.time);
                    return sortNgayTao === "asc" ? aDate - bDate : bDate - aDate;
                });
            } else {
                // Mặc định: mới nhất trước
                sortedRows.sort((a, b) => {
                    const aDate = new Date(a.children[3].dataset.time);
                    const bDate = new Date(b.children[3].dataset.time);
                    return bDate - aDate;
                });
            }
        }

        function displayPage(page) {
            const tbody = document.querySelector("#formTable tbody");
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

            function createPageItem(page, text = page, isActive = false, isDisabled = false) {
                const li = document.createElement("li");
                li.className = `page-item ${isActive ? "active" : ""} ${isDisabled ? "disabled" : ""}`;
                const a = document.createElement("a");
                a.className = "page-link";
                a.href = "#";
                a.innerText = text;
                if (!isDisabled) {
                    a.onclick = (e) => {
                        e.preventDefault();
                        currentPage = page;
                        displayPage(currentPage);
                    };
                }
                li.appendChild(a);
                return li;
            }

            pagination.appendChild(createPageItem(currentPage - 1, "«", false, currentPage === 1));

            const visiblePages = [];

            if (totalPages <= 7) {
                for (let i = 1; i <= totalPages; i++) visiblePages.push(i);
            } else {
                visiblePages.push(1);

                if (currentPage <= 3) {
                    visiblePages.push(2, 3, 4);
                    visiblePages.push("...");
                } else if (currentPage >= totalPages - 2) {
                    visiblePages.push("...");
                    visiblePages.push(totalPages - 3, totalPages - 2, totalPages - 1);
                } else {
                    visiblePages.push("...");
                    visiblePages.push(currentPage - 1, currentPage, currentPage + 1);
                    visiblePages.push("...");
                }

                visiblePages.push(totalPages);
            }

            visiblePages.forEach(p => {
                if (p === "...") {
                    pagination.appendChild(createPageItem(null, "...", false, true));
                } else {
                    pagination.appendChild(createPageItem(p, p, p === currentPage));
                }
            });

            pagination.appendChild(createPageItem(currentPage + 1, "»", false, currentPage === totalPages));
        }

        document.addEventListener("DOMContentLoaded", function() {
            originalRows = Array.from(document.querySelectorAll("#formTable tbody tr"));
            filterTable();
        });
    </script>
@endpush
