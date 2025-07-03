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
use Illuminate\Support\Facades\Storage;



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
     * Xuất bản biểu mẫu chính thức
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
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
                 'hinh_anh' => $request->background_image ?? null, 
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
                if (!isset($q['title'])) continue;

                CauHoi::create([
                    'ma_cau_hoi' => (string) Str::uuid(),
                    'cau_hoi' => $q['title'],
                    'cau_hoi_bat_buoc' => $q['required'] ?? false,
                    'noi_dung' => '',
                    'bieu_mau_ma' => $maBieuMau
                ]);
            }

            // Tạo danh sách điểm danh mặc định
            DanhSachDiemDanh::create([
                'ma_danh_sach' => (string) Str::uuid(),
                'ten_danh_sach' => $bieuMau->tieu_de,
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
    public function edit($ma_bieu_mau)
{
    $bieumau = BieuMau::where('ma_bieu_mau', $ma_bieu_mau)->firstOrFail();
    $cauhois = CauHoi::where('bieu_mau_ma', $ma_bieu_mau)->get();

    // Map tên màu sang mã màu hex
    $colorNameToHex = [
    'Trắng' => '#ffffff',
        'Đỏ' => '#fca5a5',
        'Tím' => '#c4b5fd',
        'Xanh dương đậm' => '#93c5fd',
        'Xanh trời' => '#a5f3fc',
        'Cam' => '#fdba74',
        'Vàng đậm' => '#fde68a',
        'Xanh ngọc' => '#99f6e4',
        'Xanh lá' => '#86efac',
        'Xám nhạt' => '#d1d5db'
];

// Đảo ngược để ánh xạ mã màu → tên
$hexToColorName = array_flip($colorNameToHex);

    return view('Bieumau.Tao_form', [
        'bieumau' => $bieumau,
        'cauhois' => $cauhois,
    ]);

}

    // Các hàm khác nếu cần
}
