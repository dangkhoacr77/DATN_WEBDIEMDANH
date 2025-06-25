<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GhiNhanLuotTruyCap
{
    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $now = Carbon::now();

        // Tìm lượt truy cập mới nhất của IP + thiết bị
        $luotTruyCapGanNhat = DB::table('luot_truy_cap')
            ->where('ip', $ip)
            ->where('user_agent', $userAgent)
            ->orderByDesc('thoi_gian')
            ->first();

        if (!$luotTruyCapGanNhat || Carbon::parse($luotTruyCapGanNhat->thoi_gian)->diffInMinutes($now) >= 10) {
            // Lưu lượt truy cập mới nếu chưa có trong 10 phút
            DB::table('luot_truy_cap')->insert([
                'ip' => $ip,
                'user_agent' => $userAgent,
                'thoi_gian' => $now
            ]);
        }

        return $next($request);
    }
}
