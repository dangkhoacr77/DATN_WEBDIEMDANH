<?php

namespace App\Http\Controllers\Nguoidung;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DanhSachDiemDanh;
use App\Models\DiemDanh;
use App\Models\CauTraLoi;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class QLDanhsachController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $nguoiDung = session('nguoi_dung');

        // Không lọc ở đây nữa vì lọc client-side bằng JavaScript
        $danhSach = DanhSachDiemDanh::where('tai_khoan_ma', $nguoiDung['ma_tai_khoan'])
            ->orderByDesc('ngay_tao')
            ->get(); // ⬅️ Lấy toàn bộ, không phân trang

        return view('nguoidung.QL_danhsach', compact('danhSach'));
    }
    public function export($id)
    {
        $ds = DanhSachDiemDanh::findOrFail($id);
        // Lấy điểm danh kèm cả tài khoản (để có email) và câu trả lời
        $diemDanhs = $ds->diemDanhs()->with(['cauTraLoi', 'taiKhoan'])->get();

        $filename = Str::slug($ds->ten_danh_sach) . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        // Cột cố định
        $columns = ['Email', 'Thời gian', 'Thiết bị', 'Định vị'];

        // Callback để xuất file
        $callback = function () use ($diemDanhs, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns, ';');

            foreach ($diemDanhs as $dd) {
                $row = [
                    $dd->taiKhoan->mail ?? '', // Lấy email từ quan hệ
                    $dd->thoi_gian_diem_danh,
                    $dd->thiet_bi_diem_danh,
                    $dd->dinh_vi_thiet_bi,
                ];

                // Gắn thêm các câu trả lời (nếu cần)
                foreach ($dd->cauTraLoi as $ctl) {
                    $row[] = $ctl->cau_tra_loi;
                }

                fputcsv($file, $row, ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        DanhSachDiemDanh::whereIn('ma_danh_sach', $request->ids)->delete();
        return response()->json(['success' => true]);
    }
}
