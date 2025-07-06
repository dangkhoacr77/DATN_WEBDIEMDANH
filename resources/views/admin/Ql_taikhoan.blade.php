@extends('layout.admin')

@section('title', 'Quản lý Tài khoản')
@section('page-title', 'Quản lý tài khoản')

@push('head')
<style>
    .sort-select {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 14px;
        background: #fff;
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div style="background:#fff;border-radius:16px;padding:40px;width:95%;margin:auto;">
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
    <div style="overflow-x:auto;">
        <table style="width:100%;border-collapse:collapse;font-size:18px;">
            <thead style="background:#f1f5f9;">
            <tr>
                <th style="padding:12px;">
                    <div style="display:flex;align-items:center;gap:6px;">
                        <span style="font-weight:bold;">Tên</span>
                        <select id="sort-name" class="sort-select">
                            <option value="">Chọn</option>
                            <option value="az">A → Z</option>
                            <option value="za">Z → A</option>
                        </select>
                    </div>
                </th>

                <th style="padding:12px;">
                    <div style="display:flex;align-items:center;gap:6px;">
                        <span style="font-weight:bold;">Loại</span>
                        <select id="sort-role" class="sort-select">
                            <option value="">Chọn</option>
                            <option value="asc">admin</option>
                            <option value="desc">người dùng</option>
                        </select>
                    </div>
                </th>

                <th style="padding:12px;">
                    <div style="display:flex;align-items:center;gap:6px;">
                        <span style="font-weight:bold;">SĐT</span>
                        <select id="sort-phone" class="sort-select">
                            <option value="">Chọn</option>
                            <option value="az">Tăng</option>
                            <option value="za">Giảm</option>
                        </select>
                    </div>
                </th>

                <th style="padding:12px;">
                    <div style="display:flex;align-items:center;gap:6px;">
                        <span style="font-weight:bold;">Email</span>
                        <select id="sort-mail" class="sort-select">
                            <option value="">Chọn</option>
                            <option value="az">A → Z</option>
                            <option value="za">Z → A</option>
                        </select>
                    </div>
                </th>

                <th style="padding:12px;">
                    <div style="display:flex;align-items:center;gap:6px;">
                        <span style="font-weight:bold;">Ngày tạo</span>
                        <select id="sort-date" class="sort-select">
                            <option value="">Chọn</option>
                            <option value="new">Mới</option>
                            <option value="old">Cũ</option>
                        </select>
                    </div>
                </th>

                <th style="padding:12px;">
                    <div style="display:flex;align-items:center;gap:6px;">
                        <span style="font-weight:bold;">Trạng thái</span>
                        <select id="sort-status" class="sort-select">
                            <option value="">Chọn</option>
                            <option value="asc">Hoạt động</option>
                            <option value="desc">Khóa</option>
                        </select>
                    </div>
                </th>

                <th style="width:120px;"></th>
            </tr>
            </thead>
            <tbody id="account-body"></tbody>
        </table>
    </div>

    <div id="pagination" style="display:flex;justify-content:center;gap:8px;margin-top:24px;flex-wrap:wrap;"></div>
</div>

<div id="alertBox"
     style="display:none;position:fixed;top:20px;right:20px;background:#22c55e;color:#fff;
            padding:12px 24px;border-radius:8px;box-shadow:0 2px 10px rgba(0,0,0,.2);font-size:14px;z-index:1000;">
    ✅ Cập nhật thành công!
</div>
@endsection

@push('scripts')
@php
    $mappedTaiKhoans = $taiKhoans->map(function ($tk) {
        return [
            'ma_tai_khoan'  => $tk->ma_tai_khoan,
            'ho_ten'        => $tk->ho_ten,
            'loai_tai_khoan'=> $tk->loai_tai_khoan,
            'so_dien_thoai' => $tk->so_dien_thoai,
            'mail'          => $tk->mail,
            'ngay_tao'      => \Carbon\Carbon::parse($tk->ngay_tao)->format('d/m/Y'),
            'ngay_raw'      => \Carbon\Carbon::parse($tk->ngay_tao)->timestamp,
            'trang_thai'    => $tk->trang_thai==1 ? 'Hoạt động':'Khóa',
            'isEditing'     => false
        ];
    });
@endphp

<script>
let data = @json($mappedTaiKhoans);
let filteredData = [...data];
let rowsPerPage = 7;
let currentPage = 1;
let sortColumn = 'ngay_raw';
let sortDirection = 'desc';

const compare = (a, b, dir = 'asc') => (a === b ? 0 : a > b ? 1 : -1) * (dir === 'asc' ? 1 : -1);
const roleWeight = v => v === 'admin' ? 0 : 1;
const statusWeight = v => v === 'Hoạt động' ? 0 : 1;

// ✅ Bổ sung bảng ánh xạ cho các giá trị value trong select
const dirMap = { az:'asc', za:'desc', asc:'asc', desc:'desc', old:'asc', new:'desc' };

function applySort() {
    filteredData.sort((a, b) => {
        switch (sortColumn) {
            case 'loai_tai_khoan':
                return compare(roleWeight(a.loai_tai_khoan), roleWeight(b.loai_tai_khoan), sortDirection);
            case 'trang_thai':
                return compare(statusWeight(a.trang_thai), statusWeight(b.trang_thai), sortDirection);
            case 'ho_ten':
            case 'mail':
                return compare(a[sortColumn].toLowerCase(), b[sortColumn].toLowerCase(), sortDirection);
            case 'so_dien_thoai':
                // ✅ So sánh theo giá trị số thực
                return compare(Number(a.so_dien_thoai), Number(b.so_dien_thoai), sortDirection);
            case 'ngay_raw':
            default:
                return compare(a.ngay_raw, b.ngay_raw, sortDirection);
        }
    });
}

function renderTable() {
    const tbody = document.getElementById('account-body');
    tbody.innerHTML = '';
    const start = (currentPage - 1) * rowsPerPage;
    const rows = filteredData.slice(start, start + rowsPerPage);

    if (!rows.length) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:20px;">Không có dữ liệu</td></tr>';
        return;
    }

    rows.forEach(row => {
        const dataIdx = data.findIndex(d => d.ma_tai_khoan === row.ma_tai_khoan);
        const tr = document.createElement('tr');
        tr.style.cssText = 'background:#fff;border-bottom:1px solid #eee;';

        if (row.isEditing) {
            tr.innerHTML = `
                <td style="padding:12px;">${row.ho_ten}</td>
                <td style="padding:12px;"><select id="edit-role-${dataIdx}" style="padding:6px 12px;border-radius:6px;">
                    ${['admin','nguoi_dung'].map(l=>`<option value="${l}" ${row.loai_tai_khoan===l?'selected':''}>${l}</option>`).join('')}
                </select></td>
                <td style="padding:12px;">${row.so_dien_thoai}</td>
                <td style="padding:12px;">${row.mail}</td>
                <td style="padding:12px;">${row.ngay_tao}</td>
                <td style="padding:12px;"><select id="edit-status-${dataIdx}" style="padding:6px 12px;border-radius:6px;">
                    ${['Hoạt động','Khóa'].map(st=>`<option value="${st}" ${row.trang_thai===st?'selected':''}>${st}</option>`).join('')}
                </select></td>
                <td style="padding:12px;"><button onclick="saveRow(${dataIdx},'${row.ma_tai_khoan}')" 
                    style="padding:6px 12px;background:#22c55e;color:#fff;border:none;border-radius:6px;">Cập nhật</button></td>`;
        } else {
            tr.innerHTML = `
                <td style="padding:12px;">${row.ho_ten}</td>
                <td style="padding:12px;">${row.loai_tai_khoan}</td>
                <td style="padding:12px;">${row.so_dien_thoai}</td>
                <td style="padding:12px;">${row.mail}</td>
                <td style="padding:12px;">${row.ngay_tao}</td>
                <td style="padding:12px;">${row.trang_thai}</td>
                <td style="padding:12px;"><button onclick="editRow(${dataIdx})" 
                    style="padding:6px 12px;background:#3b82f6;color:#fff;border:none;border-radius:6px;">Sửa</button></td>`;
        }
        tbody.appendChild(tr);
    });
}

