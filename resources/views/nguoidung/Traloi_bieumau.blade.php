<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Trả lời Biểu Mẫu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm py-4 px-6 flex items-center justify-between border-b">
            <div class="flex items-center space-x-4">
                <h1 class="text-xl font-medium text-gray-800">Trả lời Biểu Mẫu</h1>
            </div>
            <div class="flex items-center space-x-4">
                <button class="text-gray-600 hover:text-indigo-600" title="Tài khoản">
                    <span class="material-icons">account_circle</span>
                </button>
            </div>
        </header>

        <div class="flex flex-1 overflow-hidden">
            <!-- Main content -->
            <div class="flex-1 overflow-auto p-8">
                <div class="max-w-3xl mx-auto">
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                        <h2 class="text-2xl font-medium text-gray-800 mb-1">Biểu mẫu không tiêu đề</h2>
                        <p class="text-gray-500">Mô tả biểu mẫu...</p>
                    </div>

                    <!-- Question 2: Multiple Choice -->
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-4">
                        <label class="block text-gray-700 font-medium mb-2">2. Trắc nghiệm</label>
                        <div class="space-y-2">
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="q2" class="form-radio text-indigo-600">
                                <span>Lựa chọn 1</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="q2" class="form-radio text-indigo-600">
                                <span>Lựa chọn 2</span>
                            </label>
                        </div>
                    </div>

                    <!-- Question 3: Checkbox -->
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-4">
                        <label class="block text-gray-700 font-medium mb-2">3. Hộp kiểm</label>
                        <div class="space-y-2">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" class="form-checkbox text-indigo-600">
                                <span>Lựa chọn 1</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" class="form-checkbox text-indigo-600">
                                <span>Lựa chọn 2</span>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-right mt-6">
                        <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2 rounded">
                            Gửi
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>

</html>
