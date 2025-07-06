<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GhiNhanLuotTruyCap
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $ip = $request->ip();
            $userAgent = $request->userAgent();
            $now = Carbon::now();

            Log::info('[Tracking] Middleware chạy', [
                'ip' => $ip,
                'agent' => $userAgent,
            ]);
            DB::table('luot_truy_cap')->insert([
                'ip' => $ip,
                'user_agent' => $userAgent,
                'thoi_gian' => $now
            ]);

            Log::info('[Tracking] Ghi nhận mỗi lượt truy cập');
        } catch (\Throwable $e) {
            Log::error('[Tracking] Lỗi ghi lượt truy cập', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        return $next($request);
    }
}
