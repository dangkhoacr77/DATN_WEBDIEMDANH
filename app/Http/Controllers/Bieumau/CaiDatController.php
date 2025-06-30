<?php

namespace App\Http\Controllers\Bieumau;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BieuMau;
use App\Models\DiemDanh;
use Illuminate\Support\Str;

class CaiDatController extends Controller
{
    public function chonBieuMau($ma_bieu_mau)
    {
        session(['ma_bieu_mau' => $ma_bieu_mau]);
        return redirect()->route('bieumau.cai-dat');
    }

    public function index()
    {
        $ma_bieu_mau = session('ma_bieu_mau');
        $bieuMau = BieuMau::where('ma_bieu_mau', $ma_bieu_mau)->first();




        if (!$bieuMau) {
            return back()->with('error', 'Không tìm thấy biểu mẫu.');
        }

        return view('Bieumau.Cai_dat', compact('bieuMau'));
    }

    public function store(Request $request)
    {
         $ma_bieu_mau = session('ma_bieu_mau');
        $tai_khoan_ma = session('nguoi_dung.ma_tai_khoan') ?? 'TK001';

        $bieuMau = BieuMau::where('ma_bieu_mau', $ma_bieu_mau)->first();
        if (!$bieuMau) {
            return back()->with('error', 'Không tìm thấy biểu mẫu.');
        }

        $bieuMau->thoi_luong_diem_danh = $request->has('enable_time_limit') ? $request->input('time_limit') : null;
        $bieuMau->gioi_han_diem_danh = $request->has('enable_participant_limit') ? $request->input('participant_limit') : null;
        $bieuMau->save();

        $co_dinh_vi = $request->has('geo_location');
        $co_thiet_bi = $request->has('device_name');
        $co_email = $request->has('email_account');

        if ($co_dinh_vi || $co_thiet_bi || $co_email) {
            $diemDanh = new DiemDanh();
            $diemDanh->ma_diem_danh = 'DD' . strtoupper(Str::random(6));
            $diemDanh->thoi_gian_diem_danh = now();
            $diemDanh->dinh_vi_thiet_bi = $co_dinh_vi ? 'Lấy định vị' : null;
            $diemDanh->thiet_bi_diem_danh = $co_thiet_bi ? 'Lấy tên thiết bị' : null;
            $diemDanh->bieu_mau_ma = $ma_bieu_mau;
            $diemDanh->tai_khoan_ma = $tai_khoan_ma;
            $diemDanh->danh_sach_ma = null;
            $diemDanh->save();
        }

        return back()->with('success', 'Đã lưu cài đặt biểu mẫu thành công.');
    }
}
