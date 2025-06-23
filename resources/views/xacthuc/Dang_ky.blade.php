<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng Ký</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss/dist/tailwind.min.css">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-10 rounded-2xl shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6">Đăng Ký</h2>

        {{-- CSRF token --}}
        <form method="POST" action="{{ route('xacthuc.dang-ky.post') }}">
            @csrf

            <label class="block mb-2 font-medium">Email</label>
            <input type="email" name="email"
                class="w-full p-3 mb-4 border rounded"
                placeholder="you@gmail.com"
                value="{{ old('email') }}"
                required>
            @error('email')
                <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
            @enderror

            <label class="block mb-2 font-medium">Tên</label>
            <input type="text" name="name"
                class="w-full p-3 mb-4 border rounded"
                placeholder="Nhập tên"
                value="{{ old('name') }}"
                required>
            @error('name')
                <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
            @enderror

            <label class="block mb-2 font-medium">Loại tài khoản</label>
            <select name="account_type"
                class="w-full p-3 mb-4 border rounded"
                required>
                <option value="">-- Chọn loại tài khoản --</option>
                <option value="form_creator" {{ old('account_type') == 'form_creator' ? 'selected' : '' }}>Tài khoản tạo form</option>
                <option value="attendance_checker" {{ old('account_type') == 'attendance_checker' ? 'selected' : '' }}>Tài khoản điểm danh</option>
            </select>
            @error('account_type')
                <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
            @enderror

            <label class="block mb-2 font-medium">Mật Khẩu</label>
            <input type="password" name="password"
                class="w-full p-3 mb-4 border rounded"
                placeholder="Nhập mật khẩu"
                required>
            @error('password')
                <p class="text-red-500 text-sm mb-2">{{ $message }}</p>
            @enderror

            <label class="block mb-2 font-medium">Nhập Lại Mật Khẩu</label>
            <input type="password" name="password_confirmation"
                class="w-full p-3 mb-6 border rounded"
                placeholder="Nhập lại mật khẩu"
                required>

            <button type="submit"
                class="bg-blue-600 text-white font-semibold py-2 px-4 w-full rounded hover:bg-blue-700">
                Đăng ký
            </button>
        </form>
    </div>
</body>
</html>