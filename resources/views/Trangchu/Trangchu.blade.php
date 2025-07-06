<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hệ thống điểm danh QR</title>

    <!-- Tailwind + Font‑Awesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#4F46E5",
                        secondary: "#10B981",
                        dark: "#1F2937",
                        light: "#F9FAFB",
                    },
                },
            },
        };
    </script>

    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #4f46e5 0%, #10b981 100%);
        }

        .qr-scanner {
            border: 3px dashed rgba(255, 255, 255, 0.5);
            border-radius: 1rem;
            position: relative;
            overflow: hidden;
        }

        .qr-scanner::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg,
                    rgba(255, 255, 255, 0.1) 0%,
                    rgba(255, 255, 255, 0) 100%);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
                0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900" style="font-family: 'Times New Roman', Times, serif; font-size: 18px;">
    <!-- ========== NAVBAR ========== -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <i class="fas fa-qrcode text-primary text-2xl mr-2"></i>
                    <span onclick="window.location.href='{{ route('trangchu') }}'"
                        class="text-xl font-bold text-dark cursor-pointer">QR Điểm Danh</span>
                </div>

                <!-- Avatar & tên người dùng -->
                @php
                    $user = session('nguoi_dung');
                    $initial = '';
                    if ($user) {
                        $parts = array_filter(explode(' ', trim($user->ho_ten)));
                        $initial = mb_strtoupper(mb_substr(end($parts), 0, 1));
                    }
                @endphp

                <div class="avatar-menu relative flex items-center gap-3 cursor-pointer" onclick="toggleMenu()">
                    @if (!$user)
                        <a href="{{ route('xacthuc.dang-nhap') }}" class="text-black">Đăng nhập</a>
                        <a href="{{ route('xacthuc.dang-ky') }}" class="text-black">Đăng ký</a>
                    @else
                        <!-- AVATAR + NAME -->
                        <div class="flex items-center gap-2">
                            <div
                                class="w-9 h-9 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-sm">
                                {{ $initial }}
                            </div>
                            <span class="text-black font-medium">{{ $user->ho_ten }}</span>
                        </div>
                    @endif

                    <!-- DROPDOWN -->
                    <div id="avatarDropdown"
                        style="position:absolute;right:0;top:50px;display:none;background:white;
                                border:1px solid #ccc;border-radius:5px;z-index:100;min-width:140px;">
                        @if ($user)
                            @if ($user->loai_tai_khoan === 'admin')
                                <a href="{{ route('admin.thong-ke') }}"
                                    class="block px-4 py-2 text-black hover:bg-gray-100">Admin</a>
                            @else
                                <a href="{{ route('nguoidung.tt-canhan') }}"
                                    class="block px-4 py-2 text-black hover:bg-gray-100">Người dùng</a>
                            @endif
                            <a href="{{ route('dang-xuat') }}" class="block px-4 py-2 text-black hover:bg-gray-100">Đăng
                                xuất</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- ========== HERO ========= -->
    <div class="gradient-bg text-white">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-extrabold sm:text-5xl lg:text-6xl">Hệ thống điểm danh QR hiện đại</h1>
            <p class="mt-6 max-w-2xl mx-auto text-xl opacity-90">
                Quản lý điểm danh dễ dàng, nhanh chóng chỉ với mã QR.
            </p>

            <div class="mt-10 flex justify-center space-x-4">
                @if ($user)
                    <a href="{{ route('bieumau.tao') }}"
                        class="bg-white text-primary hover:bg-gray-100 px-8 py-3 rounded-md font-medium">
                        Tạo biểu mẫu
                    </a>
                @endif
                <a href="#qr-scanner-area"
                    class="border-2 border-white hover:bg-white hover:bg-opacity-10 px-8 py-3 rounded-md font-medium">
                    Quét mã QR
                </a>
            </div>
        </div>
    </div>

    <!-- ========== QR SECTION ========== -->
    <div id="qr-scanner-area" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="lg:grid lg:grid-cols-2 lg:gap-16 items-center">
            <div class="mb-12 lg:mb-0">
                <h2 class="text-3xl font-extrabold text-dark sm:text-4xl">Quét QR dễ dàng</h2>
                <p class="mt-4 text-lg text-gray-600">
                    Hệ thống cho phép điểm danh nhanh chóng. Chỉ cần quét mã QR trên điện thoại của bạn là xong.
                </p>
                <ul class="mt-8 space-y-4">
                    @foreach (['Ghi nhận điểm danh tức thì', 'Không cần cài thêm ứng dụng', 'Hoạt động trên mọi điện thoại thông minh'] as $txt)
                        <li class="flex items-start">
                            <span class="flex-shrink-0 bg-primary bg-opacity-10 rounded-full p-2">
                                <i class="fas fa-check text-primary"></i>
                            </span>
                            <p class="ml-3 text-base text-gray-700">{{ $txt }}</p>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <div id="reader"
                    class="qr-scanner w-full h-80 bg-gray-200 flex items-center justify-center rounded-md">
                    <span class="text-gray-500">Khu vực quét QR</span>
                </div>
                <div class="mt-4 flex justify-center">
                    <button id="start-camera-btn"
                        class="bg-primary hover:bg-indigo-700 text-white px-6 py-2 rounded-md flex items-center">
                        <i class="fas fa-camera mr-2"></i> Mở
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== CTA ========== -->
    <div class="bg-primary">
        <div
            class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                <span>Sẵn sàng đơn giản hóa việc điểm danh?</span>
                <span class="block text-primary-200">Hãy bắt đầu dùng ngay hôm nay.</span>
            </h2>
            <a href="#"
                class="mt-8 lg:mt-0 inline-flex items-center justify-center px-5 py-3 rounded-md bg-white text-primary hover:bg-gray-50">
                Bắt đầu
            </a>
        </div>
    </div>

    <!-- ========== FOOTER ========== -->
    <footer class="bg-dark text-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
            <div class="border-t border-gray-700 pt-8 text-center">
                <p class="text-base text-gray-400">&copy; 2023 QR Điểm Danh. Đã đăng ký bản quyền.</p>
            </div>
        </div>
    </footer>

    <!-- ========== SCRIPTS ========== -->
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        // Avatar dropdown
        function toggleMenu() {
            const menu = document.getElementById("avatarDropdown");
            menu.style.display = menu.style.display === "block" ? "none" : "block";
        }
        window.onclick = e => {
            if (!e.target.closest('.avatar-menu')) {
                document.getElementById("avatarDropdown").style.display = 'none';
            }
        };

        // QR scanner
        document.addEventListener("DOMContentLoaded", () => {
            const cameraBtn = document.getElementById("start-camera-btn");
            const html5Qr = new Html5Qrcode("reader");
            let running = false,
                camId = null;

            cameraBtn.addEventListener("click", () => {
                if (running) {
                    html5Qr.stop().then(() => {
                        running = false;
                        cameraBtn.innerHTML = '<i class="fas fa-camera mr-2"></i> Mở';
                    });
                    return;
                }

                html5Qr.start({
                        facingMode: "environment"
                    }, {
                        fps: 10,
                        qrbox: 250
                    },
                    msg => {
                        if (/^https?:\/\//.test(msg)) {
                            running = false;
                            cameraBtn.innerHTML = '<i class="fas fa-camera mr-2"></i> Mở';
                            window.location.href = msg;
                        } else {
                            alert("QR không chứa URL hợp lệ: " + msg);
                            html5Qr.stop();
                        }
                    },
                    err => {
                        console.warn("Lỗi quét:", err);
                    }
                ).then(() => {
                    running = true;
                    cameraBtn.innerHTML = '<i class="fas fa-times mr-2"></i> Tắt';
                }).catch(err => {
                    console.error("Không thể mở camera:", err);
                    alert(
                    "Không thể mở camera. Vui lòng kiểm tra quyền hoặc thử trình duyệt khác.");
                });
            });

        });
    </script>
</body>

</html>
