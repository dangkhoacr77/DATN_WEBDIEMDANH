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
        return view('Bieumau.Tao_form', [
            'isCreating' => true
        ]);
    }

    /**
     * Xuất bản biểu mẫu chính thức
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        Log::info('REQUEST DATA', $request->all());
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

            $mau = $request->theme_color;
            if (!$mau && $request->background_image) {
                $mau = 'hình ảnh';
            }

            // Tạo biểu mẫu
            $bieuMau = BieuMau::create([
                'ma_bieu_mau' => $maBieuMau,
                'tieu_de' => $request->title,
                'mo_ta_tieu_de' => $request->description,
                'mau' => $mau,
                'hinh_anh' => $request->background_image ?? null,
                'thoi_luong_diem_danh' => $request->time_limit ?? null,
                'gioi_han_diem_danh' => $request->participant_limit ?? null,
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
                'trang_thai' => 1,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'ma_bieu_mau' => $maBieuMau
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Đã xảy ra lỗi hệ thống']);
        }
    }

    public function show($ma_bieu_mau)
    {
        $bieumau = BieuMau::where('ma_bieu_mau', $ma_bieu_mau)->firstOrFail();
        $cauhois = CauHoi::where('bieu_mau_ma', $ma_bieu_mau)->get();

        // Danh sách tên màu → mã màu hex
        $colorNameToHex = [
            'Xanh dương đậm' => '#93c5fd',
            'Đỏ' => '#fca5a5',
            'Tím' => '#c4b5fd',
            'Xanh trời' => '#a5f3fc',
            'Cam' => '#fdba74',
            'Vàng đậm' => '#fde68a',
            'Xanh ngọc' => '#99f6e4',
            'Xanh lá' => '#86efac',
            'Xám nhạt' => '#d1d5db'
        ];

        // Xử lý màu nền
        $mauHex = isset($colorNameToHex[$bieumau->mau]) ? $colorNameToHex[$bieumau->mau] : (
            preg_match('/^#([A-Fa-f0-9]{6})$/', $bieumau->mau) ? $bieumau->mau : null
        );

        // Xử lý hình nền nếu có
        $hinhNen = null;
        if ($bieumau->mau === 'Hình ảnh' && $bieumau->hinh_anh) {
            $hinhNen = asset('storage/backgrounds/' . $bieumau->hinh_anh);
        }

        return view('Bieumau.Tao_form', [
            'bieumau' => $bieumau,
            'cauhois' => $cauhois,
            'mau' => $mauHex,
            'hinh_nen' => $hinhNen,
            'isCreating' => false
        ]);
    }
}
