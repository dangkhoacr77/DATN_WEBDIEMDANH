<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Trả lời Biểu Mẫu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
@php
    $colorMap = [
        'Trắng' => '#ffffff',
        'Đỏ' => '#fca5a5',
        'Tím' => '#c4b5fd',
        'Xanh dương đậm' => '#93c5fd',
        'Xanh trời' => '#a5f3fc',
        'Cam' => '#fdba74',
        'Vàng đậm' => '#fde68a',
        'Xanh ngọc' => '#99f6e4',
        'Xanh lá' => '#86efac',
        'Xám nhạt' => '#d1d5db',
    ];

    $mauTen = $bieuMau->mau ?? 'Trắng';
    $mauHex = $colorMap[$mauTen] ?? '#ffffff';
@endphp

<body style="background-color: {{ $mauHex }};" class="min-h-screen">
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm py-4 px-6 flex items-center justify-between border-b">
            <h1 class="text-xl font-medium text-gray-800">Trả lời Biểu Mẫu</h1>
            <button class="text-gray-600 hover:text-indigo-600" title="Tài khoản">
                <span class="material-icons">account_circle</span>
            </button>
        </header>

        <!-- Thông báo thành công -->
        @if (session('success') || isset($success))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative max-w-xl mx-auto mt-4"
                role="alert" id="success-alert">
                <strong class="font-bold">Thành công!</strong>
                <span class="block sm:inline">{{ session('success') ?? $success }}</span>
            </div>
        @endif

        <div class="flex flex-1 overflow-hidden">
            <div class="flex-1 overflow-auto p-8">
                <div class="max-w-3xl mx-auto">
                    <!-- Form Start -->
                    <form method="POST" action="{{ route('traloi-bieumau.store') }}">
                        @csrf

                        <!-- Thông tin biểu mẫu -->
                        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                            <h2 class="text-2xl font-medium text-gray-800 mb-1">{{ $bieuMau->tieu_de }}</h2>
                            <p class="text-gray-500">{{ $bieuMau->mo_ta_tieu_de ?? 'Không có mô tả' }}</p>
                        </div>

                        @if (!isset($hideQuestions) || !$hideQuestions)
                            <!-- Hiển thị các câu hỏi -->
                            @foreach ($bieuMau->cauHois as $index => $cauHoi)
                                <div class="bg-white rounded-lg shadow-sm p-6 mb-4">
                                    <label class="block text-gray-700 font-medium mb-2">
                                        {{ $index + 1 }}. {{ $cauHoi->cau_hoi }}
                                        @if ($cauHoi->cau_hoi_bat_buoc)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>

                                    <input type="text" name="cau_tra_loi[{{ $cauHoi->ma_cau_hoi }}]"
                                        class="w-full border p-2 rounded"
                                        {{ $cauHoi->cau_hoi_bat_buoc ? 'required' : '' }}>
                                </div>
                            @endforeach
                        @endif


                        <!-- Hidden device info + form id -->
                        <input type="hidden" name="device_name" id="device_name">
                        <input type="hidden" name="location" id="location">
                        <input type="hidden" name="bieu_mau_ma" value="{{ $bieuMau->ma_bieu_mau }}">

                        @if (!isset($hideQuestions) || !$hideQuestions)
                            <!-- Submit -->
                            <div class="text-right mt-6">
                                <button
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2 rounded">
                                    Gửi
                                </button>
                            </div>
                        @endif
                    </form>
                    <!-- Form End -->
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('device_name').value = navigator.userAgent;

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(async (position) => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;

                    try {
                        const res = await fetch(
                            `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lon}&key=YOUR_API_KEY`
                        );
                        const data = await res.json();
                        document.getElementById('location').value = data.results[0]
                            ?.formatted_address || `${lat},${lon}`;
                    } catch {
                        document.getElementById('location').value = `${lat},${lon}`;
                    }
                }, () => {
                    document.getElementById('location').value = '';
                });
            }

            const alert = document.getElementById('success-alert');
            if (alert) {
                setTimeout(() => alert.remove(), 4000);
            }
        });
    </script>
</body>

</html>
