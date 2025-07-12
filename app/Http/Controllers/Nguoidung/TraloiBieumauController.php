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

        //  Lấy thông tin thiết bị cụ thể hơn
        $userAgent = $request->userAgent();
        $dd = new \DeviceDetector\DeviceDetector($userAgent);
        $dd->parse();

        $device = $dd->getDeviceName();                    // smartphone, desktop, tablet, ...
        $os = $dd->getOs('name') ?? 'Không rõ HĐH';        // Android, Windows, iOS,...
        $brand = $dd->getBrandName();                      // Samsung, Apple, HP,...
        $model = $dd->getModel();                          // Galaxy S21, iPhone 13,...
        $browserData = $dd->getClient();                   // ['name' => ..., 'version' => ...]

        //  Tên thiết bị cụ thể
        $deviceName = trim("{$brand} {$model}");
        if (!$deviceName || $deviceName === '') {
            $deviceName = $device; // Fallback nếu brand/model rỗng
        }

        //  Gộp thông tin thiết bị
        $browserInfo = isset($browserData['name']) ? $browserData['name'] : 'Trình duyệt không xác định';
        $browserVersion = isset($browserData['version']) ? $browserData['version'] : '';
        $thietBiDiemDanh = "$deviceName - $os - $browserInfo $browserVersion";

        //  Giới hạn độ dài nếu cần
        $thietBiDiemDanh = Str::limit($thietBiDiemDanh, 100);

        //  Kiểm tra biểu mẫu
        $bieuMau = BieuMau::with('danhSach')->where('ma_bieu_mau', $bieuMauMa)->first();
        if (!$bieuMau) {
            return redirect()->back()->withErrors(['message' => 'Biểu mẫu không tồn tại.']);
        }

        $maDanhSach = optional($bieuMau->danhSach)->ma_danh_sach;

        // ✅ Tạo điểm danh
        DiemDanh::create([
            'ma_diem_danh' => $maDiemDanh,
            'thoi_gian_diem_danh' => now(),
            'thiet_bi_diem_danh' => $thietBiDiemDanh,
            'dinh_vi_thiet_bi' => $request->input('location') ?? '',
            'bieu_mau_ma' => $bieuMauMa,
            'tai_khoan_ma' => $taiKhoanMa,
            'danh_sach_ma' => $maDanhSach,
        ]);

        // ✅ Lưu câu trả lời
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
            'redirectAfter' => route('trangchu'),
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!session('ma_tai_khoan')) {
            return redirect()->route('xacthuc.dang-nhap')->with('error', 'Bạn cần đăng nhập.');
        }

        $bieuMau = BieuMau::with(['cauHois', 'danhSach', 'diemDanhs'])->findOrFail($id);

        $errorMessage = null;

        // ⏱️ Kiểm tra thời lượng điểm danh (nếu có)
        if ($bieuMau->thoi_luong_diem_danh) {
            $ngayTao = \Carbon\Carbon::parse($bieuMau->ngay_tao);
            $thoiGianHetHan = $ngayTao->addMinutes($bieuMau->thoi_luong_diem_danh);
            if (now()->greaterThan($thoiGianHetHan)) {
                $errorMessage = 'Biểu mẫu này đã hết thời gian điểm danh.';
            }
        }

        // 👥 Kiểm tra giới hạn số người điểm danh (nếu có)
        if (!$errorMessage && $bieuMau->gioi_han_diem_danh) {
            $soNguoiDaDiemDanh = $bieuMau->diemDanhs->count();
            if ($soNguoiDaDiemDanh >= $bieuMau->gioi_han_diem_danh) {
                $errorMessage = 'Biểu mẫu đã đạt giới hạn số người điểm danh.';
            }
        }

        return view('nguoidung.Traloi_bieumau', [
            'bieuMau' => $bieuMau,
            'errorMessage' => $errorMessage,
        ]);
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
