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

    $mauHex = $colorMap[$bieuMau->mau ?? 'Trắng'] ?? '#ffffff';
    $backgroundImageUrl = $bieuMau->hinh_anh ? asset('storage/backgrounds/' . $bieuMau->hinh_anh) : null;
    $bodyStyle = "background-color: $mauHex;";
    if ($backgroundImageUrl) {
        $bodyStyle .= " background-image: url('$backgroundImageUrl'); background-size: cover; background-repeat: no-repeat; background-position: center;";
    }
@endphp

<body style="{{ $bodyStyle }}" class="min-h-screen bg-cover bg-center bg-no-repeat font-sans text-gray-800">
    <div class="min-h-screen bg-white/70 backdrop-blur-sm flex flex-col">
        <!-- Header -->
        <header class="bg-gradient-to-r from-indigo-600 to-purple-500 text-white py-4 px-6 flex items-center justify-between shadow-md">
            <h1 class="text-2xl font-bold flex items-center gap-2">
                <span class="material-icons">assignment</span>
                Trả lời Biểu Mẫu
            </h1>
            <button class="hover:text-yellow-300 transition duration-300">
                <span class="material-icons text-4xl">account_circle</span>
            </button>
        </header>

        <!-- Thông báo -->
        @if (session('success') || isset($success))
            <div id="success-alert"
                class="max-w-2xl mx-auto mt-6 transition-all duration-700 animate-fade-in-down">
                <div class="bg-green-100 border-l-4 border-green-500 text-green-800 p-4 rounded-lg shadow-md flex items-center gap-3">
                    <span class="material-icons text-green-600 text-3xl">check_circle</span>
                    <div>
                        <p class="font-bold">Thành công!</p>
                        <p>{{ session('success') ?? $success }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Nội dung biểu mẫu -->
        <main class="flex-1 p-6 overflow-auto">
            <div class="max-w-3xl mx-auto space-y-8">
                <!-- Tiêu đề biểu mẫu -->
                <div class="bg-white/80 backdrop-blur-lg p-6 rounded-2xl shadow-lg border border-gray-200">
                    <h2 class="text-3xl font-bold text-indigo-700">{{ $bieuMau->tieu_de }}</h2>
                    <p class="text-gray-600 mt-2">{{ $bieuMau->mo_ta_tieu_de ?? 'Không có mô tả' }}</p>
                </div>

                @if (!isset($hideQuestions) || !$hideQuestions)
                    <form method="POST" action="{{ route('traloi-bieumau.store') }}" class="space-y-6 animate-fade-in" id="form-multi-step">
                        @csrf

                        <!-- Thanh tiến trình -->
                        <div class="w-full bg-gray-300 h-3 rounded-full overflow-hidden">
                            <div id="progress-bar" class="bg-indigo-600 h-full transition-all duration-500" style="width: 0%;"></div>
                        </div>

                        <!-- Sections -->
                        @php
                            $sections = collect($bieuMau->cauHois)->chunk(5);
                        @endphp

                        @foreach ($sections as $sectionIndex => $section)
                            <div class="section" data-section="{{ $sectionIndex }}" style="{{ $sectionIndex > 0 ? 'display:none;' : '' }}">
                                @foreach ($section as $index => $cauHoi)
                                    <div class="bg-white/80 border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-lg transition duration-300">
                                        <label class="block text-gray-800 font-medium mb-2 text-lg">
                                            {{ $loop->iteration + $sectionIndex * 5 }}. {{ $cauHoi->cau_hoi }}
                                            @if ($cauHoi->cau_hoi_bat_buoc)
                                                <span class="text-red-500">*</span>
                                            @endif
                                        </label>
                                        <input type="text" name="cau_tra_loi[{{ $cauHoi->ma_cau_hoi }}]"
                                               class="w-full border border-gray-300 p-3 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-400 transition duration-300"
                                               placeholder="Nhập câu trả lời..."
                                               {{ $cauHoi->cau_hoi_bat_buoc ? 'required' : '' }}>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                        <!-- Hidden -->
                        <input type="hidden" name="device_name" id="device_name">
                        <input type="hidden" name="location" id="location">
                        <input type="hidden" name="bieu_mau_ma" value="{{ $bieuMau->ma_bieu_mau }}">

                        <!-- Điều hướng -->
                        <div class="flex justify-between mt-6">
                            <button type="button" id="prev-btn"
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold px-6 py-2 rounded-lg"
                                    style="display: none;">Quay lại</button>

                            <button type="button" id="next-btn"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-lg">
                                Tiếp
                            </button>

                            <button type="submit" id="submit-btn"
                                    class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg"
                                    style="display: none;">
                                Gửi biểu mẫu
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </main>
    </div>

    <!-- Hiệu ứng CSS -->
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fade-in-down {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fade-in 0.6s ease-out;
        }

        .animate-fade-in-down {
            animation: fade-in-down 0.6s ease-out;
        }
    </style>

    <!-- Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sections = document.querySelectorAll('.section');
            const progressBar = document.getElementById('progress-bar');
            const nextBtn = document.getElementById('next-btn');
            const prevBtn = document.getElementById('prev-btn');
            const submitBtn = document.getElementById('submit-btn');

            let currentSection = 0;
            const totalSections = sections.length;

            function showSection(index) {
                sections.forEach((section, i) => {
                    section.style.display = i === index ? 'block' : 'none';
                });

                const percent = ((index + 1) / totalSections) * 100;
                progressBar.style.width = `${percent}%`;

                prevBtn.style.display = index === 0 ? 'none' : 'inline-block';
                nextBtn.style.display = index === totalSections - 1 ? 'none' : 'inline-block';
                submitBtn.style.display = index === totalSections - 1 ? 'inline-block' : 'none';
            }

            nextBtn.addEventListener('click', () => {
                if (currentSection < totalSections - 1) {
                    currentSection++;
                    showSection(currentSection);
                }
            });

            prevBtn.addEventListener('click', () => {
                if (currentSection > 0) {
                    currentSection--;
                    showSection(currentSection);
                }
            });

            showSection(currentSection);

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
                        document.getElementById('location').value = data.results[0]?.formatted_address || `${lat},${lon}`;
                    } catch {
                        document.getElementById('location').value = `${lat},${lon}`;
                    }
                }, () => {
                    document.getElementById('location').value = '';
                });
            }

            const alert = document.getElementById('success-alert');
            if (alert) setTimeout(() => alert.remove(), 5000);
        });
    </script>
</body>

</html>