function renderPagination() {
    const pg = document.getElementById('pagination');
    pg.innerHTML = '';
    const total = Math.ceil(filteredData.length / rowsPerPage);
    if (total <= 1) return;

    const addBtn = (p, label = p) => {
        const b = document.createElement('button');
        b.textContent = label;
        b.style.cssText = `
            padding:8px 14px;border-radius:12px;
            background:${p === currentPage ? '#3b82f6' : '#f1f5f9'};
            color:${p === currentPage ? '#fff' : '#1e293b'};
            font-weight:${p === currentPage ? 600 : 500};
            font-size:14px;border:none;cursor:pointer;box-shadow:0 2px 6px rgba(0,0,0,.06);`;
        b.onclick = () => { currentPage = p; renderTable(); renderPagination(); };
        pg.appendChild(b);
    };

    const addDots = () => { pg.insertAdjacentHTML('beforeend', '<span style="padding:8px 6px;color:#64748b;">…</span>'); };

    const pages = [1, total];
    for (let i = currentPage - 1; i <= currentPage + 1; i++) if (i > 1 && i < total) pages.push(i);
    [...new Set(pages)].sort((a, b) => a - b).forEach((p, i, arr) => {
        if (i && p - arr[i - 1] > 1) addDots();
        addBtn(p);
    });
}

