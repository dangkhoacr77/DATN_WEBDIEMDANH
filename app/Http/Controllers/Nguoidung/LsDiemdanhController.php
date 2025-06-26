<?php

namespace App\Http\Controllers\Nguoidung;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DiemDanh;

class LsDiemdanhController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $nguoiDung = session('nguoi_dung');

    if (!$nguoiDung) {
        return redirect()->route('xacthuc.dang-nhap');
    }

    $lichSu = DiemDanh::with(['bieuMau.taiKhoan'])
        ->where('tai_khoan_ma', $nguoiDung['ma_tai_khoan'])
        ->orderByDesc('thoi_gian_diem_danh')
        ->get(); // ❌ paginate ➜ ✅ get toàn bộ để lọc client-side

    return view('nguoidung.Ls_diemdanh', compact('lichSu'));
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
