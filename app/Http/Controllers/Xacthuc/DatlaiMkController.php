<?php

namespace App\Http\Controllers\Xacthuc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaiKhoan;
use Illuminate\Support\Facades\Hash;

class DatlaiMkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // if (!session('da_xac_thuc_ma') || !session('email_khoi_phuc')) {
        //     return redirect()->route('xacthuc.quen-mk')->withErrors(['mail' => 'Bạn cần xác minh mã trước.']);
        // }
         return view('xacthuc.Datlai_mk');
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
    public function update(Request $request)
    {
        $request->validate([
            'mat_khau' => 'required|min:6|confirmed',
        ]);

        $tai_khoan = TaiKhoan::where('mail', session('email_khoi_phuc'))->first();

        if (!$tai_khoan) {
            return redirect()->route('xacthuc.quen-mk')->withErrors(['mail' => 'Không tìm thấy tài khoản.']);
        }

        $tai_khoan->mat_khau = Hash::make($request->mat_khau);
        $tai_khoan->save();

        session()->forget(['ma_xac_nhan', 'email_khoi_phuc', 'da_xac_thuc_ma']);

        return redirect()->route('xacthuc.dang-nhap')->with('thong_bao', 'Mật khẩu đã được cập nhật. Hãy đăng nhập lại.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
