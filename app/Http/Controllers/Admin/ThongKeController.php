<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\BieuMau;
use App\Models\TaiKhoan;
use Illuminate\Support\Carbon;

class ThongKeController extends Controller
{
    /**
     * Trang thống kê.
     */
    public function index()
    {
        // ===============================
        // 1. Lượt truy cập theo tháng
        // ===============================
        $luotTruyCapTheoThang = DB::table('luot_truy_cap')
            ->whereYear('thoi_gian', now()->year)
            ->selectRaw('MONTH(thoi_gian) as thang, COUNT(*) as tong')
            ->groupByRaw('MONTH(thoi_gian)')
            ->pluck('tong', 'thang') // [thang => tong]
            ->toArray();

        $visitCounts = array_map(
            fn ($i) => $luotTruyCapTheoThang[$i] ?? 0,
            range(1, 12)
        );

        // ===============================
        // 2. Biểu mẫu được tạo theo tháng
        // ===============================
        $bieuMauTheoThang = BieuMau::query()
            ->whereYear('ngay_tao', now()->year)
            ->selectRaw('MONTH(ngay_tao) as thang, COUNT(*) as tong')
            ->groupByRaw('MONTH(ngay_tao)')
            ->pluck('tong', 'thang')
            ->toArray();

        $bieuMauData = array_map(
            fn ($i) => $bieuMauTheoThang[$i] ?? 0,
            range(1, 12)
        );

        // ===============================
        // 3. Tài khoản được tạo theo tháng
        // ===============================
        $taiKhoanTheoThang = TaiKhoan::query()
            ->whereYear('ngay_tao', now()->year)
            ->selectRaw('MONTH(ngay_tao) as thang, COUNT(*) as tong')
            ->groupByRaw('MONTH(ngay_tao)')
            ->pluck('tong', 'thang')
            ->toArray();

        $accountCreatedData = array_map(
            fn ($i) => $taiKhoanTheoThang[$i] ?? 0,
            range(1, 12)
        );

        // ===============================
        // Trả dữ liệu về view
        // ===============================
        return view('admin.Thong_ke', [
            'visitCounts'        => $visitCounts,
            'bieuMauData'        => $bieuMauData,
            'accountCreatedData' => $accountCreatedData,
        ]);
    }
}
