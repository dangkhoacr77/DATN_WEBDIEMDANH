<?php

namespace App\Http\Controllers\Bieumau;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\BieuMau;
use App\Models\CauHoi;
use App\Models\DanhSachDiemDanh;
use Illuminate\Support\Facades\Log;
use App\Models\MaQR;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class TaoFormController extends Controller
{
    public function index()
    {
        return view('Bieumau.Tao_form', [
            'isCreating' => true
        ]);
    }

    public function store(Request $request)
    {
        try {
            Log::info('REQUEST DATA', $request->except('du_lieu_vao'));

            $maTaiKhoan = session('ma_tai_khoan');
            if (!$maTaiKhoan) {
                return response()->json(['success' => false, 'message' => 'Bạn chưa đăng nhập']);
            }

            $questions = json_decode($request->questions, true);
            if (!$request->title || !is_array($questions) || count($questions) === 0) {
                return response()->json(['success' => false, 'message' => 'Dữ liệu biểu mẫu không hợp lệ']);
            }

            DB::beginTransaction();

            $maBieuMau = (string) Str::uuid();

            $mau = $request->theme_color;
            if (!$mau && $request->background_image) {
                $mau = 'hình ảnh';
            }

            $loai = $request->loai == 2 ? 2 : 1;
            $duLieuVaoPath = null;
            $duLieuDanhSach = [];

            if ($loai === 2 && $request->hasFile('du_lieu_vao')) {
                $file = $request->file('du_lieu_vao');
                if (!$file->isValid()) {
                    return response()->json(['success' => false, 'message' => 'File Excel không hợp lệ']);
                }

                $duLieuVaoPath = $file->store('uploads/excel', 'public');

                $spreadsheet = IOFactory::load($file->getPathname());
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray(null, true, true, true);

                $headerRow = array_filter(array_shift($rows), fn($h) => trim($h) !== '');
                $ngayTao = now()->format('Y-m-d');
                $headerRow[] = $ngayTao;

                foreach ($rows as $row) {
                    $cleanRow = [];
                    foreach ($headerRow as $key => $header) {
                        if ($header === $ngayTao) {
                            $cleanRow[trim($header)] = ''; // để trống chờ điểm danh
                        } else {
                            $cleanRow[trim($header)] = $row[$key] ?? '';
                        }
                    }

                    if (array_filter($cleanRow, fn($v) => trim($v) !== '')) {
                        $duLieuDanhSach[] = $cleanRow;
                    }
                }
            }

            $timeLimit = $request->input('time_limit');
            $participantLimit = $request->input('participant_limit');

            $timeLimit = $timeLimit === 'null' || $timeLimit === null ? null : (int) $timeLimit;
            $participantLimit = $participantLimit === 'null' || $participantLimit === null ? null : (int) $participantLimit;

            $bieuMau = BieuMau::create([
                'ma_bieu_mau' => $maBieuMau,
                'tieu_de' => $request->title,
                'mo_ta_tieu_de' => $request->description,
                'mau' => $mau,
                'hinh_anh' => $request->background_image ?? null,
                'thoi_luong_diem_danh' => $timeLimit,
                'gioi_han_diem_danh' => $participantLimit,
                'trang_thai' => 1,
                'ngay_tao' => now(),
                'tai_khoan_ma' => $maTaiKhoan,
                'loai' => $loai,
                'du_lieu_vao' => $duLieuVaoPath,
            ]);

            MaQR::create([
                'ma_qr' => (string) Str::uuid(),
                'hinh_anh' => null,
                'duong_dan' => url('/traloi-bieumau/' . $maBieuMau),
                'trang_thai' => 1,
                'ngay_tao' => now(),
                'bieu_mau_ma' => $maBieuMau
            ]);

            foreach ($questions as $q) {
                if (!isset($q['title'])) continue;

                CauHoi::create([
                    'ma_cau_hoi' => (string) Str::uuid(),
                    'cau_hoi' => $q['title'],
                    'cau_hoi_bat_buoc' => $q['required'] ?? false,
                    'bieu_mau_ma' => $maBieuMau
                ]);
            }

            DanhSachDiemDanh::create([
                'ma_danh_sach' => (string) Str::uuid(),
                'ten_danh_sach' => $bieuMau->tieu_de,
                'du_lieu_ds' => json_encode($duLieuDanhSach, JSON_UNESCAPED_UNICODE),
                'ngay_tao' => now()->toDateString(),
                'thoi_gian_tao' => now(),
                'bieu_mau_ma' => $maBieuMau,
                'tai_khoan_ma' => $maTaiKhoan,
                'trang_thai' => 1,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'ma_bieu_mau' => $maBieuMau
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi lưu biểu mẫu:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Đã xảy ra lỗi hệ thống']);
        }
    }

    public function show($ma_bieu_mau)
    {
        $bieumau = BieuMau::where('ma_bieu_mau', $ma_bieu_mau)->firstOrFail();
        $cauhois = CauHoi::where('bieu_mau_ma', $ma_bieu_mau)->get();

        $colorNameToHex = [
            'Xanh dương đậm' => '#93c5fd',
            'Đỏ' => '#fca5a5',
            'Tím' => '#c4b5fd',
            'Xanh trời' => '#a5f3fc',
            'Cam' => '#fdba74',
            'Vàng đậm' => '#fde68a',
            'Xanh ngọc' => '#99f6e4',
            'Xanh lá' => '#86efac',
            'Xám nhạt' => '#d1d5db'
        ];

        $mauHex = isset($colorNameToHex[$bieumau->mau]) ? $colorNameToHex[$bieumau->mau] : (
            preg_match('/^#([A-Fa-f0-9]{6})$/', $bieumau->mau) ? $bieumau->mau : null
        );

        $hinhNen = null;
        if ($bieumau->mau === 'Hình ảnh' && $bieumau->hinh_anh) {
            $hinhNen = asset('storage/backgrounds/' . $bieumau->hinh_anh);
        }

        return view('Bieumau.Tao_form', [
            'bieumau' => $bieumau,
            'cauhois' => $cauhois,
            'mau' => $mauHex,
            'hinh_nen' => $hinhNen,
            'isCreating' => false
        ]);
    }
}
