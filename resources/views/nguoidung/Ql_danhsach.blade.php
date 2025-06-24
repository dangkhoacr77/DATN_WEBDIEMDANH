@extends('layout/user')

@section('title', 'Danh sách điểm danh')
@section('page-title', 'Danh sách điểm danh')

@section('content')
    <input type="text" class="form-control mt-n2 mb-3" id="searchInput" onkeyup="searchList()"
        placeholder="🔍 Tìm kiếm danh sách..."
        style="border: none; background: #efefef; border-radius: 8px; padding: 8px 16px; width: 300px;">

    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center" id="listTable">
            <thead class="table-light">
                <tr>
                    <th><input type="checkbox" id="selectAll" onclick="toggleAll(this)"></th>
                    <th>Tên danh sách</th>
                    <th>Ngày tạo</th>
                    <th>Thời gian</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="checkbox" class="row-checkbox"></td>
                    <td>Danh sách lớp A</td>
                    <td>2025-06-01</td>
                    <td>08:00</td>
                    <td><i class="bi bi-download" onclick="downloadList('Lớp A')"></i></td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="row-checkbox"></td>
                    <td>Danh sách lớp B</td>
                    <td>2025-06-02</td>
                    <td>08:15</td>
                    <td><i class="bi bi-download" onclick="downloadList('Lớp B')"></i></td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="row-checkbox"></td>
                    <td>Danh sách lớp C</td>
                    <td>2025-06-03</td>
                    <td>08:30</td>
                    <td><i class="bi bi-download" onclick="downloadList('Lớp C')"></i></td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="row-checkbox"></td>
                    <td>Danh sách lớp C</td>
                    <td>2025-06-03</td>
                    <td>08:30</td>
                    <td><i class="bi bi-download" onclick="downloadList('Lớp C')"></i></td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="row-checkbox"></td>
                    <td>Danh sách lớp C</td>
                    <td>2025-06-03</td>
                    <td>08:30</td>
                    <td><i class="bi bi-download" onclick="downloadList('Lớp C')"></i></td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="row-checkbox"></td>
                    <td>Danh sách lớp C</td>
                    <td>2025-06-03</td>
                    <td>08:30</td>
                    <td><i class="bi bi-download" onclick="downloadList('Lớp C')"></i></td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="row-checkbox"></td>
                    <td>Danh sách lớp C</td>
                    <td>2025-06-03</td>
                    <td>08:30</td>
                    <td><i class="bi bi-download" onclick="downloadList('Lớp C')"></i></td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="row-checkbox"></td>
                    <td>Danh sách lớp C</td>
                    <td>2025-06-03</td>
                    <td>08:30</td>
                    <td><i class="bi bi-download" onclick="downloadList('Lớp C')"></i></td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="row-checkbox"></td>
                    <td>Danh sách lớp C</td>
                    <td>2025-06-03</td>
                    <td>08:30</td>
                    <td><i class="bi bi-download" onclick="downloadList('Lớp C')"></i></td>
                </tr>
            </tbody>
        </table>
    </div>

    <nav aria-label="Phân trang">
        <ul class="pagination justify-content-end mt-3" id="pagination"></ul>
    </nav>
@endsection

@push('scripts')
    <script>
        // Ẩn/hiện dropdown avatar
        function toggleMenu() {
            const menu = document.getElementById('avatarDropdown');
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        }
        window.onclick = event => {
            if (!event.target.closest('.avatar-menu')) {
                const menu = document.getElementById('avatarDropdown');
                if (menu) menu.style.display = 'none';
            }
        };

        // Chọn/bỏ chọn tất cả
        function toggleAll(master) {
            document.querySelectorAll('.row-checkbox')
                .forEach(cb => cb.checked = master.checked);
        }

        // Tải xuống danh sách
        function downloadList(name) {
            const data = [
                ['STT', 'Họ tên', 'Giờ điểm danh'],
                ['1', 'Nguyễn Văn A', '08:00'],
                ['2', 'Trần Thị B', '08:03'],
                ['3', 'Phạm Văn C', '08:07']
            ];
            const ws = XLSX.utils.aoa_to_sheet(data);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, name);
            XLSX.writeFile(wb, `${name.replace(/\s+/g,'_')}.xlsx`);
        }

        // Tìm kiếm
        function searchList() {
            const q = document.getElementById('searchInput').value.toLowerCase();
            document.querySelectorAll('#listTable tbody tr')
                .forEach(row => {
                    row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
                });
        }

        // Phân trang
        const rowsPerPage = 7;
        const tbody = document.querySelector('#listTable tbody');
        const pagination = document.getElementById('pagination');

        function displayPage(page) {
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const totalPages = Math.ceil(rows.length / rowsPerPage);
            const start = (page - 1) * rowsPerPage;
            rows.forEach((r, i) => {
                r.style.display = (i >= start && i < start + rowsPerPage) ? '' : 'none';
            });
            pagination.innerHTML = '';
            for (let i = 1; i <= totalPages; i++) {
                const li = document.createElement('li');
                li.className = `page-item ${i === page ? 'active' : ''}`;
                const a = document.createElement('a');
                a.className = 'page-link';
                a.href = '#';
                a.textContent = i;
                a.onclick = e => {
                    e.preventDefault();
                    displayPage(i);
                };
                li.appendChild(a);
                pagination.appendChild(li);
            }
        }

        // Khởi tạo phân trang
        displayPage(1);
    </script>
@endpush
