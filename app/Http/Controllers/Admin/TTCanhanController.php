<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaiKhoan;

class TTCanhanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $nguoiDung = session('nguoi_dung'); 

        if (!$nguoiDung) {
            return redirect()->route('dang-nhap'); 
        }

        // Lấy dữ liệu đầy đủ từ DB
        $taiKhoan = TaiKhoan::find($nguoiDung->ma_tai_khoan);

        return view('admin.TT_canhan', compact('taiKhoan'));
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
        $taiKhoan = TaiKhoan::find($id);

        if (!$taiKhoan) {
            return redirect()->back()->withErrors('Không tìm thấy tài khoản');
        }

        $request->validate([
            'ho_ten' => 'required|string|max:255',
            'mail' => 'required|email|max:255',
            'so_dien_thoai' => 'required|string|max:20',
            'ngay_sinh' => 'required|date_format:d/m/Y',
        ]);

        // Chuyển ngày sinh từ d/m/Y -> Y-m-d
        $ngaySinh = \Carbon\Carbon::createFromFormat('d/m/Y', $request->ngay_sinh)->format('Y-m-d');

        $taiKhoan->update([
            'ho_ten' => $request->ho_ten,
            'mail' => $request->mail,
            'so_dien_thoai' => $request->so_dien_thoai,
            'ngay_sinh' => $ngaySinh,
        ]);

        return redirect()->route('admin.tt-canhan')->with('success', 'Cập nhật thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
