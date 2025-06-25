<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Đăng Ký</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-2xl shadow-md w-full max-w-xl">
        <h2 class="text-2xl font-bold mb-6 text-center">Đăng Ký</h2>

        <form method="POST" action="{{ route('xacthuc.dang-ky.post') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 font-medium">Email</label>
                    <input type="email" name="mail" value="{{ old('mail') }}" required
                        class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label class="block mb-1 font-medium">Họ tên</label>
                    <input type="text" name="ho_ten" value="{{ old('ho_ten') }}" required
                        class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label class="block mb-1 font-medium">Ngày sinh</label>
                    <input type="date" name="ngay_sinh" value="{{ old('ngay_sinh') }}" required
                        class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label class="block mb-1 font-medium">Số điện thoại</label>
                    <input type="text" name="so_dien_thoai" value="{{ old('so_dien_thoai') }}"
                        class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>
            </div>

            <div>
                <label class="block mb-1 font-medium">Loại tài khoản</label>
                <select name="loai_tai_khoan" required
                    class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="">-- Chọn loại tài khoản --</option>
                    <option value="nguoi_tao_form" {{ old('loai_tai_khoan') == 'nguoi_tao_form' ? 'selected' : '' }}>
                        Tài khoản tạo form</option>
                    <option value="nguoi_diem_danh" {{ old('loai_tai_khoan') == 'nguoi_diem_danh' ? 'selected' : '' }}>
                        Tài khoản điểm danh</option>
                </select>
            </div>

            <div>
                <label class="block mb-1 font-medium">Mật khẩu</label>
                <input type="password" name="mat_khau" required
                    class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block mb-1 font-medium">Nhập lại mật khẩu</label>
                <input type="password" name="mat_khau_confirmation" required
                    class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded font-semibold transition">
                    Đăng ký
                </button>
            </div>
        </form>
    </div>
</body>

</html>
