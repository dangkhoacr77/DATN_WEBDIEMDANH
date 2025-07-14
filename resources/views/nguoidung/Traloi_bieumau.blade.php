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

<body style="{{ $bodyStyle }} font-family: 'Times New Roman', serif;"
    class="min-h-screen bg-cover bg-center bg-no-repeat text-gray-800">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header
            class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-5 px-6 flex items-center justify-between shadow-lg">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-white/20 rounded-full">
                    <span class="material-icons text-2xl">assignment</span>
                </div>
                <h1 class="text-2xl font-bold tracking-tight">Trả lời Biểu Mẫu</h1>
            </div>
            <button onclick="window.location.href='{{ route('trangchu') }}'" class="text-gray-600 hover:text-indigo-600"
                title="Trang chủ">
                <span class="material-icons text-white">home</span>
            </button>
        </header>

        <!-- Thông báo -->
        @if (session('success') || isset($success))
            <div id="success-alert" class="max-w-2xl mx-auto mt-6 transition-all duration-700 animate-fade-in-down">
                <div
                    class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-xl shadow-lg flex items-start gap-3">
                    <span class="material-icons text-green-500 text-2xl mt-0.5">check_circle</span>
                    <div>
                        <p class="font-bold text-lg">Thành công!</p>
                        <p class="text-sm">{{ session('success') ?? $success }}</p>
                    </div>
                    <button onclick="this.parentElement.parentElement.remove()"
                        class="ml-auto text-green-600 hover:text-green-800">
                        <span class="material-icons">close</span>
                    </button>
                </div>
            </div>

            @if (isset($redirectAfter))
                <script>
                    setTimeout(() => {
                        window.location.href = "{{ $redirectAfter }}";
                    }, 3000);
                </script>
            @endif
        @endif

        <!-- Nội dung biểu mẫu -->
        <main class="flex-1 p-6 overflow-auto">
            <div class="max-w-3xl mx-auto space-y-6">
                @if (isset($errorMessage))
                    <div
                        class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-xl shadow-lg animate-fade-in-down mt-4 w-full max-w-full mx-auto">
                        <div class="flex items-start gap-3">
                            <span class="material-icons text-red-500 text-2xl mt-0.5">error</span>
                            <div>
                                <p class="font-bold text-lg">Không thể trả lời biểu mẫu</p>
                                <p class="text-sm">{{ $errorMessage }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="max-w-3xl mx-auto space-y-6">
                    <!-- Tiêu đề biểu mẫu -->
                    <div
                        class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 hover:shadow-lg transition duration-300">
                        <div class="flex items-start gap-3">
                            <div class="bg-indigo-100 p-2 rounded-lg">
                                <span class="material-icons text-indigo-600">description</span>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-800">{{ $bieuMau->tieu_de }}</h2>
                                <p class="text-gray-600 mt-2 text-sm">{{ $bieuMau->mo_ta_tieu_de ?? 'Không có mô tả' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    @if ((!isset($hideQuestions) || !$hideQuestions) && !isset($errorMessage))
                        <form method="POST" action="{{ route('traloi-bieumau.store') }}"
                            class="space-y-4 animate-fade-in" id="form-multi-step">
                            @csrf

                            <!-- Thanh tiến trình -->
                            <div class="w-full bg-gray-200 h-2.5 rounded-full overflow-hidden">
                                <div id="progress-bar"
                                    class="bg-gradient-to-r from-indigo-500 to-purple-500 h-full transition-all duration-500"
                                    style="width: 0%;"></div>
                            </div>

                            <!-- Sections -->
                            @php
                                $sections = collect($bieuMau->cauHois)->chunk(5);
                            @endphp

                            @foreach ($sections as $sectionIndex => $section)
                                <div class="section" data-section="{{ $sectionIndex }}"
                                    style="{{ $sectionIndex > 0 ? 'display:none;' : '' }}">
                                    @foreach ($section as $index => $cauHoi)
                                        <div
                                            class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition duration-300 mb-3">
                                            <div class="flex items-start gap-3 mb-3">
                                                <div class="flex-1">
                                                    <label class="block text-gray-800 font-medium text-base">
                                                        {{ $cauHoi->cau_hoi }}
                                                        @if ($cauHoi->cau_hoi_bat_buoc)
                                                            <span class="text-red-500">*</span>
                                                        @endif
                                                    </label>
                                                    <input type="text" name="cau_tra_loi[{{ $cauHoi->ma_cau_hoi }}]"
                                                        class="w-full border-b border-gray-300 p-2 focus:outline-none focus:border-indigo-500 transition duration-300 mt-1"
                                                        placeholder="Câu trả lời của bạn"
                                                        {{ $cauHoi->cau_hoi_bat_buoc ? 'required' : '' }}>
                                                </div>
                                            </div>
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
                                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-5 py-2.5 rounded-lg flex items-center gap-2 transition duration-300"
                                    style="display: none;">
                                    <span class="material-icons text-lg">chevron_left</span>
                                    Quay lại
                                </button>

                                <button type="button" id="next-btn"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-5 py-2.5 rounded-lg flex items-center gap-2 transition duration-300">
                                    Tiếp tục
                                    <span class="material-icons text-lg">chevron_right</span>
                                </button>

                                <button type="submit" id="submit-btn"
                                    class="bg-green-600 hover:bg-green-700 text-white font-medium px-5 py-2.5 rounded-lg flex items-center gap-2 transition duration-300"
                                    style="display: none;">
                                    <span class="material-icons text-lg">send</span>
                                    Gửi biểu mẫu
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
        </main>
    </div>

    <!-- CSS hiệu ứng -->
    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fade-in-down {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.6s ease-out forwards;
        }

        .animate-fade-in-down {
            animation: fade-in-down 0.6s ease-out forwards;
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }
    </style>

    <!-- Script xử lý -->
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

                prevBtn.style.display = index === 0 ? 'none' : 'flex';
                nextBtn.style.display = index === totalSections - 1 ? 'none' : 'flex';
                submitBtn.style.display = index === totalSections - 1 ? 'flex' : 'none';
            }

            nextBtn.addEventListener('click', () => {
                if (currentSection < totalSections - 1) {
                    currentSection++;
                    showSection(currentSection);
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
            });

            prevBtn.addEventListener('click', () => {
                if (currentSection > 0) {
                    currentSection--;
                    showSection(currentSection);
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
            });

            showSection(currentSection);

            document.getElementById('device_name').value = navigator.userAgent;

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    document.getElementById('location').value = `${lat},${lon}`;
                }, (error) => {
                    console.warn('Không lấy được định vị:', error.message);
                    document.getElementById('location').value = '';
                });
            }

            const alert = document.getElementById('success-alert');
            if (alert) setTimeout(() => alert.remove(), 5000);
        });
    </script>
</body>

</html>
