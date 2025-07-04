<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <style>
        body {
            margin: 0;
            font-family: 'Times New Roman', sans-serif; font-size: 18px;
            background-color: #f5f7fa;
        }
    </style>
    @stack('head')
</head>

<body>
    @php
        $current = Route::currentRouteName();
    @endphp

    <div style="display: flex; min-height: 100vh;">
        <!-- Sidebar -->
        <div
            style="width: 220px; background: white; padding: 30px 0; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column;">
            <div onclick="window.location.href='{{ route('trangchu') }}'"style="text-align: center; font-weight: bold; font-size: 22px; margin-bottom: 40px;">QR Điểm danh</div>
            <div style="display: flex; flex-direction: column;">

                <div onclick="window.location.href='{{ route('admin.thong-ke') }}'"
                    style="padding: 12px 24px; display: flex; align-items: center; gap: 10px; font-size: 16px;
                    {{ $current === 'admin.thong-ke' ? 'color: #0047ff; background-color: #eef3ff; font-weight: bold;' : 'color: #333;' }}
                    cursor: pointer;">
                    🏠 Thống kê
                </div>

                <div onclick="window.location.href='{{ route('admin.ql-bieumau') }}'"
                    style="padding: 12px 24px; display: flex; align-items: center; gap: 10px; font-size: 16px;
                    {{ $current === 'admin.ql-bieumau' ? 'color: #0047ff; background-color: #eef3ff; font-weight: bold;' : 'color: #333;' }}
                    cursor: pointer;">
                    📄 Biểu mẫu
                </div>

                <div onclick="window.location.href='{{ route('admin.ql-taikhoan') }}'"
                    style="padding: 12px 24px; display: flex; align-items: center; gap: 10px; font-size: 16px;
                    {{ $current === 'admin.ql-taikhoan' ? 'color: #0047ff; background-color: #eef3ff; font-weight: bold;' : 'color: #333;' }}
                    cursor: pointer;">
                    👤 Tài Khoản
                </div>

                <div onclick="window.location.href='{{ route('admin.tt-canhan') }}'"
                    style="padding: 12px 24px; display: flex; align-items: center; gap: 10px; font-size: 16px;
                    {{ $current === 'admin.tt-canhan' ? 'color: #0047ff; background-color: #eef3ff; font-weight: bold;' : 'color: #333;' }}
                    cursor: pointer;">
                    ⚙️ Thông tin cá nhân
                </div>

            </div>
        </div>

        <!-- Main content -->
        <div style="flex: 1; display: flex; flex-direction: column;">
            <!-- Header -->
            <div
                style="background: #7da4ff; height: 72px; padding: 0 40px; color: white; font-weight: bold; display: flex; justify-content: space-between; align-items: center;">
                <span  style="font-size: 25px;">@yield('page-title')</span>

                <!-- Avatar + Tên -->
                @php
                    $user = session('nguoi_dung');
                @endphp
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="color: white; font-weight: normal;">{{ $user->ho_ten ?? 'Không xác định' }}</span>
                </div>
            </div>

            <!-- Nội dung -->
            <div style="padding: 40px;">
                @yield('content')
            </div>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
