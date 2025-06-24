<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Trang điểm danh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background: #f8f9fa;">

    <!-- Header -->
    <nav class="navbar navbar-light bg-primary text-white px-3 d-flex justify-content-between">
        <div class="navbar-brand mb-0 h1 text-white">QR Điểm Danh</div>
        <div class="d-flex align-items-center gap-3">

            <!-- Avatar & menu -->
            <div class="avatar-menu" onclick="toggleMenu()"
                style="position: relative; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                @php
                    $user = session('nguoi_dung');
                @endphp

                @if (!$user)
                    <img src="{{ asset('images/default-avatar.png') }}" alt="avatar" class="rounded-circle"
                        width="32">
                    <span><a class="text-white text-decoration-none">Khách</a></span>
                @else
                    <img src="{{ $user->hinh_anh ?? asset('images/default-avatar.png') }}" alt="avatar"
                        class="rounded-circle" width="32">
                    <span class="text-white">{{ $user->ho_ten }}</span>
                @endif

                <div id="avatarDropdown"
                    style="position: absolute; right: 0; top: 50px; display: none; background: white; border: 1px solid #ccc; border-radius: 5px; z-index: 100; min-width: 120px;">
                    @if (!$user)
                        <a href="{{ route('xacthuc.dang-nhap') }}"
                            style="display: block; padding: 10px 15px; text-decoration: none; color: black;">Đăng
                            nhập</a>
                        <a href="{{ route('xacthuc.dang-ky') }}"
                            style="display: block; padding: 10px 15px; text-decoration: none; color: black;">Đăng ký</a>
                    @else
                        @if ($user->loai_tai_khoan === 'admin')
                            <a href="{{ route('xacthuc.dang-nhap') }}"
                                style="display: block; padding: 10px 15px; text-decoration: none; color: black;">Đăng
                                nhập</a>
                            <a href="{{ route('xacthuc.dang-ky') }}"
                                style="display: block; padding: 10px 15px; text-decoration: none; color: black;">Đăng
                                ký</a>
                            <a href="{{ route('admin.thong-ke') }}"
                                style="display: block; padding: 10px 15px; text-decoration: none; color: black;">Admin</a>
                        @else
                            <a href="{{ route('xacthuc.dang-nhap') }}"
                                style="display: block; padding: 10px 15px; text-decoration: none; color: black;">Đăng
                                nhập</a>
                            <a href="{{ route('xacthuc.dang-ky') }}"
                                style="display: block; padding: 10px 15px; text-decoration: none; color: black;">Đăng
                                ký</a>
                            <a href="{{ route('nguoidung.tt-canhan') }}"
                                style="display: block; padding: 10px 15px; text-decoration: none; color: black;">Người
                                dùng</a>
                                
                        @endif

                        {{-- Đăng xuất --}}
                        <form method="POST" action="{{ route('dang-xuat') }}"> {{-- Bạn nên tạo route logout riêng --}}
                            @csrf
                            <button type="submit" class="dropdown-item"
                                style="display: block; padding: 10px 15px; background: none; border: none; width: 100%; text-align: left; color: black;">Đăng
                                xuất</button>
                        </form>
                    @endif
                </div>
            </div>

        </div>
    </nav>


    <!-- Main Content -->
    <div class="container py-5">
        <a href="{{ route('bieumau.tao') }}" class="btn btn-primary mb-4">Tạo biểu mẫu</a>

        <h5 class="fw-bold">Các mẫu điểm danh</h5>
        <div class="d-flex flex-wrap">
            @foreach ($forms as $form)
                <div
                    style="width: 160px; height: 260px; background-color: #ccc; border-radius: 10px; margin: 10px; padding: 8px; text-align: left; font-size: 13px;">
                    <h6>{{ $form->title }}</h6>
                    <p class="text-truncate">{{ Str::limit($form->description, 100) }}</p>
                    <small>Ngày tạo: {{ $form->created_at->format('d/m/Y') }}</small>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-white text-center py-5" style="background: #1c1f3c;">
        <div class="d-flex flex-column align-items-center">
            <div class="d-flex align-items-center mb-2">
                <div
                    style="width: 40px; height: 40px; background-color: #2dc5c5; color: white; border-radius: 50%; font-weight: bold; display: flex; align-items: center; justify-content: center; margin-right: 10px;">
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

    <script>
        function toggleMenu() {
            const menu = document.getElementById("avatarDropdown");
            menu.style.display = menu.style.display === "block" ? "none" : "block";
        }
        window.onclick = function(event) {
            if (!event.target.closest('.avatar-menu')) {
                document.getElementById("avatarDropdown").style.display = 'none';
            }
        }
    </script>

</body>

</html>
