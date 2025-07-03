<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\QLBieumauController;
use App\Http\Controllers\Admin\QLTaikhoanController;
use App\Http\Controllers\Admin\ThongKeController;
use App\Http\Controllers\Admin\TTCanhanController;

use App\Http\Controllers\Bieumau\CaiDatController;
use App\Http\Controllers\Bieumau\DsCautraloiController;
use App\Http\Controllers\Bieumau\TaoFormController;

use App\Http\Controllers\Nguoidung\LsDiemdanhController;
use App\Http\Controllers\Nguoidung\QLBieumauController as NguoidungQLBieumauController;
use App\Http\Controllers\Nguoidung\QLDanhsachController;
use App\Http\Controllers\Nguoidung\TraloiBieumauController;
use App\Http\Controllers\Nguoidung\TTCanhanController as NguoidungTTCanhanController;

use App\Http\Controllers\Trangchu\TrangchuController;

use App\Http\Controllers\Xacthuc\DangKyController;
use App\Http\Controllers\Xacthuc\DangNhapController;
use App\Http\Controllers\Xacthuc\DatlaiMkController;
use App\Http\Controllers\Xacthuc\QuenMkController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

// ======================= ADMIN =========================
Route::get('/ql-bieumau', [QLBieumauController::class, 'index'])->name('admin.ql-bieumau');
Route::get('/ql-taikhoan', [QLTaikhoanController::class, 'index'])->name('admin.ql-taikhoan');
Route::get('/thong-ke', [ThongKeController::class, 'index'])->name('admin.thong-ke');
Route::get('/thong-tin-ca-nhan', [TTCanhanController::class, 'index'])->name('admin.tt-canhan');

Route::put('/admin/ql-taikhoan/{id}', [QLTaikhoanController::class, 'update'])->name('admin.ql-taikhoan.update');
Route::put('/admin/thong-tin-ca-nhan/{id}', [TTCanhanController::class, 'update'])->name('thong-tin-ca-nhan.update');

// ======================= BIEUMAU =========================
Route::get('/bieumau/tao-form', [TaoFormController::class, 'index'])->name('bieumau.tao');
Route::middleware(['web'])->group(function () {
    Route::post('/bieumau/xuat-ban', [TaoFormController::class, 'store']);

    // Upload ảnh nền vào storage
    Route::post('/upload-background', function (Request $request) {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('public/backgrounds', $filename);
            return response()->json(['success' => true, 'path' => Storage::url($path)]);
        }
        return response()->json(['success' => false], 400);
    });
});
Route::get('/bieumau/tao/{ma_bieu_mau}', [TaoFormController::class, 'edit'])->name('bieumau.sua');


// ======================= NGUOIDUNG =========================
Route::get('/nguoidung/ls-diemdanh', [LsDiemdanhController::class, 'index'])->name('nguoidung.ls-diemdanh');
Route::get('/nguoidung/ql-bieumau', [NguoidungQLBieumauController::class, 'index'])->name('nguoidung.ql-bieumau');
Route::delete('/nguoidung/bieumau/xoa-da-chon', [NguoidungQLBieumauController::class, 'xoaDaChon'])->name('nguoidung.bieumau.xoaDaChon');

Route::get('/nguoidung/ql-danhsach', [QLDanhsachController::class, 'index'])->name('nguoidung.ql-danhsach');
Route::delete('/nguoidung/ql-danhsach/delete', [QLDanhsachController::class, 'destroy'])->name('nguoidung.ql-danhsach.destroy');
Route::get('/nguoidung/ql-danhsach/export/{id}', [QLDanhsachController::class, 'export'])->name('nguoidung.ql-danhsach.export');
Route::get('/nguoidung/ql-danhsach/{id}/preview', [QLDanhsachController::class, 'preview'])->name('nguoidung.ql-danhsach.preview');

Route::get('/nguoidung/traloi-bieumau', [TraloiBieumauController::class, 'index'])->name('nguoidung.traloi-bieumau');
Route::get('/traloi-bieumau/{id}', [TraloiBieumauController::class, 'show'])->name('traloi-bieumau.show');
Route::post('/traloi-bieumau', [TraloiBieumauController::class, 'store'])->name('traloi-bieumau.store');

Route::get('/nguoidung/thong-tin-ca-nhan', [NguoidungTTCanhanController::class, 'index'])->name('nguoidung.tt-canhan');
Route::put('/nguoidung/thong-tin-ca-nhan/{id}', [NguoidungTTCanhanController::class, 'update'])->middleware('web')->name('nguoidung.tt-canhan.update');

// ======================= TRANG CHỦ =========================
Route::get('/', [TrangchuController::class, 'index'])->name('trangchu');

// ======================= XÁC THỰC =========================
Route::middleware(['web'])->group(function () {
    Route::get('/dang-ky', [DangKyController::class, 'index'])->name('xacthuc.dang-ky');
    Route::post('/dang-ky', [DangKyController::class, 'store'])->name('xacthuc.dang-ky.post');
});
Route::get('/dang-nhap', [DangNhapController::class, 'index'])->name('xacthuc.dang-nhap');
Route::get('/dat-lai-mk', [DatlaiMkController::class, 'index'])->name('xacthuc.dat-lai-mk');
Route::get('/quen-mk', [QuenMkController::class, 'index'])->name('xacthuc.quen-mk');

Route::post('/dang-nhap', [DangNhapController::class, 'authenticate'])->name('xacthuc.dang-nhap.post');
Route::post('/dat-lai-mk', [DatlaiMkController::class, 'update'])->name('xacthuc.dat-lai-mk.post');
Route::post('/quen-mk', [QuenMkController::class, 'sendResetCode'])->name('xacthuc.quen-mk.post');
Route::post('/xac-thuc-ma', [QuenMkController::class, 'verifyCode'])->name('xacthuc.quen-mk.verify');
Route::get('/dang-xuat', function () {
    session()->forget('nguoi_dung');
    return redirect()->route('trangchu')->with('success', 'Đã đăng xuất!');
})->name('dang-xuat');
