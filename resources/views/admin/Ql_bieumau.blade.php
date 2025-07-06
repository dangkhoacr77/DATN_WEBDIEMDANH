@extends('layout.admin')

@section('title', 'Quản lý Biểu mẫu')
@section('page-title', 'Quản lý biểu mẫu')

@section('content')
<div style="background:#fff;border-radius:16px;padding:40px;max-width:100%;width:95%;margin:auto;">
    <!-- Thanh tìm kiếm + chọn số dòng -->
    <div style="margin-bottom:12px;display:flex;justify-content:space-between;align-items:center;">
        <input id="searchInput"
               style="width:240px;border-radius:12px;border:1px solid #ddd;padding:10px 14px;"
               type="text" placeholder="🔍 Tìm kiếm…">
        <label style="font-size:14px;">
            Hiển thị:
            <select id="rowsPerPageSelect" style="padding:6px 12px;border-radius:6px;">
                <option value="10" selected>10 dòng</option>
                <option value="15">15 dòng</option>
                <option value="20">20 dòng</option>
            </select>
        </label>
    </div>

    <!-- Bảng -->
    <div id="table-container" style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:18px;">
            <thead style="background:#f1f5f9;">
            <tr>
                @foreach ([
                    ['label' => 'Tiêu đề', 'id' => 'sort-title'],
                    ['label' => 'Giao diện', 'id' => 'sort-color'],
                    ['label' => 'Người tạo', 'id' => 'sort-author'],
                    ['label' => 'Ngày tạo', 'id' => 'sort-date']
                ] as $col)
                    <th style="padding:12px;">
                        <div style="display:flex;align-items:center;gap:6px;">
                            <span style="font-weight:bold;">{{ $col['label'] }}</span>
                            <select id="{{ $col['id'] }}"
                                    style="border:1px solid #ccc;border-radius:8px;padding:4px 10px;font-size:14px;background:#fff;cursor:pointer;">
                                <option value="">Chọn</option>
                                @if ($col['id'] === 'sort-date')
                                    <option value="new">Mới</option>
                                    <option value="old">Cũ</option>
                                @else
                                    <option value="az">A → Z</option>
                                    <option value="za">Z → A</option>
                                @endif
                            </select>
                        </div>
                    </th>
                @endforeach
                <th style="width:60px;"></th>
            </tr>
            </thead>
            <tbody id="form-body"></tbody>
        </table>
    </div>

    <!-- Phân trang -->
    <div id="pagination" style="display:flex;justify-content:center;gap:8px;flex-wrap:wrap;margin-top:24px;"></div>
</div>
@endsection

@push('scripts')
@php
    $mappedBieuMaus = $bieuMaus->map(function ($bm) {
        return [
            'ma_bieu_mau' => $bm->ma_bieu_mau,
            'tieu_de'     => $bm->tieu_de,
            'mau'         => $bm->mau,
            'nguoi_tao'   => $bm->taiKhoan->ho_ten ?? 'Không rõ',
            'ngay_tao'    => \Carbon\Carbon::parse($bm->ngay_tao)->format('d/m/Y'),
            'ngay_raw'    => \Carbon\Carbon::parse($bm->ngay_tao)->timestamp
        ];
    });
@endphp

<script>
let formData      = @json($mappedBieuMaus);
let filteredData  = [...formData];
let rowsPerPage   = 7;
let currentPage   = 1;
let sortColumn    = 'ngay_raw';
let sortDirection = 'desc';

const viCollator = new Intl.Collator('vi', { sensitivity: 'base' });
const compare    = (a, b, dir = 'asc') => (a === b ? 0 : (a > b ? 1 : -1)) * (dir === 'asc' ? 1 : -1);
const compareStr = (a, b, dir = 'asc') => viCollator.compare(a, b) * (dir === 'asc' ? 1 : -1);

function applySort() {
    filteredData.sort((a, b) => {
        const valA = a[sortColumn], valB = b[sortColumn];
        switch (sortColumn) {
            case 'tieu_de':
            case 'mau':
            case 'nguoi_tao':
                return compareStr(valA, valB, sortDirection);
            default:
                return compare(valA, valB, sortDirection);
        }
    });
}

