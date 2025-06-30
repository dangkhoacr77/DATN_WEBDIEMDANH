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

        <form method="POST" action="{{ route('xacthuc.dang-nhap.post') }}" id="loginForm">
            @csrf

            {{-- Email --}}
            <label class="block mb-2 font-medium">Email</label>
            <input type="email" name="mail" id="emailInput" value="{{ old('mail') }}"
                class="w-full p-3 mb-1 border rounded @error('mail') border-red-500 @enderror"
                placeholder="you@gmail.com">
            <p id="emailError" class="text-red-500 text-sm mb-2 hidden"></p>
            @error('mail')
                <p class="text-red-500 text-sm mb-4">{{ $message }}</p>
            @enderror

            {{-- Mật khẩu --}}
            <label class="block mb-2 font-medium">Mật khẩu</label>
            <input type="password" name="mat_khau" id="passwordInput"
                class="w-full p-3 mb-1 border rounded @error('mat_khau') border-red-500 @enderror"
                placeholder="Nhập mật khẩu">
            <p id="passwordError" class="text-red-500 text-sm mb-2 hidden"></p>
            @error('mat_khau')
                <p class="text-red-500 text-sm mb-4">{{ $message }}</p>
            @enderror

            {{-- ✅ Quên mật khẩu --}}
            {{-- <div class="mb-6 text-right">
                <a href="{{ route('xacthuc.quen-mk') }}" class="text-sm text-blue-600 hover:underline">Quên mật
                    khẩu?</a>
            </div> --}}

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

    {{-- ✅ Script kiểm tra lỗi khi nhập --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const emailInput = document.getElementById("emailInput");
            const emailError = document.getElementById("emailError");

            const passwordInput = document.getElementById("passwordInput");
            const passwordError = document.getElementById("passwordError");

            emailInput.addEventListener("input", function() {
                const emailValue = emailInput.value.trim();
                const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (!emailValue) {
                    emailError.textContent = "Vui lòng nhập email.";
                    emailError.classList.remove("hidden");
                    emailInput.classList.add("border-red-500");
                } else if (!regex.test(emailValue)) {
                    emailError.textContent = "Email không hợp lệ.";
                    emailError.classList.remove("hidden");
                    emailInput.classList.add("border-red-500");
                } else {
                    emailError.textContent = "";
                    emailError.classList.add("hidden");
                    emailInput.classList.remove("border-red-500");
                }
            });

            passwordInput.addEventListener("input", function() {
                const passValue = passwordInput.value.trim();

                if (!passValue) {
                    passwordError.textContent = "Vui lòng nhập mật khẩu.";
                    passwordError.classList.remove("hidden");
                    passwordInput.classList.add("border-red-500");
                } else if (passValue.length < 6) {
                    passwordError.textContent = "Mật khẩu tối thiểu 6 ký tự.";
                    passwordError.classList.remove("hidden");
                    passwordInput.classList.add("border-red-500");
                } else {
                    passwordError.textContent = "";
                    passwordError.classList.add("hidden");
                    passwordInput.classList.remove("border-red-500");
                }
            });
        });
    </script>
</body>

</html>
