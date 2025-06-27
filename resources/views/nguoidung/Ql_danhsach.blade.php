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
                        <th>Tải Excel</th>
                    </tr>
                </thead>
                <tbody id="list-body">
                    {{-- Dữ liệu sẽ được render bằng JavaScript --}}
                </tbody>
            </table>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        // Lấy dữ liệu từ PHP
        const originalData = @json($danhSach);
        let filteredData = [...originalData];

        // Render bảng danh sách
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
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        // Tìm kiếm client-side
        function applySearch(keyword) {
            const lowerKeyword = keyword.toLowerCase();
            filteredData = originalData.filter(ds =>
                ds.ten_danh_sach.toLowerCase().includes(lowerKeyword) ||
                ds.ngay_tao.includes(lowerKeyword) ||
                ds.thoi_gian_tao.includes(lowerKeyword)
            );
            renderTable(filteredData);
        }

        // Chọn tất cả checkbox
        function toggleAll(master) {
            document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = master.checked);
        }

        // Tải file Excel
        function downloadList(maDanhSach) {
            window.location.href = `/nguoidung/ql-danhsach/export/${maDanhSach}`;
        }

        // Gửi form xóa
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

        // Khởi tạo
        document.addEventListener('DOMContentLoaded', () => {
            renderTable(originalData);

            document.getElementById("searchInput").addEventListener("input", (e) => {
                applySearch(e.target.value);
            });
        });
    </script>
@endpush
