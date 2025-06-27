<?php

namespace App\Http\Controllers\Nguoidung;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DiemDanh;
use App\Models\CauTraLoi;
use App\Models\BieuMau;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

class TraloiBieumauController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('nguoidung.Traloi_bieumau');
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
        $bieuMauMa = $request->input('bieu_mau_ma');
        if (!$bieuMauMa) {
            return redirect()->back()->withErrors(['message' => 'Thiếu mã biểu mẫu.']);
        }

        $maDiemDanh = Str::uuid()->toString();

        // Lấy tài khoản từ session 
        $taiKhoanMa = session('ma_tai_khoan');
        $agent = new Agent();
        $device = $agent->device();         // Tên thiết bị (nếu có)
        $platform = $agent->platform();     // Hệ điều hành
        $browser = $agent->browser();       // Trình duyệt

        $thietBiDiemDanh = "$device - $platform - $browser";
        $bieuMau = BieuMau::with('danhSach')->where('ma_bieu_mau', $bieuMauMa)->first();
        $maDanhSach = optional($bieuMau->danhSach)->ma_danh_sach;


        // Tạo điểm danh
        DiemDanh::create([
            'ma_diem_danh' => $maDiemDanh,
            'thoi_gian_diem_danh' => now(),
            'thiet_bi_diem_danh' => Str::limit($thietBiDiemDanh, 100),
            'dinh_vi_thiet_bi' => $request->input('location') ?? '',
            'bieu_mau_ma' => $bieuMauMa,
            'tai_khoan_ma' => $taiKhoanMa,
            'danh_sach_ma' => $maDanhSach,
        ]);

        // Lưu các câu trả lời
        $traLoiData = $request->input('cau_tra_loi', []);
        foreach ($traLoiData as $maCauHoi => $traLoi) {
            if (is_array($traLoi)) {
                foreach ($traLoi as $tl) {
                    CauTraLoi::create([
                        'ma_cau_tra_loi' => Str::uuid()->toString(),
                        'cau_tra_loi' => $tl,
                        'cau_hoi_ma' => $maCauHoi,
                        'diem_danh_ma' => $maDiemDanh,
                    ]);
                }
            } else {
                CauTraLoi::create([
                    'ma_cau_tra_loi' => Str::uuid()->toString(),
                    'cau_tra_loi' => $traLoi,
                    'cau_hoi_ma' => $maCauHoi,
                    'diem_danh_ma' => $maDiemDanh,
                ]);
            }
        }

        return redirect()->route(session('ma_tai_khoan') ? 'nguoidung.ql-bieumau' : 'trangchu')
                 ->with('success', 'Đã gửi câu trả lời thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bieuMau = BieuMau::with('cauHois')->findOrFail($id);
        return view('nguoidung.Traloi_bieumau', compact('bieuMau'));
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
    public function destroy(string $id)
    {
        //
    }
}
