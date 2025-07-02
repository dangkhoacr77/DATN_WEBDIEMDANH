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

        <form method="POST" action="{{ route('xacthuc.dang-ky.post') }}" class="space-y-4" novalidate>
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Email -->
                <div>
                    <label class="block mb-1 font-medium">Email</label>
                    <input type="email" name="mail" value="{{ old('mail') }}"
                        class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <p class="text-red-500 text-sm mt-1 error-message"></p>
                    @error('mail')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Họ tên -->
                <div>
                    <label class="block mb-1 font-medium">Họ tên</label>
                    <input type="text" name="ho_ten" value="{{ old('ho_ten') }}"
                        class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <p class="text-red-500 text-sm mt-1 error-message"></p>
                    @error('ho_ten')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ngày sinh -->
                <div>
                    <label class="block mb-1 font-medium">Ngày sinh</label>
                    <input type="date" name="ngay_sinh" value="{{ old('ngay_sinh') }}"
                        class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <p class="text-red-500 text-sm mt-1 error-message"></p>
                    @error('ngay_sinh')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Số điện thoại -->
                <div>
                    <label class="block mb-1 font-medium">Số điện thoại</label>
                    <input type="text" name="so_dien_thoai" value="{{ old('so_dien_thoai') }}"
                        class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <p class="text-red-500 text-sm mt-1 error-message"></p>
                    @error('so_dien_thoai')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Mật khẩu -->
            <div>
                <label class="block mb-1 font-medium">Mật khẩu</label>
                <input type="password" name="mat_khau"
                    class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                <p class="text-red-500 text-sm mt-1 error-message"></p>
                @error('mat_khau')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nhập lại mật khẩu -->
            <div>
                <label class="block mb-1 font-medium">Nhập lại mật khẩu</label>
                <input type="password" name="mat_khau_confirmation"
                    class="w-full p-3 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
                <p class="text-red-500 text-sm mt-1 error-message"></p>
                @error('mat_khau_confirmation')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded font-semibold transition">
                    Đăng ký
                </button>
            </div>
        </form>
    </div>

    <!-- ✅ Script validation real-time -->
    <script>
        const fields = {
            mail: {
                input: document.querySelector('input[name="mail"]'),
                validate: val => {
                    if (!val.trim()) return 'Vui lòng nhập email.';
                    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!regex.test(val)) return 'Email không đúng định dạng.';
                    return '';
                }
            },
            ho_ten: {
                input: document.querySelector('input[name="ho_ten"]'),
                validate: val => {
                    if (!val.trim()) return 'Vui lòng nhập họ tên.';
                    if (val.length > 100) return 'Họ tên tối đa 100 ký tự.';
                    return '';
                }
            },
            ngay_sinh: {
                input: document.querySelector('input[name="ngay_sinh"]'),
                validate: val => {
                    if (!val.trim()) return 'Vui lòng chọn ngày sinh.';
                    return '';
                }
            },
            so_dien_thoai: {
                input: document.querySelector('input[name="so_dien_thoai"]'),
                validate: val => {
                    if (!val.trim()) return '';
                    if (val.length > 10) return 'Số điện thoại tối đa 10 ký tự.';
                    return '';
                }
            },
            mat_khau: {
                input: document.querySelector('input[name="mat_khau"]'),
                validate: val => {
                    if (!val.trim()) return 'Vui lòng nhập mật khẩu.';
                    if (val.length < 6) return 'Mật khẩu phải có ít nhất 6 ký tự.';
                    const regex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[\W_]).+$/;
                    if (!regex.test(val)) return 'Mật khẩu phải chứa ít nhất 1 chữ cái, 1 số và 1 ký tự đặc biệt.';
                    return '';
                }
            },
            mat_khau_confirmation: {
                input: document.querySelector('input[name="mat_khau_confirmation"]'),
                validate: val => {
                    const original = fields.mat_khau.input.value;
                    if (!val.trim()) return 'Vui lòng nhập lại mật khẩu.';
                    if (val !== original) return 'Mật khẩu nhập lại không khớp.';
                    return '';
                }
            }
        };

        function showError(input, message) {
            const errorEl = input.parentElement.querySelector('.error-message');
            errorEl.textContent = message;
        }

        Object.values(fields).forEach(field => {
            field.input.addEventListener('input', () => {
                const message = field.validate(field.input.value);
                showError(field.input, message);
            });
        });
    </script>
</body>
</html>
