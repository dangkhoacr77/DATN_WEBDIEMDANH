<?php

namespace App\Http\Controllers\Bieumau;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BieuMau;
use App\Models\CauHoi;
use App\Models\DanhSachDiemDanh;
use Illuminate\Support\Facades\DB;

class TaoFormController extends Controller
{
    public function index()
    {
        return view('Bieumau.Tao_form');
    }

    public function store(Request $request)
    {
        $ma_tai_khoan = session('nguoi_dung')['ma_tai_khoan'] ?? null;

        if (!$ma_tai_khoan) {
            return response()->json(['success' => false, 'message' => 'Bạn chưa đăng nhập']);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            $ma_bieu_mau = 'BM' . str_pad(BieuMau::count() + 1, 3, '0', STR_PAD_LEFT);

            $bieuMau = BieuMau::create([
                'ma_bieu_mau' => $ma_bieu_mau,
                'tieu_de' => $validated['title'],
                'mo_ta_tieu_de' => $validated['description'] ?? '',
                'mau' => $request->input('theme') ?? null,
                'thoi_luong_diem_danh' => $request->input('time_limit') ?? 0,
                'gioi_han_diem_danh' => $request->input('participant_limit') ?? 0,
                'so_luong_da_diem_danh' => 0,
                'trang_thai' => 1,
                'ngay_tao' => now(),
                'tai_khoan_ma' => $ma_tai_khoan,
                'hinh_anh' => null
            ]);

            $i = 1;
            foreach ($validated['questions'] as $q) {
                CauHoi::create([
                    'ma_cau_hoi' => 'CH' . str_pad(($i + 10), 3, '0', STR_PAD_LEFT),
                    'cau_hoi' => $q['title'] ?? '',
                    'cau_hoi_bat_buoc' => $q['required'] ?? false,
                    'noi_dung' => json_encode($q['options'] ?? []),
                    'loai_cau_hoi' => $q['type'] ?? 'Trả lời ngắn',
                    'bieu_mau_ma' => $ma_bieu_mau,
                ]);
                $i++;
            }

            DanhSachDiemDanh::create([
                'ma_danh_sach' => 'DS' . str_pad(DanhSachDiemDanh::count() + 1, 3, '0', STR_PAD_LEFT),
                'ten_danh_sach' => 'Danh sách cho ' . $validated['title'],
                'du_lieu_ds' => json_encode([]),
                'ngay_tao' => now(),
                'thoi_gian_tao' => now()->format('H:i:s'),
                'bieu_mau_ma' => $ma_bieu_mau,
                'tai_khoan_ma' => $ma_tai_khoan
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Biểu mẫu đã được xuất bản thành công!',
                'ma_bieu_mau' => $ma_bieu_mau,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xuất bản biểu mẫu.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, string $id)
    {
        $bieuMau = BieuMau::where('ma_bieu_mau', $id)->first();

        if (!$bieuMau) {
            return redirect()->back()->with('error', 'Không tìm thấy biểu mẫu.');
        }

        $bieuMau->update($request->only(['tieu_de', 'mau']));

        return redirect()->route('bieumau.tao')->with('success', 'Cập nhật biểu mẫu thành công.');
    }

    // Các hàm create, show, edit, destroy bạn có thể bổ sung sau nếu cần
}
