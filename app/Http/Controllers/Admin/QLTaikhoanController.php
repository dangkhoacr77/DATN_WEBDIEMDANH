<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaiKhoan;

class QLTaikhoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $currentUserId = session('ma_tai_khoan'); // Lấy mã tài khoản từ session

        $taiKhoans = TaiKhoan::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('ho_ten', 'like', "%$search%")
                        ->orWhere('mail', 'like', "%$search%")
                        ->orWhere('so_dien_thoai', 'like', "%$search%")
                        ->orWhere('loai_tai_khoan', 'like', "%$search%")
                        ->orWhere('trang_thai', 'like', "%$search%")
                        ->orWhere('ngay_tao', 'like', "%$search%");
                });
            })
            ->when($currentUserId, function ($query, $currentUserId) {
                return $query->where('ma_tai_khoan', '<>', $currentUserId);
            })
            ->orderByDesc('ngay_tao')
            ->get();

        return view('admin.Ql_taikhoan', compact('taiKhoans', 'search'));
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
    public function update(Request $request, $ma_tai_khoan)
    {

        $taiKhoan = TaiKhoan::find($ma_tai_khoan);

        if (!$taiKhoan) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy tài khoản'], 404);
        }

        $validated = $request->validate([
            'loai_tai_khoan' => 'required|in:admin,nguoi_dung',
            'trang_thai' => 'required|in:0,1',
        ]);

        $taiKhoan->loai_tai_khoan = $validated['loai_tai_khoan'];
        $taiKhoan->trang_thai = $validated['trang_thai'];
        $taiKhoan->save();

        return response()->json(['success' => true, 'message' => 'Cập nhật thành công']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
