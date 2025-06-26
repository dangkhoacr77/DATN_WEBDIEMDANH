@extends('layout.admin')

@section('title', 'Quản lý Biểu mẫu')

@section('page-title', 'Quản lý biểu mẫu')

@section('content')
    <div style="background: white; border-radius: 16px; padding: 40px; max-width: 100%; width: 95%; margin: auto;">
        <div style="margin-bottom: 12px;">
            <input id="searchInput"
                   style="width: 240px; border-radius: 12px; border: 1px solid #ddd; padding: 10px 14px;"
                   type="text" placeholder="🔍 Tìm kiếm..." onkeyup="filterTable()">
        </div>

        <!-- Table -->
        <div id="table-container" style="overflow-x: auto;">
            <table id="form-table" style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <thead>
                <tr style="background: #f1f5f9;">
                    <th style="text-align:left; padding: 12px;">#</th>
                    <th style="text-align:left; padding: 12px;">Tiêu đề</th>
                    <th style="text-align:left; padding: 12px;">Màu</th>
                    <th style="text-align:left; padding: 12px;">Người tạo</th>
                    <th style="text-align:left; padding: 12px;">Ngày tạo</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($bieuMaus as $index => $bm)
                    <tr>
                        <td style="padding: 12px;">
                            {{ $loop->iteration + ($bieuMaus->currentPage() - 1) * $bieuMaus->perPage() }}
                        </td>
                        <td style="padding: 12px;">{{ $bm->tieu_de }}</td>
                        <td style="padding: 12px;">{{ $bm->mau }}</td>
                        <td style="padding: 12px;">{{ $bm->taiKhoan->ho_ten ?? 'Không rõ' }}</td>
                        <td style="padding: 12px;">
                            {{ \Carbon\Carbon::parse($bm->ngay_tao)->format('d/m/Y') }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Custom Pagination -->
        @if ($bieuMaus->hasPages())
            <div style="margin-top: 24px; display: flex; justify-content: center;">
                <nav class="custom-pagination">
                    {{-- Previous Page --}}
                    @if ($bieuMaus->onFirstPage())
                        <span class="page disabled">←</span>
                    @else
                        <a href="{{ $bieuMaus->previousPageUrl() }}" class="page">←</a>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($bieuMaus->getUrlRange(1, $bieuMaus->lastPage()) as $page => $url)
                        @if ($page == $bieuMaus->currentPage())
                            <span class="page active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next Page --}}
                    @if ($bieuMaus->hasMorePages())
                        <a href="{{ $bieuMaus->nextPageUrl() }}" class="page">→</a>
                    @else
                        <span class="page disabled">→</span>
                    @endif
                </nav>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<style>
    .custom-pagination {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
    }

    .custom-pagination .page {
        padding: 8px 14px;
        border-radius: 12px;
        background: #f1f5f9;
        color: #1e293b;
        font-weight: 500;
        font-size: 14px;
        text-decoration: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
        transition: all 0.2s ease-in-out;
    }

    .custom-pagination .page:hover:not(.active):not(.disabled) {
        background: #3b82f6;
        color: white;
    }

    .custom-pagination .page.active {
        background: #3b82f6;
        color: white;
        font-weight: 600;
    }

    .custom-pagination .page.disabled {
        opacity: 0.5;
        pointer-events: none;
    }
</style>

<script>
    function filterTable() {
        const keyword = document.getElementById("searchInput").value.toLowerCase().trim();
        const rows = document.querySelectorAll("#form-table tbody tr");

        rows.forEach(row => {
            const cells = Array.from(row.querySelectorAll("td"));
            const matches = cells.some(cell => cell.textContent.toLowerCase().includes(keyword));
            row.style.display = matches ? "" : "none";
        });
    }
</script>
@endpush
