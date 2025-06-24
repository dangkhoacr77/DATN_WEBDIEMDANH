<?php

namespace App\Http\Controllers\Xacthuc;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaiKhoan;
use Illuminate\Support\Facades\Hash;

class DangNhapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('xacthuc.Dang_nhap');
    }

    public function authenticate(Request $request)
    {
        $validatedData = $request->validate([
            'mail' => ['required', 'email'],
            'mat_khau' => ['required'],
        ], [
            'mail.required' => 'Vui lòng nhập địa chỉ email.',
            'mail.email' => 'Địa chỉ email không hợp lệ.',
            'mat_khau.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $nguoi_dung = TaiKhoan::where('mail', $request->mail)->first();

        // ✅ Nếu không tồn tại tài khoản với email đã nhập
        if (!$nguoi_dung) {
            return back()->withErrors([
                'mail' => 'Email chưa được đăng ký.',
            ])->withInput();
        }

        // ✅ Nếu mật khẩu sai
        if (!Hash::check($request->mat_khau, $nguoi_dung->mat_khau)) {
            return back()->withErrors([
                'mat_khau' => 'Mật khẩu không chính xác.',
            ])->withInput();
        }

        // ✅ Thành công
        session(['nguoi_dung' => $nguoi_dung]);
        return redirect()->route('trangchu')->with('success', 'Đăng nhập thành công!');
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
