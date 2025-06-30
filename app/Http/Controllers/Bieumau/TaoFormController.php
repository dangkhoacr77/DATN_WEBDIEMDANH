<?php

namespace App\Http\Controllers\Bieumau;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\BieuMau;
use App\Models\CauHoi;
use App\Models\DanhSachDiemDanh;
use Illuminate\Support\Facades\Log;
use App\Models\MaQR;



class TaoFormController extends Controller
{
    /**
     * Hiển thị giao diện tạo biểu mẫu
     */
    public function index()
    {
        return view('Bieumau.Tao_form');
    }

    /**
     * Lưu biểu mẫu ở dạng nháp (nếu cần)
     */
    public function store(Request $request)
    {
        $taiKhoanMa = session('nguoi_dung')['ma_tai_khoan'] ?? session('ma_tai_khoan');

        if (!$taiKhoanMa) {
            return response()->json(['success' => false, 'message' => 'Chưa đăng nhập']);
        }

        $maBieuMau = 'BM' . Str::uuid()->toString();

        BieuMau::create([
            'ma_bieu_mau' => $maBieuMau,
            'tieu_de' => $request->title,
            'mo_ta_tieu_de' => $request->description,
            'mau' => $request->theme_color ?? '#ffffff',
            'thoi_luong_diem_danh' => $request->time_limit ?? 0,
            'gioi_han_diem_danh' => $request->participant_limit ?? 0,
            'so_luong_da_diem_danh' => 0,
            'trang_thai' => 0, // 0 = nháp
            'ngay_tao' => Carbon::now(),
            'tai_khoan_ma' => $taiKhoanMa,
        ]);

        return response()->json(['success' => true, 'message' => 'Lưu nháp thành công']);
    }

    /**
     * Xuất bản biểu mẫu chính thức
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
   public function publish(Request $request)
{
    \Log::info('Dữ liệu gửi lên:', $request->all());
    $maTaiKhoan = session('ma_tai_khoan');

    if (!$maTaiKhoan) {
        return response()->json(['success' => false, 'message' => 'Bạn chưa đăng nhập']);
    }
    
    // Tạo mã BM001, BM002...
    $last = BieuMau::orderBy('ma_bieu_mau', 'desc')->first();
    $nextNumber = $last ? ((int)substr($last->ma_bieu_mau, 2)) + 1 : 1;
    $ma_bieu_mau = 'BM' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

    // Kiểm tra dữ liệu đầu vào
    if (!$request->title || !is_array($request->questions) || count($request->questions) === 0) {
        return response()->json(['success' => false, 'message' => 'Dữ liệu biểu mẫu không hợp lệ']);
    }

    try {
        DB::beginTransaction();

        $maBieuMau = (string) Str::uuid();

        // Tạo biểu mẫu
        $bieuMau = BieuMau::create([
            'ma_bieu_mau' => $maBieuMau,
            'tieu_de' => $request->title,
            'mo_ta_tieu_de' => $request->description,
            'mau' => $request->theme_color ?? '#ffffff',
            'thoi_luong_diem_danh' => $request->time_limit ?? 0,
            'gioi_han_diem_danh' => $request->participant_limit ?? 0,
            'so_luong_da_diem_danh' => 0,
            'trang_thai' => 1,
            'ngay_tao' => now(),
            'tai_khoan_ma' => $maTaiKhoan,
        ]);
// ✅ Tạo bản ghi mã QR ứng với biểu mẫu
MaQR::create([
    'ma_qr' => (string) Str::uuid(),
    'hinh_anh' => null,
    'duong_dan' => url('/traloi-bieumau/' . $maBieuMau),
    'trang_thai' => 1,
    'ngay_tao' => now(),
    'bieu_mau_ma' => $maBieuMau
]);

        // Lưu câu hỏi
        foreach ($request->questions as $q) {
            if (!isset($q['title']) || !isset($q['type'])) continue;

            CauHoi::create([
                'ma_cau_hoi' => (string) Str::uuid(),
                'cau_hoi' => $q['title'],
                'cau_hoi_bat_buoc' => $q['required'] ?? false,
                'noi_dung' => '',
                'loai_cau_hoi' => $q['type'],
                'bieu_mau_ma' => $maBieuMau
            ]);
        }

        // Tạo danh sách điểm danh mặc định
        DanhSachDiemDanh::create([
            'ma_danh_sach' => (string) Str::uuid(),
            'ten_danh_sach' => 'Danh sách ' . $bieuMau->tieu_de,
            'du_lieu_ds' => '[]',
            'ngay_tao' => now(),
            'thoi_gian_tao' => now(),
            'bieu_mau_ma' => $maBieuMau,
            'tai_khoan_ma' => $maTaiKhoan,
        ]);

        DB::commit();

       return response()->json([
    'success' => true,
    'ma_bieu_mau' => $maBieuMau
]);


    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Lỗi xuất bản biểu mẫu: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Đã xảy ra lỗi hệ thống']);
    }
}


    // Các hàm khác nếu cần
}
