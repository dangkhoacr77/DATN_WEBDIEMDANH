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
        $request->validate([
            'mail' => 'required|email',
            'mat_khau' => 'required'
        ]);

        $nguoi_dung = TaiKhoan::where('mail', $request->mail)->first();

        if ($nguoi_dung && Hash::check($request->mat_khau, $nguoi_dung->mat_khau)) {
            session(['nguoi_dung' => $nguoi_dung]);
            return redirect()->route('forms.create');
        }

        return back()->withErrors(['mail' => 'Email hoặc mật khẩu không đúng'])->withInput();
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
