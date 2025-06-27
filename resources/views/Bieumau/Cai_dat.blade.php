<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Cài Đặt Biểu Mẫu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm py-4 px-6 flex items-center justify-between border-b">
            <div class="flex items-center space-x-4">
                <h1 class="text-xl font-medium text-gray-800">Cài Đặt</h1>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-gray-600 font-medium hidden sm:inline">Cài đặt</span>
                <button class="text-gray-600 hover:text-indigo-600" title="Tài khoản">
                    <span class="material-icons">account_circle</span>
                </button>
            </div>
        </header>

        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar -->
            <div class="w-16 bg-white shadow-md flex flex-col items-center py-4 space-y-6">
                <a href="{{ route('bieumau.tao') }}"
                   class="text-gray-700 hover:text-indigo-600 p-2 rounded-full hover:bg-indigo-50" title="Biểu mẫu">
                    <span class="material-icons">view_headline</span>
                </a>
                <a href="{{ route('bieumau.ds-cautraloi') }}"
                   class="text-gray-700 hover:text-indigo-600 p-2 rounded-full hover:bg-indigo-50" title="Câu Trả lời">
                    <span class="material-icons">description</span>
                </a>
                <a href="{{ route('bieumau.cai-dat') }}" class="text-indigo-600 bg-indigo-50 p-2 rounded-full"
                   title="Cài đặt">
                    <span class="material-icons">settings</span>
                </a>
            </div>

            <!-- Nội dung -->
            <main class="flex-1 overflow-auto p-8">
                <div class="max-w-3xl mx-auto bg-white rounded-xl shadow p-8 space-y-6">

                    @if (session('success'))
                        <div class="bg-green-100 text-green-800 p-3 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 text-red-800 p-3 rounded-md">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('bieumau.luu-cai-dat') }}" class="space-y-6">
                        @csrf

                        <!-- Giới hạn thời gian -->
                        <div class="border border-gray-200 p-4 rounded-lg">
                            <label class="flex items-center justify-between mb-2">
                                <span class="font-medium">Giới hạn thời gian hoạt động</span>
                                <input type="checkbox" name="enable_time_limit" class="toggle-setting"
                                       data-target="time-limit"
                                       {{ $bieuMau->thoi_luong_diem_danh ? 'checked' : '' }}>
                            </label>
                            <input type="range" min="0" max="60"
                                   value="{{ $bieuMau->thoi_luong_diem_danh ?? 30 }}"
                                   id="time-limit" name="time_limit"
                                   class="w-full {{ $bieuMau->thoi_luong_diem_danh ? '' : 'opacity-50' }}"
                                   {{ $bieuMau->thoi_luong_diem_danh ? '' : 'disabled' }}>
                            <p class="text-sm text-gray-600 mt-1">Thời gian:
                                <span id="time-value">{{ $bieuMau->thoi_luong_diem_danh ?? 30 }}</span> phút</p>
                        </div>

                        <!-- Giới hạn số lượng người -->
                        <div class="border border-gray-200 p-4 rounded-lg">
                            <label class="flex items-center justify-between mb-2">
                                <span class="font-medium">Đóng form khi đủ số lượng người tham gia</span>
                                <input type="checkbox" name="enable_participant_limit" class="toggle-setting"
                                       data-target="participant-limit"
                                       {{ $bieuMau->gioi_han_diem_danh ? 'checked' : '' }}>
                            </label>
                            <input type="range" min="0" max="200"
                                   value="{{ $bieuMau->gioi_han_diem_danh ?? 100 }}"
                                   id="participant-limit" name="participant_limit"
                                   class="w-full {{ $bieuMau->gioi_han_diem_danh ? '' : 'opacity-50' }}"
                                   {{ $bieuMau->gioi_han_diem_danh ? '' : 'disabled' }}>
                            <p class="text-sm text-gray-600 mt-1">Giới hạn:
                                <span id="participant-value">{{ $bieuMau->gioi_han_diem_danh ?? 100 }}</span> người</p>
                        </div>

                        <!-- Lấy định vị -->
                        <div class="border border-gray-200 p-4 rounded-lg flex items-center justify-between">
                            <span class="font-medium">Lấy định vị</span>
                            <input type="checkbox" name="geo_location">
                        </div>

                        <!-- Lấy tên thiết bị -->
                        <div class="border border-gray-200 p-4 rounded-lg flex items-center justify-between">
                            <span class="font-medium">Lấy tên thiết bị</span>
                            <input type="checkbox" name="device_name">
                        </div>

                        <!-- Lấy email -->
                        <div class="border border-gray-200 p-4 rounded-lg flex items-center justify-between">
                            <span class="font-medium">Lấy tài khoản email</span>
                            <input type="checkbox" name="email_account">
                        </div>

                        <div class="pt-4">
                            <button type="submit"
                                    class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">
                                Lưu cài đặt
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script>
        const timeRange = document.getElementById('time-limit');
        const participantRange = document.getElementById('participant-limit');

        timeRange?.addEventListener('input', () => {
            document.getElementById('time-value').innerText = timeRange.value;
        });

        participantRange?.addEventListener('input', () => {
            document.getElementById('participant-value').innerText = participantRange.value;
        });

        document.querySelectorAll('.toggle-setting').forEach(cb => {
            cb.addEventListener('change', function () {
                const targetId = this.dataset.target;
                const input = document.getElementById(targetId);
                if (input) {
                    input.disabled = !this.checked;
                    input.classList.toggle('opacity-50', !this.checked);
                }
            });
        });
    </script>
</body>

</html>
