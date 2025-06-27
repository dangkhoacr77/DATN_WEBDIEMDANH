<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BieuMau;
use App\Models\CauHoi;
use Illuminate\Support\Str;

class BieuMauController extends Controller
{
    public function store(Request $request)
{
    // Validate dữ liệu từ frontend gửi lên
    $validated = $request->validate([
        'title' => 'required|string',
        'description' => 'nullable|string',
        'questions' => 'required|array',
    ]);

    // ✅ Sinh mã mới cho biểu mẫu
    $lastBM = BieuMau::orderByDesc('ma_bieu_mau')->first();
    $nextBMCode = 'BM' . str_pad(((int)str_replace('BM', '', $lastBM->ma_bieu_mau ?? 0)) + 1, 3, '0', STR_PAD_LEFT);

    // ✅ Tạo bản ghi biểu mẫu
    $bieuMau = BieuMau::create([
        'ma_bieu_mau' => $nextBMCode,
        'tieu_de' => $validated['title'],
        'mau' => 'Xanh', // hoặc request->mau nếu có
        'thoi_luong_diem_danh' => 30,
        'gioi_han_diem_danh' => 50,
        'so_luong_da_diem_danh' => 0,
        'ngay_tao' => now(),
        'tai_khoan_ma' => 'TK001', // tuỳ theo đăng nhập hoặc mặc định
    ]);

    // ✅ Lưu từng câu hỏi
    $lastCH = CauHoi::orderByDesc('ma_cau_hoi')->first();
    $counter = (int)str_replace('CH', '', $lastCH->ma_cau_hoi ?? 0);

    foreach ($validated['questions'] as $q) {
        $counter++;
        $maCauHoi = 'CH' . str_pad($counter, 3, '0', STR_PAD_LEFT);

        CauHoi::create([
            'ma_cau_hoi' => $maCauHoi,
            'cau_hoi' => $q['title'],
            'cau_hoi_bat_buoc' => $q['required'] ? 1 : 0,
            'noi_dung' => isset($q['options']) && is_array($q['options']) ? implode(', ', $q['options']) : null,
            'loai_cau_hoi' => match($q['type']) {
                'Trả lời ngắn' => 'tra_loi_ngan',
                'Trắc nghiệm' => 'trac_nghiem',
                'Hộp kiểm' => 'hop_kiem',
                default => 'tra_loi_ngan'
            },
            'bieu_mau_ma' => $bieuMau->ma_bieu_mau
        ]);
    }

    return response()->json(['success' => true, 'message' => 'Biểu mẫu đã được lưu thành công!']);
}
}