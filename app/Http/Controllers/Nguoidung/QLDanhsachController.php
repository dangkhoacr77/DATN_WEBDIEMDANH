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
            ->orderByDesc('ngay_tao')
            ->get();

        return view('nguoidung.QL_danhsach', compact('danhSach'));
    }

    public function export($id)
    {
        $ds = DanhSachDiemDanh::findOrFail($id);
        $diemDanhs = $ds->diemDanhs()->with(['cauTraLoi', 'taiKhoan'])->get();

        // Lấy danh sách câu hỏi theo biểu mẫu và sắp xếp theo mã câu hỏi
        $cauHoiList = $ds->bieuMau->cauHois()->orderBy('ma_cau_hoi')->get();
        $cauHoiLabels = $cauHoiList->pluck('cau_hoi')->toArray();
        $cauHoiIds = $cauHoiList->pluck('ma_cau_hoi')->toArray();

        $filename = Str::slug($ds->ten_danh_sach) . '.csv';
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

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

                // Đảm bảo lấy câu trả lời theo đúng mã câu hỏi
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

    public function preview($id)
    {
        $ds = DanhSachDiemDanh::findOrFail($id);
        $diemDanhs = $ds->diemDanhs()->with(['cauTraLoi', 'taiKhoan'])->get();

        // Lấy danh sách câu hỏi theo mã câu hỏi
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

        return response()->json([
            'success' => true,
            'rows' => $rows,
            'labels' => $labels
        ]);
    }

    public function destroy(Request $request)
    {
        DanhSachDiemDanh::whereIn('ma_danh_sach', $request->ids)->delete();
        return response()->json(['success' => true]);
    }
}
