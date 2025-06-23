<?php

namespace App\Http\Controllers\Xacthuc;

use App\Http\Controllers\Controller;
use App\Models\TaiKhoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DangKyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('xacthuc.Dang_ky');
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
        $request->validate([
            'mail' => 'required|email|unique:TaiKhoan,mail',
            'mat_khau' => 'required|min:6|confirmed',
            'ho_ten' => 'required|string|max:100',
            'ngay_sinh' => 'required|date',
            'so_dien_thoai' => 'nullable|string|max:20',
            'hinh_anh' => 'nullable|string|max:200',
        ]);

        TaiKhoan::create([
            'ma_tai_khoan' => 'TK' . time(),
            'hinh_anh' => $request->hinh_anh,
            'mail' => $request->mail,
            'so_dien_thoai' => $request->so_dien_thoai,
            'ngay_sinh' => $request->ngay_sinh,
            'ho_ten' => $request->ho_ten,
            'mat_khau' => Hash::make($request->mat_khau),
            'loai_tai_khoan' => 'user',
            'trang_thai' => 1,
        ]);

        return redirect()->route('xacthuc.dang-nhap')->with('thong_bao', 'Đăng ký thành công. Vui lòng đăng nhập.');
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
    public function destroy(string $id)
    {
        //
    }
}
