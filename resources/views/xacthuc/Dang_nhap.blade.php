<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-10 rounded-2xl shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6">Đăng nhập</h2>

        <form method="POST" action="{{ route('xacthuc.dang-nhap.post') }}">
            @csrf

            {{-- Email --}}
            <label class="block mb-2 font-medium">Email</label>
            <input type="email"
                   name="email"
                   value="{{ old('email') }}"
                   class="w-full p-3 mb-4 border rounded"
                   placeholder="you@gmail.com"
                   required>
            @error('email')
                <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
            @enderror

            {{-- Mật khẩu --}}
            <label class="block mb-2 font-medium">Mật Khẩu</label>
            <input type="password"
                   name="password"
                   class="w-full p-3 mb-2 border rounded"
                   placeholder="Nhập mật khẩu"
                   required>
            @error('password')
                <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
            @enderror

            {{-- ✅ Quên mật khẩu --}}
            <div class="mb-6 text-right">
                <a href="{{ route('xacthuc.quen-mk') }}" class="text-sm text-blue-600 hover:underline">Quên mật khẩu?</a>
            </div>

            <button type="submit"
                    class="bg-blue-600 text-white font-semibold py-2 px-4 w-full rounded hover:bg-blue-700">
                Đăng nhập
            </button>
        </form>

        <p class="mt-4 text-center text-sm text-gray-500">
            Bạn chưa có tài khoản?
            <a href="{{ route('xacthuc.dang-ky') }}" class="text-blue-600 hover:underline">Đăng ký</a>
        </p>
    </div>
</body>
</html>
