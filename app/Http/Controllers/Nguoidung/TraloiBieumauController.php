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
    public function index()
    {
        return view('nguoidung.Traloi_bieumau');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $bieuMauMa = $request->input('bieu_mau_ma');
        if (!$bieuMauMa) {
            return redirect()->back()->withErrors(['message' => 'Thiếu mã biểu mẫu.']);
        }

        $maDiemDanh = Str::uuid()->toString();
        $taiKhoanMa = session('ma_tai_khoan');

        $userAgent = $request->userAgent();
        $dd = new DeviceDetector($userAgent);
        $dd->parse();

        $device = $dd->getDeviceName();
        $os = $dd->getOs('name') ?? 'Không rõ HĐH';
        $brand = $dd->getBrandName();
        $model = $dd->getModel();
        $browserData = $dd->getClient();

        $deviceName = trim("{$brand} {$model}");
        if (!$deviceName || $deviceName === '') {
            $deviceName = $device;
        }

        $browserInfo = isset($browserData['name']) ? $browserData['name'] : 'Trình duyệt không xác định';
        $browserVersion = isset($browserData['version']) ? $browserData['version'] : '';
        $thietBiDiemDanh = "$deviceName - $os - $browserInfo $browserVersion";
        $thietBiDiemDanh = Str::limit($thietBiDiemDanh, 100);

        $bieuMau = BieuMau::with('danhSach')->where('ma_bieu_mau', $bieuMauMa)->first();
        if (!$bieuMau) {
            return redirect()->back()->withErrors(['message' => 'Biểu mẫu không tồn tại.']);
        }

        $maDanhSach = optional($bieuMau->danhSach)->ma_danh_sach;

        // Xử lý riêng cho loại biểu mẫu 2
        if ($bieuMau->loai == 2) {
            $emailDangNhap = strtolower(trim(session('email')));
            $homNay = now()->format('Y-m-d');
            $danhSach = $bieuMau->danhSach;
            $duLieu = json_decode($danhSach->du_lieu_ds, true);

            $found = false;
            foreach ($duLieu as &$dong) {
                foreach ($dong as $key => $value) {
                    if (strtolower(trim($value)) === $emailDangNhap) {
                        // Kiểm tra đã điểm danh hôm nay chưa
                        if (isset($dong[$homNay]) && trim($dong[$homNay]) === 'x') {
                            return redirect()->back()->withErrors(['message' => 'Bạn đã điểm danh hôm nay.']);
                        }
                        $dong[$homNay] = 'x';
                        $found = true;
                        break 2;
                    }
                }
            }

            if (!$found) {
                return redirect()->back()->withErrors(['message' => 'Email của bạn không có trong danh sách điểm danh.']);
            }

            $danhSach->du_lieu_ds = json_encode($duLieu, JSON_UNESCAPED_UNICODE);
            $danhSach->save();

            DiemDanh::create([
                'ma_diem_danh' => $maDiemDanh,
                'thoi_gian_diem_danh' => now(),
                'thiet_bi_diem_danh' => $thietBiDiemDanh,
                'dinh_vi_thiet_bi' => $request->input('location') ?? '',
                'bieu_mau_ma' => $bieuMauMa,
                'tai_khoan_ma' => $taiKhoanMa,
                'danh_sach_ma' => $maDanhSach,
            ]);

            return view('nguoidung.Traloi_bieumau', [
                'bieuMau' => $bieuMau,
                'success' => 'Bạn đã điểm danh thành công!',
                'hideQuestions' => true,
                'redirectAfter' => route('trangchu'),
            ]);
        }

        // Biểu mẫu loại 1
        DiemDanh::create([
            'ma_diem_danh' => $maDiemDanh,
            'thoi_gian_diem_danh' => now(),
            'thiet_bi_diem_danh' => $thietBiDiemDanh,
            'dinh_vi_thiet_bi' => $request->input('location') ?? '',
            'bieu_mau_ma' => $bieuMauMa,
            'tai_khoan_ma' => $taiKhoanMa,
            'danh_sach_ma' => $maDanhSach,
        ]);

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

    public function show(string $id)
    {
        if (!session('ma_tai_khoan')) {
            return redirect()->route('xacthuc.dang-nhap')->with('error', 'Bạn cần đăng nhập.');
        }

        $bieuMau = BieuMau::with(['cauHois', 'danhSach', 'diemDanhs'])->findOrFail($id);
        $errorMessage = null;

        // Kiểm tra loại 2
        if ($bieuMau->loai == 2) {
            $emailDangNhap = strtolower(trim(session('email')));
            $duLieu = json_decode(optional($bieuMau->danhSach)->du_lieu_ds, true);
            $homNay = now()->format('Y-m-d');
            $emailTonTai = false;
            $daDiemDanh = false;

            foreach ($duLieu as $dong) {
                foreach ($dong as $key => $value) {
                    if (strtolower(trim($value)) === $emailDangNhap) {
                        $emailTonTai = true;
                        if (isset($dong[$homNay]) && trim($dong[$homNay]) === 'x') {
                            $daDiemDanh = true;
                        }
                        break 2;
                    }
                }
            }

            if (!$emailTonTai) {
                $errorMessage = 'Email của bạn không có trong danh sách điểm danh.';
            } elseif ($daDiemDanh) {
                $errorMessage = 'Bạn đã điểm danh hôm nay.';
            }
        }

        // Kiểm tra thời gian
        if (!$errorMessage && $bieuMau->thoi_luong_diem_danh) {
            $ngayTao = \Carbon\Carbon::parse($bieuMau->ngay_tao);
            $thoiGianHetHan = $ngayTao->addMinutes($bieuMau->thoi_luong_diem_danh);
            if (now()->greaterThan($thoiGianHetHan)) {
                $errorMessage = 'Biểu mẫu đã hết thời gian điểm danh.';
            }
        }

        // Kiểm tra giới hạn số người (chỉ cho loại 1)
        if (!$errorMessage && $bieuMau->loai == 1 && $bieuMau->gioi_han_diem_danh) {
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
}