function applySearch(key) {
    const v = key.toLowerCase();
    filteredData = data.filter(r =>
        r.ho_ten.toLowerCase().includes(v) ||
        r.mail.toLowerCase().includes(v) ||
        r.so_dien_thoai.toLowerCase().includes(v) ||
        r.loai_tai_khoan.toLowerCase().includes(v) ||
        r.trang_thai.toLowerCase().includes(v) ||
        r.ngay_tao.includes(v)
    );
    currentPage = 1;
    applySort();
    renderTable();
    renderPagination();
}

function editRow(idx) {
    data[idx].isEditing = true;
    renderTable();
}

function saveRow(idx, maTK) {
    if (!confirm('⚠️ Bạn chắc muốn cập nhật tài khoản này?')) return;
    const roleEl = document.getElementById(`edit-role-${idx}`);
    const stEl = document.getElementById(`edit-status-${idx}`);
    const statusVal = stEl.value === 'Hoạt động' ? 1 : 0;

    fetch(`/admin/ql-taikhoan/${maTK}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ loai_tai_khoan: roleEl.value, trang_thai: statusVal })
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            data[idx].loai_tai_khoan = roleEl.value;
            data[idx].trang_thai = stEl.value;
            data[idx].isEditing = false;
            showAlert('✅ Cập nhật thành công!');
            applySort();
            renderTable();
        } else showAlert('❌ Cập nhật thất bại!');
    })
    .catch(() => showAlert('❌ Có lỗi xảy ra!'));
}

function showAlert(msg, duration = 3000) {
    const box = document.getElementById('alertBox');
    box.textContent = msg;
    box.style.display = 'block';
    setTimeout(() => box.style.display = 'none', duration);
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('searchInput').addEventListener('input', e => applySearch(e.target.value));

    document.getElementById('rowsPerPageSelect').addEventListener('change', e => {
        rowsPerPage = +e.target.value;
        currentPage = 1;
        renderTable();
        renderPagination();
    });

    document.querySelectorAll('.sort-select').forEach(sel => {
        sel.addEventListener('change', () => {
            document.querySelectorAll('.sort-select').forEach(s => { if (s !== sel) s.value = ''; });

            if (!sel.value) {
                sortColumn = 'ngay_raw';
                sortDirection = 'desc';
            } else {
                switch (sel.id) {
                    case 'sort-name': sortColumn = 'ho_ten'; break;
                    case 'sort-role': sortColumn = 'loai_tai_khoan'; break;
                    case 'sort-phone': sortColumn = 'so_dien_thoai'; break;
                    case 'sort-mail': sortColumn = 'mail'; break;
                    case 'sort-date': sortColumn = 'ngay_raw'; break;
                    case 'sort-status': sortColumn = 'trang_thai'; break;
                }
                // ✅ Sửa lại dòng này
                sortDirection = dirMap[sel.value] ?? 'asc';
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
