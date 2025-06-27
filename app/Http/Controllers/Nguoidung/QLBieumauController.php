<?php

namespace App\Http\Controllers\Nguoidung;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BieuMau;

class QLBieumauController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $nguoiDung = session('nguoi_dung'); // lấy từ session
        if (!$nguoiDung) {
            return redirect()->route('xacthuc.dang-nhap');
        }

        $bieumau = BieuMau::where('tai_khoan_ma', $nguoiDung['ma_tai_khoan'])
            ->where('trang_thai', 1)
            ->get();

        return view('nguoidung.QL_bieumau', compact('bieumau'));
    }

    public function xoaDaChon(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            BieuMau::whereIn('ma_bieu_mau', $ids)->update(['trang_thai' => 0]);
        }

        return response()->json(['message' => 'Đã cập nhật trạng thái thành công']);
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
