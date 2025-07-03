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
            'mat_khau' => [
                'required',
                'string',
                'min:6',
                'regex:/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[\W_]).+$/',
            ],
        ], [
            'mail.required' => 'Vui lòng nhập địa chỉ email.',
            'mail.email' => 'Địa chỉ email không hợp lệ.',
            'mat_khau.required' => 'Vui lòng nhập mật khẩu.',
            'mat_khau.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'mat_khau.regex' => 'Mật khẩu phải có ít nhất 1 chữ cái, 1 số và 1 ký tự đặc biệt.',
        ]);

        $nguoi_dung = TaiKhoan::where('mail', $request->mail)->first();

        // ❌ Không tìm thấy tài khoản
        if (!$nguoi_dung) {
            return back()->withErrors([
                'mail' => 'Email chưa được đăng ký.',
            ])->withInput();
        }

        // ❌ Tài khoản bị khóa
        if ($nguoi_dung->trang_thai == 0) {
            return back()->withErrors([
                'mail' => 'Tài khoản đã bị khóa.',
            ])->withInput();
        }

        // ❌ Sai mật khẩu
        if (!Hash::check($request->mat_khau, $nguoi_dung->mat_khau)) {
            return back()->withErrors([
                'mat_khau' => 'Mật khẩu không chính xác.',
            ])->withInput();
        }

        // ✅ Đăng nhập thành công
        session([
            'nguoi_dung' => $nguoi_dung,
            'ma_tai_khoan' => $nguoi_dung->ma_tai_khoan,
            'ho_ten' => $nguoi_dung->ho_ten,
        ]);
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