function renderTable() {
    const tbody = document.getElementById('form-body');
    tbody.innerHTML = '';
    const start = (currentPage - 1) * rowsPerPage;
    const rows = filteredData.slice(start, start + rowsPerPage);

    if (!rows.length) {
        tbody.innerHTML = `<tr><td colspan="5" style="text-align:center;padding:20px;">Không có dữ liệu</td></tr>`;
        return;
    }

    rows.forEach(r => {
        const tr = document.createElement('tr');
        tr.style.cssText = 'background:#fff;border-bottom:1px solid #eee;';
        tr.innerHTML = `
            <td style="padding:12px;">${r.tieu_de}</td>
            <td style="padding:12px;">
                <span style="display:inline-block;width:12px;height:12px;border-radius:4px;margin-right:6px;background:${r.mau}"></span>
                ${r.mau}
            </td>
            <td style="padding:12px;">${r.nguoi_tao}</td>
            <td style="padding:12px;">${r.ngay_tao}</td>
            <td style="padding:12px;">
                <button type="button" style="border:none;background:none;color:#0d6efd;cursor:pointer;"
                        onclick="window.location.href='bieumau/tao/${r.ma_bieu_mau}'">
                    <i class="bi bi-eye fs-5"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

function renderPagination() {
    const container = document.getElementById('pagination');
    container.innerHTML = '';
    const total = Math.ceil(filteredData.length / rowsPerPage);
    if (total <= 1) return;

    const addBtn = (p, label = p) => {
        const btn = document.createElement('button');
        btn.textContent = label;
        btn.style.cssText = `
            padding:8px 14px;border-radius:12px;
            background:${p === currentPage ? '#3b82f6' : '#f1f5f9'};
            color:${p === currentPage ? '#fff' : '#1e293b'};
            font-weight:${p === currentPage ? 600 : 500};
            font-size:14px;border:none;cursor:pointer;box-shadow:0 2px 6px rgba(0,0,0,0.06);
        `;
        btn.onclick = () => { currentPage = p; renderTable(); renderPagination(); }
        container.appendChild(btn);
    };

    const addDots = () => {
        const span = document.createElement('span');
        span.textContent = '…';
        span.style.cssText = 'padding:8px 6px;font-size:14px;color:#64748b;';
        container.appendChild(span);
    };

    const pages = [1, total];
    for (let i = currentPage - 1; i <= currentPage + 1; i++) {
        if (i > 1 && i < total) pages.push(i);
    }
    [...new Set(pages)].sort((a, b) => a - b).forEach((p, i, arr) => {
        if (i > 0 && p - arr[i - 1] > 1) addDots();
        addBtn(p);
    });
}

function applySearch(keyword) {
    const val = keyword.toLowerCase();
    filteredData = formData.filter(row =>
        row.tieu_de.toLowerCase().includes(val) ||
        row.mau.toLowerCase().includes(val) ||
        row.nguoi_tao.toLowerCase().includes(val) ||
        row.ngay_tao.includes(val)
    );
    currentPage = 1;
    applySort();
    renderTable();
    renderPagination();
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('searchInput').addEventListener('input', e => applySearch(e.target.value));

    document.getElementById('rowsPerPageSelect').addEventListener('change', e => {
        rowsPerPage = +e.target.value;
        currentPage = 1;
        renderTable();
        renderPagination();
    });

    document.querySelectorAll('select[id^="sort-"]').forEach(sel => {
        sel.addEventListener('change', () => {
            document.querySelectorAll('select[id^="sort-"]').forEach(s => { if (s !== sel) s.value = ''; });

            if (!sel.value) {
                sortColumn = 'ngay_raw';
                sortDirection = 'desc';
            } else {
                switch (sel.id) {
                    case 'sort-title': sortColumn = 'tieu_de'; break;
                    case 'sort-color': sortColumn = 'mau'; break;
                    case 'sort-author': sortColumn = 'nguoi_tao'; break;
                    case 'sort-date': sortColumn = 'ngay_raw'; break;
                }
                sortDirection = (sel.value === 'az' || sel.value === 'old') ? 'asc' : 'desc';
            }

            currentPage = 1;
            applySort();
            renderTable();
            renderPagination();
        });
    });

    applySort();
    renderTable();
    renderPagination();
});
</script>
@endpush
