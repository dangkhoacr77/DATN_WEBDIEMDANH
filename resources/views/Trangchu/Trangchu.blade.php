<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hệ thống điểm danh QR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
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
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(
          135deg,
          rgba(255, 255, 255, 0.1) 0%,
          rgba(255, 255, 255, 0) 100%
        );
      }
      .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
          0 10px 10px -5px rgba(0, 0, 0, 0.04);
      }
    </style>
  </head>
  <body class="bg-gray-50 font-sans">
    <!-- Thanh điều hướng -->
    <nav class="bg-white shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <div class="flex-shrink-0 flex items-center">
              <i class="fas fa-qrcode text-primary text-2xl mr-2"></i>
              <span onclick="window.location.href='{{ route('trangchu') }}'"class="text-xl font-bold text-dark">QR Điểm Danh</span>
            </div>
          </div>
          <!-- menu -->
            <div class="avatar-menu" onclick="toggleMenu()"
                style="position: relative; display: flex; align-items: center; gap: 10px; cursor: pointer;">
                @php
                    $user = session('nguoi_dung');
                @endphp

                @if (!$user)
                    <span><a href="{{ route('xacthuc.dang-nhap') }}"class="text-black text-decoration-none">Đăng nhâp /</a></span>
                    <span><a href="{{ route('xacthuc.dang-ky') }}"class="text-black text-decoration-none">Đăng ký</a></span>
                @else
                    
                    <span class="text-black">{{ $user->ho_ten }}</span>
                @endif

                <div id="avatarDropdown"
                    style="position: absolute; right: 0; top: 50px; display: none; background: white; border: 1px solid #ccc; border-radius: 5px; z-index: 100; min-width: 120px;">
                    @if (!$user)
                    @else
                        @if ($user->loai_tai_khoan === 'admin')
                            <a href="{{ route('admin.thong-ke') }}"
                                style="display: block; padding: 10px 15px; text-decoration: none; color: black;">Admin</a>
                        @else
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
      </div>
    </nav>

    <!-- Phần hero -->
    <div class="gradient-bg text-white">
      <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
        <div class="text-center">
          <h1
            class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl"
          >
            Hệ thống điểm danh QR hiện đại
          </h1>
          <p class="mt-6 max-w-2xl mx-auto text-xl opacity-90">
            Quản lý điểm danh dễ dàng, nhanh chóng chỉ với mã QR.
          </p>
          <div class="mt-10 flex justify-center space-x-4">
            <a
              href="{{ route('bieumau.tao') }}"
              class="bg-white text-primary hover:bg-gray-100 px-8 py-3 rounded-md text-base font-medium"
            >
              Tạo biểu mẫu
            </a>
            <a
              href="#"
              class="bg-transparent border-2 border-white hover:bg-white hover:bg-opacity-10 px-8 py-3 rounded-md text-base font-medium"
            >
              Quét mã QR
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Phần quét QR -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <div class="lg:grid lg:grid-cols-2 lg:gap-16 items-center">
        <div class="mb-12 lg:mb-0">
          <h2 class="text-3xl font-extrabold text-dark sm:text-4xl">
            Quét QR dễ dàng
          </h2>
          <p class="mt-4 text-lg text-gray-600">
            Hệ thống cho phép điểm danh nhanh chóng. Chỉ cần quét mã QR trên
            điện thoại của bạn là xong.
          </p>
          <ul class="mt-8 space-y-4">
            <li class="flex items-start">
              <div
                class="flex-shrink-0 bg-primary bg-opacity-10 rounded-full p-2"
              >
                <i class="fas fa-check text-primary"></i>
              </div>
              <p class="ml-3 text-base text-gray-700">
                Ghi nhận điểm danh tức thì
              </p>
            </li>
            <li class="flex items-start">
              <div
                class="flex-shrink-0 bg-primary bg-opacity-10 rounded-full p-2"
              >
                <i class="fas fa-check text-primary"></i>
              </div>
              <p class="ml-3 text-base text-gray-700">
                Không cần cài thêm ứng dụng
              </p>
            </li>
            <li class="flex items-start">
              <div
                class="flex-shrink-0 bg-primary bg-opacity-10 rounded-full p-2"
              >
                <i class="fas fa-check text-primary"></i>
              </div>
              <p class="ml-3 text-base text-gray-700">
                Hoạt động trên mọi điện thoại thông minh
              </p>
            </li>
          </ul>
        </div>
        <div class="relative">
          <div
            class="qr-scanner w-full h-80 bg-gray-200 flex items-center justify-center"
          >
            <div class="text-center">
              <i class="fas fa-qrcode text-6xl text-gray-400 mb-4"></i>
              <p class="text-gray-500">Khu vực quét QR</p>
            </div>
          </div>
          <div class="mt-4 flex justify-center">
            <button
              class="bg-primary hover:bg-indigo-700 text-white px-6 py-2 rounded-md text-sm font-medium flex items-center"
            >
              <i class="fas fa-camera mr-2"></i> Mở 
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Kêu gọi hành động -->
    <div class="bg-primary">
      <div
        class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between"
      >
        <h2
          class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl"
        >
          <span class="block">Sẵn sàng đơn giản hóa việc điểm danh?</span>
          <span class="block text-primary-200"
            >Hãy bắt đầu dùng ngay hôm nay.</span
          >
        </h2>
        <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
          <div class="inline-flex rounded-md shadow">
            <a
              href="#"
              class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-primary bg-white hover:bg-gray-50"
            >
              Bắt đầu
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white">
      <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
          <!-- Các mục như Product, Resources... hãy đổi sang "Sản phẩm", "Tài nguyên", "Công ty", "Kết nối" -->
          <!-- và dịch từng liên kết nhỏ -->
        </div>
        <div class="mt-12 border-t border-gray-700 pt-8">
          <p class="text-base text-gray-400 text-center">
            &copy; 2023 QR Điểm Danh. Đã đăng ký bản quyền.
          </p>
        </div>
      </div>
    </footer>

    <script>
      // Simple animation for feature cards on scroll
      document.addEventListener("DOMContentLoaded", function () {
        const featureCards = document.querySelectorAll(".feature-card");

        const observer = new IntersectionObserver(
          (entries) => {
            entries.forEach((entry) => {
              if (entry.isIntersecting) {
                entry.target.style.opacity = "1";
                entry.target.style.transform = "translateY(0)";
              }
            });
          },
          { threshold: 0.1 }
        );

        featureCards.forEach((card) => {
          card.style.opacity = "0";
          card.style.transform = "translateY(20px)";
          card.style.transition = "all 0.5s ease";
          observer.observe(card);
        });

        // QR Scanner button functionality
        const scannerBtn = document.querySelector(
          'button[aria-label="Open Scanner"]'
        );
        if (scannerBtn) {
          scannerBtn.addEventListener("click", function () {
            alert("QR Scanner would open here in a real implementation");
          });
        }
      });
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
