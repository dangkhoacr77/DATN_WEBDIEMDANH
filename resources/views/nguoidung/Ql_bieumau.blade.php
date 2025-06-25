@extends('layout/user')

@section('title', 'Quản lý biểu mẫu')
@section('page-title', 'Danh sách biểu mẫu')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <input type="text" class="form-control" id="searchInput" onkeyup="searchForm()"
            placeholder="🔍 Tìm kiếm biểu mẫu..."
            style="border: none; background: #efefef; border-radius: 8px; padding: 8px 16px; width: 300px;">
        <button class="btn btn-danger" onclick="deleteSelectedRows()">🗑️ Xóa đã chọn</button>
    </div>

    <div class="table-responsive">
        <table class="table align-middle text-center" id="formTable">
            <thead class="table-light">
                <tr>
                    <th><input type="checkbox" id="selectAll" onclick="toggleAll(this)"></th>
                    <th>Tiêu đề</th>
                    <th>Màu</th>
                    <th>Hình ảnh</th>
                    <th>Ngày tạo</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 1; $i <= 8; $i++)
                    <tr>
                        <td><input type="checkbox" class="row-checkbox"></td>
                        <td>Biểu mẫu {{ $i }}</td>
                        <td>
                            @php
                                $colors = ['Xanh', 'Đỏ', 'Vàng', 'Tím', 'Xanh lá', 'Cam', 'Hồng', 'Xám'];
                            @endphp
                            {{ $colors[$i - 1] }}
                        </td>
                        <td>Hình ảnh</td>
                        <td>{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}/01/2025</td>
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>

    <nav aria-label="Phân trang">
        <ul class="pagination justify-content-end mt-3" id="pagination"></ul>
    </nav>
@endsection

@push('scripts')
<script>
    // Hiện/ẩn dropdown avatar
    function toggleMenu() {
        const menu = document.getElementById("avatarDropdown");
        menu.style.display = menu.style.display === "block" ? "none" : "block";
    }
    window.onclick = event => {
        if (!event.target.closest('.avatar-menu')) {
            const menu = document.getElementById("avatarDropdown");
            if (menu) menu.style.display = "none";
        }
    };

    // Chọn/bỏ chọn tất cả
    function toggleAll(master) {
        document.querySelectorAll(".row-checkbox").forEach(cb => cb.checked = master.checked);
    }

    // Xóa các dòng đã chọn
    function deleteSelectedRows() {
        const selected = document.querySelectorAll(".row-checkbox:checked");
        if (!selected.length) {
            return alert("Bạn chưa chọn dòng nào để xóa.");
        }
        if (confirm("Bạn có chắc chắn muốn xóa các dòng đã chọn không?")) {
            selected.forEach(cb => cb.closest("tr").remove());
            displayPage(1);
        }
    }

    // Tìm kiếm
    function searchForm() {
        const q = document.getElementById("searchInput").value.toLowerCase();
        document.querySelectorAll("#formTable tbody tr").forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? "" : "none";
        });
    }

    // Phân trang
    const rowsPerPage = 7;
    const tbody = document.querySelector("#formTable tbody");
    const pagination = document.getElementById("pagination");

    function displayPage(page) {
        const rows = tbody.querySelectorAll("tr");
        const totalPages = Math.ceil(rows.length / rowsPerPage);
        const start = (page - 1) * rowsPerPage;
        rows.forEach((r, i) => {
            r.style.display = (i >= start && i < start + rowsPerPage) ? "" : "none";
        });
        pagination.innerHTML = "";
        for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement("li");
            li.className = `page-item ${i === page ? "active" : ""}`;
            const a = document.createElement("a");
            a.className = "page-link";
            a.href = "#";
            a.textContent = i;
            a.onclick = e => {
                e.preventDefault();
                displayPage(i);
            };
            li.appendChild(a);
            pagination.appendChild(li);
        }
    }

    displayPage(1);
</script>
@endpush
