<?php

namespace App\Http\Controllers\Nguoidung;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DanhSachDiemDanh;
use Illuminate\Support\Str;

class QLDanhsachController extends Controller
{
    public function index(Request $request)
    {
        $nguoiDung = session('nguoi_dung');

        $danhSach = DanhSachDiemDanh::where('tai_khoan_ma', $nguoiDung['ma_tai_khoan'])
            ->where('trang_thai', 1)
            ->orderByDesc('ngay_tao')
            ->get();

        return view('nguoidung.QL_danhsach', compact('danhSach'));
    }

   public function export($id)
{
    $ds = DanhSachDiemDanh::where('ma_danh_sach', $id)
        ->where('trang_thai', 1)
        ->firstOrFail();

    $bieuMau = $ds->bieuMau;
    $loai = $bieuMau->loai;
    $filename = Str::slug($ds->ten_danh_sach) . '.csv';

    $headers = [
        "Content-type" => "text/csv",
        "Content-Disposition" => "attachment; filename=$filename",
        "Pragma" => "no-cache",
        "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
        "Expires" => "0"
    ];

    // ===== LOẠI 1: xuất từ dữ liệu điểm danh =====
    if ($loai == 1) {
        $diemDanhs = $ds->diemDanhs()->with(['cauTraLoi', 'taiKhoan'])->get();

        $cauHoiList = $bieuMau->cauHois()->orderBy('ma_cau_hoi')->get();
        $cauHoiLabels = $cauHoiList->pluck('cau_hoi')->toArray();
        $cauHoiIds = $cauHoiList->pluck('ma_cau_hoi')->toArray();

        $fixedCols = ['Email', 'Thời gian', 'Thiết bị', 'Định vị'];
        $allHeaders = array_merge($fixedCols, $cauHoiLabels);

        $callback = function () use ($diemDanhs, $allHeaders, $cauHoiIds) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8
            fputcsv($file, $allHeaders, ';');

            foreach ($diemDanhs as $dd) {
                $row = [
                    $dd->taiKhoan->mail ?? '',
                    $dd->thoi_gian_diem_danh,
                    $dd->thiet_bi_diem_danh,
                    $dd->dinh_vi_thiet_bi,
                ];

                $traLoiMap = $dd->cauTraLoi->keyBy('cau_hoi_ma');
                foreach ($cauHoiIds as $id) {
                    $row[] = $traLoiMap[$id]->cau_tra_loi ?? '';
                }

                fputcsv($file, $row, ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ===== LOẠI 2: xuất từ dữ liệu Excel đã nhập =====
    if ($loai == 2) {
        $duLieuDs = json_decode($ds->du_lieu_ds, true);
        if (!$duLieuDs || !is_array($duLieuDs)) {
            return response()->json(['success' => false, 'message' => 'Không có dữ liệu để xuất']);
        }

        // Lấy tiêu đề từ keys của dòng đầu tiên
        $headersRow = array_keys($duLieuDs[0]);

        $callback = function () use ($duLieuDs, $headersRow) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM UTF-8
            fputcsv($file, $headersRow, ';');

            foreach ($duLieuDs as $row) {
                $values = array_map(function ($key) use ($row) {
                    return $row[$key] ?? '';
                }, $headersRow);

                fputcsv($file, $values, ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Mặc định (nếu loại không hợp lệ)
    return response()->json(['success' => false, 'message' => 'Loại biểu mẫu không hợp lệ']);
}


    public function destroy(Request $request)
    {
        DanhSachDiemDanh::whereIn('ma_danh_sach', $request->ids)
            ->update(['trang_thai' => 0]);

        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $ds = DanhSachDiemDanh::findOrFail($id);
        $loai = $ds->bieuMau->loai;

        if ($loai == 1) {
            // Biểu mẫu thường
            $diemDanhs = $ds->diemDanhs()->with(['cauTraLoi', 'taiKhoan'])->orderByDesc('thoi_gian_diem_danh')->get();
            $cauHoiList = $ds->bieuMau->cauHois()->orderBy('ma_cau_hoi')->get();

            $labels = $cauHoiList->pluck('cau_hoi')->toArray();
            $cauHoiIds = $cauHoiList->pluck('ma_cau_hoi')->toArray();

            $rows = $diemDanhs->map(function ($dd) use ($cauHoiIds) {
                $traLoiMap = $dd->cauTraLoi->keyBy('cau_hoi_ma');
                return [
                    'email' => $dd->taiKhoan->mail ?? '',
                    'thoi_gian' => $dd->thoi_gian_diem_danh,
                    'thiet_bi' => $dd->thiet_bi_diem_danh,
                    'dinh_vi' => $dd->dinh_vi_thiet_bi,
                    'cau_tra_loi' => array_map(fn($id) => $traLoiMap[$id]->cau_tra_loi ?? '', $cauHoiIds)
                ];
            });
        } else {
            // Biểu mẫu điểm danh theo ngày
            $duLieu = json_decode($ds->du_lieu_ds, true) ?? [];
            $labels = array_keys($duLieu[0] ?? []);
            $rows = $duLieu;
        }

        return view('nguoidung.chitiet_ds', compact('ds', 'labels', 'rows'));
    }
}
