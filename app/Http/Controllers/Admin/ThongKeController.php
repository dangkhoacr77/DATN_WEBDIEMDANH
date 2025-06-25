<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BieuMau;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ThongKeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Lấy lượt truy cập theo tháng
        $thongKeLuotTruyCap = DB::table('luot_truy_cap')
            ->selectRaw('MONTH(thoi_gian) as thang, COUNT(*) as tong')
            ->groupByRaw('MONTH(thoi_gian)')
            ->pluck('tong', 'thang')
            ->toArray();

        $duLieuLuotTruyCap = [];
        for ($i = 1; $i <= 12; $i++) {
            $duLieuLuotTruyCap[] = $thongKeLuotTruyCap[$i] ?? 0;
        }

        // Thống kê biểu mẫu
        $tatCaBieuMau = BieuMau::all();
        $thongKeBieuMau = array_fill(1, 12, 0);
        foreach ($tatCaBieuMau as $bieuMau) {
            $thang = date('n', strtotime($bieuMau->ngay_tao));
            $thongKeBieuMau[$thang]++;
        }

        return view('admin.Thong_ke', [
            'visitCounts' => $duLieuLuotTruyCap,
            'bieuMauData' => array_values($thongKeBieuMau),
        ]);
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
