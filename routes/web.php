<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\QLBieumauController;
use App\Http\Controllers\Admin\QLTaikhoanController;
use App\Http\Controllers\Admin\ThongKeController;
use App\Http\Controllers\Admin\TTCanhanController;
use App\Http\Controllers\FormController;

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
use App\Http\Controllers\Api\BieuMauController;



// ======================= ADMIN =========================
Route::get('/ql-bieumau', [QLBieumauController::class, 'index'])->name('admin.ql-bieumau');
Route::get('/ql-taikhoan', [QLTaikhoanController::class, 'index'])->name('admin.ql-taikhoan');
Route::get('/thong-ke', [ThongKeController::class, 'index'])->name('admin.thong-ke');
Route::get('/thong-tin-ca-nhan', [TTCanhanController::class, 'index'])->name('admin.tt-canhan');
Route::put('/admin/ql-taikhoan/{id}', [QLTaikhoanController::class, 'update'])->name('admin.ql-taikhoan.update');
// ======================= BIEUMAU =========================
Route::get('/bieumau/cai-dat', [CaiDatController::class, 'index'])->name('bieumau.cai-dat');
Route::post('/bieumau/cai-dat', [CaiDatController::class, 'store'])->name('bieumau.luu-cai-dat');
Route::get('/bieumau/ds-cautraloi', [App\Http\Controllers\Bieumau\DsCautraloiController::class, 'index'])->name('bieumau.ds-cautraloi');
Route::get('/bieumau/tao-form', [TaoFormController::class, 'index'])->name('bieumau.tao');
Route::get('/chon-bieumau/{ma_bieu_mau}', [CaiDatController::class, 'chonBieuMau'])->name('chon-bieumau');


// ======================= NGUOIDUNG =========================
Route::get('/nguoidung/ls-diemdanh', [App\Http\Controllers\Nguoidung\LsDiemdanhController::class, 'index'])->name('nguoidung.ls-diemdanh');
Route::get('/nguoidung/ql-bieumau', [App\Http\Controllers\Nguoidung\QLBieumauController::class, 'index'])->name('nguoidung.ql-bieumau');
Route::get('/nguoidung/ql-danhsach', [App\Http\Controllers\Nguoidung\QLDanhsachController::class, 'index'])->name('nguoidung.ql-danhsach');
Route::get('/nguoidung/traloi-bieumau', [App\Http\Controllers\Nguoidung\TraloiBieumauController::class, 'index'])->name('nguoidung.traloi-bieumau');
Route::get('/nguoidung/thong-tin-ca-nhan', [App\Http\Controllers\Nguoidung\TTCanhanController::class, 'index'])->name('nguoidung.tt-canhan');

// ======================= TRANG CHỦ =========================
Route::get('/', [App\Http\Controllers\Trangchu\TrangchuController::class, 'index'])->name('trangchu');

// ======================= XÁC THỰC =========================
Route::middleware(['web'])->group(function () {
    Route::get('/dang-ky', [DangKyController::class, 'index'])->name('xacthuc.dang-ky');
    Route::post('/dang-ky', [DangKyController::class, 'store'])->name('xacthuc.dang-ky.post');
});
Route::get('/dang-nhap', [App\Http\Controllers\Xacthuc\DangNhapController::class, 'index'])->name('xacthuc.dang-nhap');
Route::get('/dat-lai-mk', [App\Http\Controllers\Xacthuc\DatlaiMkController::class, 'index'])->name('xacthuc.dat-lai-mk');
Route::get('/quen-mk', [App\Http\Controllers\Xacthuc\QuenMkController::class, 'index'])->name('xacthuc.quen-mk');
// Xử lý form gửi lên

Route::post('/dang-nhap', [DangNhapController::class, 'authenticate'])->name('xacthuc.dang-nhap.post');
Route::post('/dat-lai-mk', [DatlaiMkController::class, 'update'])->name('xacthuc.dat-lai-mk.post');
Route::post('/quen-mk', [QuenMkController::class, 'sendResetCode'])->name('xacthuc.quen-mk.post');
Route::post('/xac-thuc-ma', [QuenMkController::class, 'verifyCode'])->name('xacthuc.quen-mk.verify');
Route::post('/dang-xuat', function () {
    session()->forget('nguoi_dung'); // Xóa phiên đăng nhập
    return redirect()->route('trangchu')->with('success', 'Đã đăng xuất!');
})->name('dang-xuat');


Route::get('/test-middleware', function () {
    return "Bạn đã truy cập thành công";
})->middleware('kiemtra.loainguoidung:admin');

Route::post('/forms', [BieuMauController::class, 'store']);