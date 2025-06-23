<?php

namespace App\Http\Controllers\Xacthuc;

use App\Http\Controllers\Controller;
use App\Models\TaiKhoan;
use Illuminate\Http\Request;

class QuenMkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('xacthuc.Quen_mk');
    }

    public function sendResetCode(Request $request)
    {
        $request->validate([
            'mail' => 'required|email'
        ]);

        $tai_khoan = TaiKhoan::where('mail', $request->mail)->first();

        if (!$tai_khoan) {
            return back()->withErrors(['mail' => 'Email không tồn tại'])->withInput();
        }

        $ma_xac_nhan = rand(100000, 999999); // Sinh mã 6 chữ số

        session([
            'email_khoi_phuc' => $tai_khoan->mail,
            'ma_xac_nhan' => $ma_xac_nhan,
            'da_xac_thuc_ma' => false,
        ]);

        // Giả lập gửi email
        // Mail::to($tai_khoan->mail)->send(new MaXacNhanMail($ma_xac_nhan));

        return back()->with('thong_bao', 'Mã xác nhận đã được gửi tới email.');
    }

    /**
     * Xác nhận mã OTP nhập vào.
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'ma_xac_nhan' => 'required'
        ]);

        if ($request->ma_xac_nhan != session('ma_xac_nhan')) {
            return back()->withErrors(['ma_xac_nhan' => 'Mã xác nhận không đúng'])->withInput();
        }

        session(['da_xac_thuc_ma' => true]);

        return redirect()->route('xacthuc.dat-lai-mk')->with('thong_bao', 'Xác nhận thành công. Vui lòng đặt lại mật khẩu.');
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
