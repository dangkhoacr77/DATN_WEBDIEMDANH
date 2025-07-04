@extends('layout/user')

@section('title', 'Quản lý biểu mẫu')
@section('page-title', 'Danh sách biểu mẫu')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Thêm vào <head> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <div class="d-flex align-items-center justify-content-between mb-3">
        <input type="text" class="form-control" id="searchInput" onkeyup="searchForm()" placeholder="🔍 Tìm kiếm biểu mẫu..."
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
                    <th>Ngày tạo</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bieumau as $bm)
                    @php
                        $hexToColorName = [
                            '#86efac' => 'Xanh lá',
                            '#fdba74' => 'Cam',
                            '#fca5a5' => 'Đỏ nhạt',
                            '#ff0000' => 'Đỏ',
                            '#ffffff' => 'Trắng',
                            '#000000' => 'Đen',
                        ];
                        $mau = strtolower($bm->mau);
                        $tenMau = $hexToColorName[$mau] ?? ($bm->ten_mau ?? $mau);
                    @endphp
                    <tr>
                        <td><input type="checkbox" class="row-checkbox" value="{{ $bm->ma_bieu_mau }}"></td>
                        <td>{{ $bm->tieu_de }}</td>
                        <td>
                            <div class="d-flex align-items-center justify-content-center gap-2">
                                <span class="inline-block w-4 h-4 rounded-full border"
                                    style="background-color: {{ $bm->mau }};"></span>
                                <span>{{ $tenMau }}</span>
                            </div>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($bm->ngay_tao)->format('d/m/Y') }}</td>
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
        function toggleAll(master) {
            document.querySelectorAll(".row-checkbox").forEach(cb => cb.checked = master.checked);
        }

        function deleteSelectedRows() {
            const selected = document.querySelectorAll(".row-checkbox:checked");
            if (!selected.length) {
                return alert("Bạn chưa chọn dòng nào để xóa.");
            }

            if (confirm("Bạn có chắc chắn muốn xóa các biểu mẫu đã chọn không?")) {
                const ids = Array.from(selected).map(cb => cb.value);

                fetch("{{ route('nguoidung.bieumau.xoaDaChon') }}", {
                        method: "DELETE",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name=\"csrf-token\"]').getAttribute("content")
                        },
                        body: JSON.stringify({
                            ids
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        location.reload();
                    })
                    .catch(error => {
                        console.error("Lỗi khi xóa:", error);
                        alert("Đã xảy ra lỗi!");
                    });
            }
        }

        function searchForm() {
            const q = document.getElementById("searchInput").value.toLowerCase();
            document.querySelectorAll("#formTable tbody tr").forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(q) ? "" : "none";
            });
        }

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

        document.addEventListener("DOMContentLoaded", function() {
            displayPage(1);
        });
    </script>
@endpush
