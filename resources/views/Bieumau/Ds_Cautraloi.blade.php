<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Câu Trả Lời - Google Forms Clone</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm py-4 px-6 flex items-center justify-between border-b">
            <div class="flex items-center space-x-4">
                <h1 class="text-xl font-medium text-gray-800">Câu Trả Lời</h1>
            </div>
            <div class="flex items-center space-x-4">
                <form method="POST">
                    <button type="submit" class="text-white bg-green-500 hover:bg-green-600 px-4 py-2 rounded-md">
                        Lưu vào tài khoản
                    </button>
                </form>
                <button class="text-gray-600 hover:text-indigo-600" title="Tài khoản">
                    <span class="material-icons">account_circle</span>
                </button>
            </div>
        </header>

        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar -->
            <div class="w-16 bg-white shadow-md flex flex-col items-center py-4 space-y-6">
                <a href="{{ route('bieumau.tao') }}"
                    class="text-gray-700 hover:text-indigo-600 p-2 rounded-full hover:bg-indigo-50" title="Biểu mẫu">
                    <span class="material-icons">view_headline</span>
                </a>
                <a href="{{ route('bieumau.ds-cautraloi') }}" class="text-indigo-600 bg-indigo-50 p-2 rounded-full"
                    title="Câu Trả lời">
                    <span class="material-icons">description</span>
                </a>
                <button onclick="window.location.href='{{ route('bieumau.cai-dat') }}'"
                    class="text-gray-700 hover:text-indigo-600 p-2 rounded-full hover:bg-indigo-50" title="Cài đặt">
                    <span class="material-icons">settings</span>
                </button>
            </div>

            <!-- Nội dung chính -->
            <main class="flex-1 overflow-auto p-8">
                <div class="max-w-4xl mx-auto space-y-6">
                    {{-- @if ($diemDanhs->isEmpty())
                        <div class="bg-white p-6 rounded-lg shadow text-gray-500 text-center">
                            Chưa có câu trả lời nào.
                        </div>
                    @else
                        @foreach ($diemDanhs as $index => $dd)
                            <div class="bg-white p-6 rounded-lg shadow">
                                <h2 class="font-semibold text-lg mb-4">
                                    Lần trả lời {{ $index + 1 }} - Người trả lời: {{ $dd->taiKhoan->ho_ten ?? 'Không rõ' }}
                                </h2>
                                <ul class="space-y-2">
                                    @foreach ($dd->cauTraLoi as $ctl)
                                        <li>
                                            <strong>{{ $ctl->cauHoi->cau_hoi }}:</strong> {{ $ctl->cau_tra_loi }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    @endif --}}
                </div>
            </main>
        </div>
    </div>
</body>

</html>
