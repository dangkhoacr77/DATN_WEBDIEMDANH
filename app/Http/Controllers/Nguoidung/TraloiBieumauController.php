<?php

namespace App\Http\Controllers\Nguoidung;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DiemDanh;
use App\Models\CauTraLoi;
use App\Models\BieuMau;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;

class TraloiBieumauController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('nguoidung.Traloi_bieumau');
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
        $bieuMauMa = $request->input('bieu_mau_ma');
        if (!$bieuMauMa) {
            return redirect()->back()->withErrors(['message' => 'Thiếu mã biểu mẫu.']);
        }

        $maDiemDanh = Str::uuid()->toString();
        $taiKhoanMa = session('ma_tai_khoan');

        // ✅ Lấy thông tin thiết bị cụ thể hơn với DeviceDetector
        $userAgent = $request->userAgent(); // hoặc: $request->header('User-Agent')
        $dd = new \DeviceDetector\DeviceDetector($userAgent);
        $dd->parse();

        $device = $dd->getDeviceName();               // smartphone, desktop, tablet, ...
        $os = $dd->getOs('name');                     // Android, iOS, Windows, ...
        $brand = $dd->getBrandName();                 // Apple, Samsung, etc.
        $model = $dd->getModel();                     // iPhone 13, Galaxy S21, etc.
        $browserData = $dd->getClient();              // ['name' => ..., 'version' => ...]

        $thietBiDiemDanh = "$device - $os - $brand $model - {$browserData['name']} {$browserData['version']}";
        $thietBiDiemDanh = Str::limit($thietBiDiemDanh, 100); // Giới hạn 100 ký tự (tùy DB)

        // Lấy biểu mẫu và danh sách liên quan
        $bieuMau = BieuMau::with('danhSach')->where('ma_bieu_mau', $bieuMauMa)->first();
        if (!$bieuMau) {
            return redirect()->back()->withErrors(['message' => 'Biểu mẫu không tồn tại.']);
        }

        $maDanhSach = optional($bieuMau->danhSach)->ma_danh_sach;

        // Tạo điểm danh
        DiemDanh::create([
            'ma_diem_danh' => $maDiemDanh,
            'thoi_gian_diem_danh' => now(),
            'thiet_bi_diem_danh' => $thietBiDiemDanh,
            'dinh_vi_thiet_bi' => $request->input('location') ?? '',
            'bieu_mau_ma' => $bieuMauMa,
            'tai_khoan_ma' => $taiKhoanMa,
            'danh_sach_ma' => $maDanhSach,
        ]);

        // Lưu từng câu trả lời (mặc định là dạng trả lời ngắn)
        $traLoiData = $request->input('cau_tra_loi', []);
        foreach ($traLoiData as $maCauHoi => $traLoi) {
            CauTraLoi::create([
                'ma_cau_tra_loi' => Str::uuid()->toString(),
                'cau_tra_loi' => is_array($traLoi) ? implode(', ', $traLoi) : $traLoi,
                'cau_hoi_ma' => $maCauHoi,
                'diem_danh_ma' => $maDiemDanh,
            ]);
        }

        return view('nguoidung.Traloi_bieumau', [
            'bieuMau' => $bieuMau,
            'success' => 'Đã gửi câu trả lời thành công!',
            'hideQuestions' => true,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       $bieuMau = BieuMau::with(['cauHois'])->findOrFail($id);
        return view('nguoidung.Traloi_bieumau', compact('bieuMau'));
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
