@extends('layout.user')

@section('title', 'Quản lý danh sách điểm danh')
@section('page-title', 'Quản lý danh sách điểm danh')

@section('content')
    <div class="mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <input id="searchInput" type="text" class="form-control"
            placeholder="🔍 Tìm kiếm danh sách..."
            style="max-width: 300px; border: none; background: #efefef; border-radius: 8px;">
        <button type="submit" form="deleteForm" class="btn btn-danger">🗑️ Xóa đã chọn</button>
    </div>

    <form id="deleteForm" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa?');">
        @csrf
        @method('DELETE')

        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th><input type="checkbox" id="selectAll" onclick="toggleAll(this)"></th>
                        <th>Tên danh sách</th>
                        <th>Ngày tạo</th>
                        <th>Thời gian</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="list-body">
                    {{-- Dữ liệu sẽ được render bằng JavaScript --}}
                </tbody>
            </table>
        </div>
    </form>

    <!-- Phân trang -->
    <nav aria-label="Phân trang">
        <ul class="pagination justify-content-end mt-3" id="pagination"></ul>
    </nav>

    <!-- Modal preview -->
    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalLabel">Chi tiết danh sách điểm danh</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    Đang tải dữ liệu...
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<style>
    .table-bordered td,
    .table-bordered th {
        border-left: none !important;
        border-right: none !important;
    }

    .table-bordered {
        border-left: none !important;
        border-right: none !important;
    }

    #list-body tr:first-child td {
        border-top: none !important;
    }

    .table thead,
    .table thead tr {
        border-top: none !important;
    }
</style>

<script>
    const originalData = @json($danhSach);
    let filteredData = [...originalData];

    const rowsPerPage = 7;
    const pagination = document.getElementById("pagination");

    function renderTable(data) {
        const tbody = document.getElementById("list-body");
        tbody.innerHTML = "";

        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5">Không có dữ liệu.</td></tr>`;
            return;
        }

        data.forEach(ds => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td><input type="checkbox" name="ids[]" value="${ds.ma_danh_sach}" class="row-checkbox"></td>
                <td>${ds.ten_danh_sach}</td>
                <td>${ds.ngay_tao}</td>
                <td>${ds.thoi_gian_tao}</td>
                <td>
                    <button type="button" class="btn btn-link text-primary p-0"
                        onclick="downloadList('${ds.ma_danh_sach}')">
                        <i class="bi bi-download fs-5"></i>
                    </button>
                    <button type="button" class="btn btn-link text-info p-0"
                        onclick="previewExcel('${ds.ma_danh_sach}', '${ds.ten_danh_sach}')">
                        <i class="bi bi-eye fs-5"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function toggleAll(master) {
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = master.checked);
    }

    function downloadList(maDanhSach) {
        window.location.href = `/nguoidung/ql-danhsach/export/${maDanhSach}`;
    }

    function previewExcel(maDanhSach, tenDanhSach) {
        const modal = new bootstrap.Modal(document.getElementById('previewModal'));
        document.getElementById('modalContent').innerHTML = '⏳ Đang tải dữ liệu...';
        document.getElementById('previewModalLabel').textContent = `Chi tiết: ${tenDanhSach}`;
        modal.show();

        fetch(`/nguoidung/ql-danhsach/${maDanhSach}/preview`)
            .then(res => res.json())
            .then(data => {
                if (!data.success || !data.rows || data.rows.length === 0) {
                    document.getElementById('modalContent').innerHTML = 'Không có dữ liệu điểm danh.';
                    return;
                }

                const rows = data.rows;
                const labels = data.labels || [];
                const maxAnswers = Math.max(...rows.map(r => r.cau_tra_loi.length), labels.length);

                const table = `
                    <div style="overflow-x:auto;">
                        <table class="table table-sm table-striped table-bordered text-center align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Email</th>
                                    <th>Thời gian</th>
                                    <th>Thiết bị</th>
                                    <th>Định vị</th>
                                    ${[...Array(maxAnswers)].map((_, i) => `<th>${labels[i] ?? ''}</th>`).join('')}
                                </tr>
                            </thead>
                            <tbody>
                                ${rows.map(row => `
                                    <tr>
                                        <td>${row.email}</td>
                                        <td>${row.thoi_gian}</td>
                                        <td>${row.thiet_bi}</td>
                                        <td>${row.dinh_vi}</td>
                                        ${[...Array(maxAnswers)].map((_, i) => `<td>${row.cau_tra_loi[i] ?? ''}</td>`).join('')}
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>`;
                document.getElementById('modalContent').innerHTML = table;
            })
            .catch(err => {
                console.error(err);
                document.getElementById('modalContent').innerHTML = "⚠️ Lỗi khi tải dữ liệu.";
            });
    }

    function displayPage(data, page) {
        const start = (page - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const pageData = data.slice(start, end);
        renderTable(pageData);
        renderPagination(data.length, page);
    }

    function renderPagination(totalRows, currentPage) {
        const totalPages = Math.ceil(totalRows / rowsPerPage);
        pagination.innerHTML = "";

        for (let i = 1; i <= totalPages; i++) {
            const li = document.createElement("li");
            li.className = `page-item ${i === currentPage ? "active" : ""}`;
            const a = document.createElement("a");
            a.className = "page-link";
            a.href = "#";
            a.textContent = i;
            a.onclick = e => {
                e.preventDefault();
                displayPage(filteredData, i);
            };
            li.appendChild(a);
            pagination.appendChild(li);
        }
    }

    function applySearch(keyword) {
        const lowerKeyword = keyword.toLowerCase();
        filteredData = originalData.filter(ds =>
            ds.ten_danh_sach.toLowerCase().includes(lowerKeyword) ||
            ds.ngay_tao.includes(lowerKeyword) ||
            ds.thoi_gian_tao.includes(lowerKeyword)
        );
        displayPage(filteredData, 1);
    }

    document.getElementById('deleteForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);

        fetch('{{ route('nguoidung.ql-danhsach.destroy') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-HTTP-Method-Override': 'DELETE'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) location.reload();
        })
        .catch(err => console.error(err));
    });

    document.addEventListener('DOMContentLoaded', () => {
        displayPage(filteredData, 1);
        document.getElementById("searchInput").addEventListener("input", (e) => {
            applySearch(e.target.value);
        });
    });
</script>
@endpush
