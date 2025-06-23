<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đặt lại mật khẩu</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">
  <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Đặt lại mật khẩu</h2>

    {{-- ✅ Thông báo thành công --}}
    @if (session('thong_bao'))
      <div class="mb-4 p-3 text-green-700 bg-green-100 rounded">
        {{ session('thong_bao') }}
      </div>
    @endif

    {{-- ✅ Hiển thị lỗi --}}
    @if ($errors->any())
      <div class="mb-4 p-3 text-red-700 bg-red-100 rounded">
        {{ $errors->first() }}
      </div>
    @endif

    <form method="POST" action="{{ route('xacthuc.dat-lai-mk.post') }}">
      @csrf

      {{-- Mật khẩu --}}
      <div class="mb-5">
        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Mật khẩu</label>
        <div class="relative">
          <input type="password" name="mat_khau" id="password" placeholder="Nhập mật khẩu" required
                 class="w-full px-4 py-3 border rounded-lg pr-12 focus:outline-none focus:ring-2 focus:ring-blue-400">
          <span onclick="togglePassword('password', this)"
                class="absolute top-1/2 right-4 transform -translate-y-1/2 text-gray-500 cursor-pointer text-lg">👁️</span>
        </div>
      </div>

      {{-- Nhập lại mật khẩu --}}
      <div class="mb-6">
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Nhập lại mật khẩu</label>
        <div class="relative">
          <input type="password" name="mat_khau_confirmation" id="password_confirmation" placeholder="Nhập lại mật khẩu" required
                 class="w-full px-4 py-3 border rounded-lg pr-12 focus:outline-none focus:ring-2 focus:ring-blue-400">
          <span onclick="togglePassword('password_confirmation', this)"
                class="absolute top-1/2 right-4 transform -translate-y-1/2 text-gray-500 cursor-pointer text-lg">👁️</span>
        </div>
      </div>

      <button type="submit"
              class="w-full py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition duration-200">
        Xác nhận
      </button>
    </form>
  </div>

  <script>
    function togglePassword(id, el) {
      const input = document.getElementById(id);
      if (input.type === "password") {
        input.type = "text";
        el.textContent = "🙈";
      } else {
        input.type = "password";
        el.textContent = "👁️";
      }
    }
  </script>
</body>
</html>
