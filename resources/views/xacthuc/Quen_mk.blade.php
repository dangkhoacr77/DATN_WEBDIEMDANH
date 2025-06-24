<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quên mật khẩu</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen px-4">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Quên mật khẩu</h2>

        {{-- Hiển thị thông báo --}}
        @if (session('thong_bao'))
            <div class="mb-4 p-3 text-green-700 bg-green-100 rounded">
                {{ session('thong_bao') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="mb-4 p-3 text-red-700 bg-red-100 rounded">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Form gửi mã --}}
        <form method="POST" action="{{ route('xacthuc.quen-mk.post') }}" class="mb-6">
            @csrf
            <label for="mail" class="block font-medium text-gray-700 mb-1">Email</label>
            <div class="flex gap-2">
                <input type="email" name="mail" id="mail" placeholder="@gmail.com" required
                    class="flex-1 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-400">
                <button type="submit"
                    class="bg-blue-600 text-white px-5 py-3 rounded-lg hover:bg-blue-700 transition">Lấy mã</button>
            </div>
        </form>

        {{-- Form xác nhận mã --}}
        <form method="POST" action="{{ route('xacthuc.quen-mk.verify') }}">
            @csrf
            <label for="ma_xac_nhan" class="block font-medium text-gray-700 mb-1">Mã Xác Nhận</label>
            <input type="text" name="ma_xac_nhan" id="ma_xac_nhan" placeholder="Nhập mã" required
                class="w-full px-4 py-3 mb-4 border rounded-lg focus:ring-2 focus:ring-blue-400">

            <button type="submit"
                class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                Xác nhận
            </button>
        </form>
    </div>
</body>

</html>
