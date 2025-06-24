<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body style="background: #f8f9fa; font-family: 'Segoe UI', sans-serif;">
    @php
        $user = session('nguoi_dung');
    @endphp

    <!-- Navbar -->
    <nav class="navbar navbar-light bg-primary text-white px-3 d-flex justify-content-between">
        <div class="navbar-brand mb-0 h1 text-white">QR Điểm danh</div>

        <div class="d-flex align-items-center gap-3 avatar-menu" onclick="toggleMenu()">
            <img src="{{ $user->hinh_anh ?? asset('images/default-avatar.png') }}" alt="avatar" class="rounded-circle" width="32">
            <span class="text-white">{{ $user->ho_ten ?? 'Khách' }}</span>

            <div id="avatarDropdown" class="position-absolute"
                style="right:0; top:50px; display:none; background:#fff; border:1px solid #ccc; border-radius:5px; min-width:120px; z-index:100;">
                @if (!$user)
                    <a href="{{ route('xacthuc.dang-nhap') }}" class="d-block px-3 py-2 text-dark text-decoration-none">Đăng nhập</a>
                    <a href="{{ route('xacthuc.dang-ky') }}" class="d-block px-3 py-2 text-dark text-decoration-none">Đăng ký</a>
                @else
                    <a href="#" class="d-block px-3 py-2 text-dark text-decoration-none">Cài đặt</a>
                    <form method="POST" action="{{ route('dang-xuat') }}" class="m-0">
                        @csrf
                        <button type="submit" class="d-block w-100 text-start px-3 py-2 border-0 bg-white">Đăng xuất</button>
                    </form>
                @endif
            </div>
        </div>
    </nav>

    <!-- Main layout -->
    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-sm-12 mb-4">
                <div class="bg-white rounded shadow-sm p-2">
                    <nav class="nav flex-column">
                        {{-- Thông tin cá nhân --}}
                        <a href="{{ route('nguoidung.tt-canhan') }}"
                           style="display:block; padding:12px 16px; text-decoration:none;
                                  color:{{ request()->routeIs('nguoidung.tt-canhan') ? '#0d6efd' : '#333' }};
                                  font-weight:{{ request()->routeIs('nguoidung.tt-canhan') ? 'bold' : '500' }};
                                  border-left:4px solid {{ request()->routeIs('nguoidung.tt-canhan') ? '#0d6efd' : 'transparent' }};
                                  background-color: {{ request()->routeIs('nguoidung.tt-canhan') ? '#e9f1ff' : 'transparent' }};">
                            Thông tin cá nhân
                        </a>

                        {{-- Người tạo form --}}
                        @if ($user && $user->loai_tai_khoan === 'người tạo form')
                            <a href="{{ route('nguoidung.ql-bieumau') }}"
                               style="display:block; padding:12px 16px; text-decoration:none;
                                      color:{{ request()->routeIs('nguoidung.ql-bieumau') ? '#0d6efd' : '#333' }};
                                      font-weight:{{ request()->routeIs('nguoidung.ql-bieumau') ? 'bold' : '500' }};
                                      border-left:4px solid {{ request()->routeIs('nguoidung.ql-bieumau') ? '#0d6efd' : 'transparent' }};
                                      background-color: {{ request()->routeIs('nguoidung.ql-bieumau') ? '#e9f1ff' : 'transparent' }};">
                                Danh sách biểu mẫu
                            </a>
                            <a href="{{ route('nguoidung.ql-danhsach') }}"
                               style="display:block; padding:12px 16px; text-decoration:none;
                                      color:{{ request()->routeIs('nguoidung.ql-danhsach') ? '#0d6efd' : '#333' }};
                                      font-weight:{{ request()->routeIs('nguoidung.ql-danhsach') ? 'bold' : '500' }};
                                      border-left:4px solid {{ request()->routeIs('nguoidung.ql-danhsach') ? '#0d6efd' : 'transparent' }};
                                      background-color: {{ request()->routeIs('nguoidung.ql-danhsach') ? '#e9f1ff' : 'transparent' }};">
                                Danh sách điểm danh
                            </a>
                        @endif

                        {{-- Người điểm danh --}}
                        @if ($user && $user->loai_tai_khoan === 'người điểm danh')
                            <a href="{{ route('nguoidung.ls-diemdanh') }}"
                               style="display:block; padding:12px 16px; text-decoration:none;
                                      color:{{ request()->routeIs('nguoidung.ls-diemdanh') ? '#0d6efd' : '#333' }};
                                      font-weight:{{ request()->routeIs('nguoidung.ls-diemdanh') ? 'bold' : '500' }};
                                      border-left:4px solid {{ request()->routeIs('nguoidung.ls-diemdanh') ? '#0d6efd' : 'transparent' }};
                                      background-color: {{ request()->routeIs('nguoidung.ls-diemdanh') ? '#e9f1ff' : 'transparent' }};">
                                Lịch sử điểm danh
                            </a>
                        @endif
                    </nav>
                </div>
            </div>

            <!-- Main content -->
            <div class="col-md-9 col-sm-12">
                <div class="bg-white rounded p-4 shadow-sm">
                    <h4 class="mb-3" style="font-weight: bold;">@yield('page-title')</h4>
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-white text-center py-5" style="background: #1c1f3c;">
        <div class="d-flex flex-column align-items-center">
            <div class="d-flex align-items-center mb-2">
                <div style="width: 40px; height: 40px; background-color: #2dc5c5; color: white; border-radius: 50%; font-weight: bold; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
                    QR DD
                </div>
                <span class="text-white">Dự án lập trình web</span>
            </div>
            <p class="mt-2">Bạn có hài lòng khi sử dụng trang web?</p>
            <form method="POST" action="#" class="d-flex justify-content-center gap-2 mb-3">
                @csrf
                <input type="text" name="feedback" class="form-control w-200" placeholder="Đánh giá" required>
                <button type="submit" class="btn btn-info text-white">Gửi</button>
            </form>
            <small>Lý Thanh Duy | Võ Thành Đăng Khoa</small><br>
            <small>Khóa học 2022 – 2025.</small>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        function toggleMenu() {
            const menu = document.getElementById('avatarDropdown');
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        }
        window.onclick = function(event) {
            if (!event.target.closest('.avatar-menu')) {
                const menu = document.getElementById('avatarDropdown');
                if (menu) menu.style.display = 'none';
            }
        };
    </script>
    @stack('scripts')
</body>

</html>
