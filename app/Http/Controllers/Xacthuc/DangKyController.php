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
            'mat_khau' => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'regex:/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[\W_]).+$/',
            ],
            'mat_khau_confirmation' => 'required',
            'ho_ten' => 'required|string|max:100',
            'ngay_sinh' => 'required|date',
            'so_dien_thoai' => 'nullable|string|max:20',
        ], [
            // Mail
            'mail.required' => 'Vui lòng nhập email.',
            'mail.email' => 'Email không đúng định dạng.',
            'mail.unique' => 'Email này đã được đăng kí.',

            // Mật khẩu
            'mat_khau.required' => 'Vui lòng nhập mật khẩu.',
            'mat_khau.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'mat_khau.confirmed' => 'Mật khẩu nhập lại không khớp.',
            'mat_khau.regex' => 'Mật khẩu phải chứa ít nhất 1 chữ cái, 1 số và 1 ký tự đặc biệt.',

            // Nhập lại mật khẩu
            'mat_khau_confirmation.required' => 'Vui lòng nhập lại mật khẩu.',

            // Họ tên
            'ho_ten.required' => 'Vui lòng nhập họ tên.',
            'ho_ten.string' => 'Họ tên không hợp lệ.',
            'ho_ten.max' => 'Họ tên tối đa 100 ký tự.',

            // Ngày sinh
            'ngay_sinh.required' => 'Vui lòng chọn ngày sinh.',
            'ngay_sinh.date' => 'Ngày sinh không hợp lệ.',

            // Số điện thoại
            'so_dien_thoai.string' => 'Số điện thoại không hợp lệ.',
            'so_dien_thoai.max' => 'Số điện thoại tối đa 10 ký tự.',
        ]);

        TaiKhoan::create([
            'ma_tai_khoan' => 'TK' . time(),
            'mail' => $request->mail,
            'so_dien_thoai' => $request->so_dien_thoai,
            'ngay_sinh' => $request->ngay_sinh,
            'ho_ten' => $request->ho_ten,
            'mat_khau' => Hash::make($request->mat_khau),
            'loai_tai_khoan' => 'nguoi_dung',
            'trang_thai' => 1,
            'ngay_tao' => now()
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
