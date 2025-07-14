<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tạo Biểu Mẫu - Google Forms Clone</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        html,
        body {
            min-height: 100%;height: auto;background-repeat: no-repeat; background-position: center center;background-size: contain;  font-family: 'Times New Roman', Times, serif;background-attachment: fixed;
        }.question-box:hover .question-toolbar {
            opacity: 1;
        }.question-toolbar {
            transition: opacity 0.2s ease;opacity: 0;
        }.dragging {
            opacity: 0.5; border: 2px dashed #4f46e5;
        }.drag-over {
            background-color: #e0e7ff;
        }.qr-popup {
            position: fixed;top: 0;left: 0;width: 100%;height: 100%;display: flex;align-items: center;justify-content: center;z-index: 1000;background-color: rgba(0, 0, 0, 0.5);backdrop-filter: blur(2px);transition: opacity 0.2s ease;opacity: 0;pointer-events: none;
        }.qr-popup.active {
            opacity: 1; pointer-events: auto;
        }.qr-popup-content {
            background-color: white; padding: 24px;  border-radius: 8px;width: 90%;max-width: 400px;box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }.form-header-background {
            background-size: contain;background-position: center;background-repeat: no-repeat;height: 200px;position: relative;border-radius: 12px 12px 0 0;overflow: hidden;
        }.form-header-overlay {
            background-color: rgba(255, 255, 255, 0.2); width: 100%;height: 100%;position: absolute;top: 0;left: 0;
        }
    </style>
</head>
<script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script>

<body id="main-body" class="bg-gray-50 min-h-screen">
    <div id="theme-panel"
        class="fixed top-0 right-0 w-80 max-w-full h-full bg-white shadow-lg border-l transform translate-x-full transition-transform duration-300 z-50 overflow-y-auto">
        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-medium">Giao diện</h2>
            <button id="close-theme-btn" class="text-gray-500 hover:text-gray-700">
                <span class="material-icons">close</span>
            </button>
        </div>
        <div class="p-6 space-y-6">
            <div>
                <h3 class="font-medium mb-4">Ảnh nền</h3>
                <div class="grid grid-cols-2 gap-4">
                    @for ($i = 1; $i <= 6; $i++)
                        <img src="{{ asset('storage/backgrounds/Mau' . $i . '.jpg') }}"
                            class="bg-image-option w-full h-24 object-cover rounded cursor-pointer border-2 border-transparent hover:border-indigo-500"
                            data-src="{{ asset('storage/backgrounds/Mau' . $i . '.jpg') }}">
                    @endfor
                </div>
            </div>
            <!-- Màu -->
            <div>
                <h3 class="font-medium mb-4">Màu</h3>
                <div class="flex flex-wrap gap-4">
                    <div class="color-option w-6 h-6 bg-[#93c5fd] border rounded-full cursor-pointer ring-2 ring-indigo-500"
                        data-color="Xanh dương đậm" data-code="#93c5fd"></div>
                    <div class="color-option w-6 h-6 bg-[#fca5a5] rounded-full cursor-pointer" data-color="Đỏ"
                        data-code="#fca5a5"></div>
                    <div class="color-option w-6 h-6 bg-[#c4b5fd] rounded-full cursor-pointer" data-color="Tím"
                        data-code="#c4b5fd"></div>
                    <div class="color-option w-6 h-6 bg-[#a5f3fc] rounded-full cursor-pointer" data-color="Xanh trời"
                        data-code="#a5f3fc"></div>
                    <div class="color-option w-6 h-6 bg-[#fdba74] rounded-full cursor-pointer" data-color="Cam"
                        data-code="#fdba74"></div>
                    <div class="color-option w-6 h-6 bg-[#fde68a] rounded-full cursor-pointer" data-color="Vàng đậm"
                        data-code="#fde68a"></div>
                    <div class="color-option w-6 h-6 bg-[#99f6e4] rounded-full cursor-pointer" data-color="Xanh ngọc"
                        data-code="#99f6e4"></div>
                    <div class="color-option w-6 h-6 bg-[#86efac] rounded-full cursor-pointer" data-color="Xanh lá"
                        data-code="#86efac"></div>
                    <div class="color-option w-6 h-6 bg-[#d1d5db] rounded-full cursor-pointer" data-color="Xám nhạt"
                        data-code="#d1d5db"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex flex-col min-h-screen">
        <header class="bg-blue-600 text-white py-4 px-6 flex items-center justify-between shadow-md">
            <h1 class="text-xl font-medium text-white-800">Tạo Biểu Mẫu</h1>
            <div class="flex items-center space-x-4">
                <button onclick="window.location.href='{{ route('trangchu') }}'"
                    class="text-gray-600 hover:text-indigo-600" title="Trang chủ">
                    <span class="material-icons text-white">home</span>
                </button>
                @if ($isCreating ?? false)
                    <button id="settings-btn" class="text-gray-600 hover:text-indigo-600" title="Cài đặt">
                        <span class="material-icons text-white">settings</span>
                    </button>
                    <button id="theme-btn" class="text-gray-600 hover:text-indigo-600" title="Thay đổi giao diện">
                        <span class="material-icons text-white">palette</span>
                    </button>
                @endif
                <button id="show-qr-btn" class="text-gray-600 hover:text-indigo-600" title="Mã QR">
                    <span class="material-icons text-white">qr_code</span>
                </button>
                @if ($isCreating ?? false)
                    <button id="publish-btn"
                        class="bg-indigo-700 text-white px-4 py-2 rounded-md hover:bg-indigo-700 flex items-center">
                        <span class="material-icons mr-2">publish</span>Xuất bản
                    </button>
                @else
                @if ($bieumau->loai == 2)
<button id="tao-lai-qr-btn"
        data-id="{{ $bieumau->ma_bieu_mau }}"
        class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600 ml-2">
    🔁 Tạo lại mã QR
</button>
@endif
                    <button class="bg-indigo-700 text-white px-4 py-2 rounded-md hover:bg-indigo-700 flex items-center">
                        <a href="{{ url()->previous() }}">← Quay lại</a>
                    </button>
                @endif
            </div>
        </header>
        <div id="settings-panel"
            class="fixed top-0 right-0 w-80 max-w-full h-full bg-white shadow-lg border-l transform translate-x-full transition-transform duration-300 z-50 overflow-y-auto">
            <div class="p-4 border-b flex justify-between items-center">
                <h2 class="text-lg font-medium">Cài đặt biểu mẫu</h2>
                <button id="close-settings-btn" class="text-gray-500 hover:text-gray-700">
                    <span class="material-icons">close</span>
                </button>
            </div>
            <div class="p-4 space-y-4">
                <!-- Giới hạn thời gian -->
                <div>
                    <label class="flex items-center gap-2 font-medium mb-2">
                        <input type="checkbox" id="enable-time-limit">
                        Giới hạn thời gian (phút)
                    </label>
                    <input type="range" id="setting-time-limit" min="1" max="60" value="30"
                        class="w-full accent-indigo-600 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    <p class="text-sm text-gray-600 mt-1">Đang chọn: <span id="time-limit-value">30</span> phút</p>
                </div>
                <!-- Giới hạn số người -->
                <div>
                    <label class="flex items-center gap-2 font-medium mb-2">
                        <input type="checkbox" id="enable-participant-limit">
                        Giới hạn số người
                    </label>
                    <input type="range" id="setting-participant-limit" min="1" max="200"
                        value="100"
                        class="w-full accent-indigo-600 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    <p class="text-sm text-gray-600 mt-1">Đang chọn: <span id="participant-limit-value">100</span>
                        người</p>
                </div>
                <!-- Biểu mẫu điểm danh theo ngày -->
                <div>
                    <label class="flex items-center gap-2 font-medium mb-2">
                        <input type="checkbox" id="attendance-mode-toggle">
                        Biểu mẫu điểm danh theo ngày
                    </label>
                    <div id="excel-upload-wrapper" class="mt-2 hidden">
                        <label class="block text-sm mb-1 text-gray-600">Nhập danh sách điểm danh (Excel)</label>
                        <input type="file" id="excel-upload" accept=".xlsx,.xls,.csv"
                            class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-1 overflow-hidden">
            <!-- Main Content -->
            <div class="flex-1 overflow-auto p-8">
                <div class="max-w-3xl mx-auto">
                    <!-- Form Header -->
                    <div class="bg-white rounded-lg shadow-sm mb-6 overflow-hidden">
                        <div id="form-background" class="form-header-background relative bg-white bg-opacity-80">
                            <div class="form-header-overlay absolute inset-0 bg-white/60"></div>
                            <div class="absolute inset-0 flex flex-col justify-center items-start px-6">
                                <input type="text" id="form-title"
                                    class="form-title text-3xl sm:text-4xl font-semibold text-gray-900 bg-transparent border-b-2 border-transparent focus:border-indigo-500 focus:outline-none w-full max-w-3xl"
                                    value="{{ $bieumau->tieu_de ?? 'Biểu mẫu không tiêu đề' }}">
                                <input type="text" id="form-description"
                                    class="text-gray-600 mt-2 text-lg bg-transparent border-b-2 border-transparent focus:border-indigo-500 focus:outline-none w-full max-w-2xl"
                                    value="{{ $bieumau->mo_ta_tieu_de ?? '' }}" placeholder="Mô tả biểu mẫu">
                            </div>
                        </div>
                    </div>
                    <!-- Questions -->
                    <div id="questions-container" class="space-y-4">
                        @if (isset($cauhois))
                            @foreach ($cauhois as $ch)
                                <div class="question-box bg-white rounded-lg shadow-sm p-6 relative group">
                                    <div class="flex items-start">
                                        <div class="flex-1">
                                            <input type="text"
                                                class="question-title w-full font-medium border-b-2 border-transparent focus:border-indigo-500 focus:outline-none py-1 px-1 mb-2"
                                                value="{{ $ch->cau_hoi }}" placeholder="Câu hỏi">
                                            <div class="mt-4">
                                                <input type="text"
                                                    class="w-full border-b border-gray-300 py-2 focus:outline-none text-gray-500"
                                                    placeholder="Văn bản trả lời ngắn" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="question-toolbar flex items-center justify-between mt-4 pt-4 border-t">
                                        <button
                                            class="text-gray-500 hover:text-indigo-600 p-1 rounded-full hover:bg-indigo-50"
                                            title="Xoá câu hỏi">
                                            <span class="material-icons">delete</span>
                                        </button>
                                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                                            <label class="px-3 py-1 hover:bg-gray-100 rounded-md">
                                                Bắt buộc
                                                <input type="checkbox" class="question-required ml-2"
                                                    {{ $ch->cau_hoi_bat_buoc ? 'checked' : '' }}>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        @if ($isCreating ?? false)
                            <div class="question-box bg-white rounded-lg shadow-sm p-6 relative group">
                                <div class="flex items-start">
                                    <div class="flex-1">
                                        <input type="text"
                                            class="question-title w-full font-medium border-b-2 border-transparent focus:border-indigo-500 focus:outline-none py-1 px-1 mb-2"
                                            value="" placeholder="Câu hỏi">
                                        <div class="mt-4">
                                            <input type="text"
                                                class="w-full border-b border-gray-300 py-2 focus:outline-none text-gray-500"
                                                placeholder="Văn bản trả lời ngắn" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="question-toolbar flex items-center justify-between mt-4 pt-4 border-t">
                                    <button
                                        class="text-gray-500 hover:text-indigo-600 p-1 rounded-full hover:bg-indigo-50"
                                        title="Xoá câu hỏi">
                                        <span class="material-icons">delete</span>
                                    </button>
                                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                                        <label class="px-3 py-1 hover:bg-gray-100 rounded-md">
                                            Bắt buộc
                                            <input type="checkbox" class="question-required ml-2">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!-- Thêm câu hỏi -->
                        @if ($isCreating ?? false)
                            <div class="mt-6 flex justify-center">
                                <button id="add-question"
                                    class="flex items-center text-indigo-600 hover:text-indigo-800 font-medium py-3 px-6 border-2 border-dashed border-indigo-200 rounded-lg hover:bg-indigo-50">
                                    <span class="material-icons mr-2">add</span>Thêm câu hỏi
                                </button>
                            </div>
                        @endif
                        <!-- QR Popup -->
                        <div id="qr-popup"
                            class="fixed inset-0 bg-black bg-opacity-40 hidden flex items-center justify-center z-50">
                            <div class="bg-white p-6 rounded-lg w-80">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium">Mã QR Biểu Mẫu</h3>
                                    <button id="close-qr-btn" class="text-gray-500 hover:text-gray-700">
                                        <span class="material-icons">close</span>
                                    </button>
                                </div>
                                <div id="qr-code"
                                    class="w-64 h-64 bg-gray-100 flex items-center justify-center mb-4 mx-auto">
                                    <canvas></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      <script>
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('tao-lai-qr-btn')?.addEventListener('click', function () {
    const formId = this.dataset.id;
    if (!formId) {
        alert("❌ Không tìm thấy ID biểu mẫu.");
        return;
    }
    taoLaiQR(formId);
});
    // ===== Thiết lập ban đầu từ server (màu nền & hình nền) =====
    let selectedColor = "{{ $mau ?? '#93c5fd' }}";
    let selectedColorName = selectedColor ? 'Xanh dương đậm' : null;
    let selectedBgImage = "{{ $hinh_nen ?? '' }}";
    if (selectedBgImage) {
        document.body.style.backgroundImage = `url('${selectedBgImage}')`;
        document.body.style.backgroundSize = 'cover';
        document.body.style.backgroundPosition = 'center center';
        document.body.style.backgroundRepeat = 'no-repeat';
        document.body.style.backgroundAttachment = 'fixed';
        window.selectedBackgroundImage = selectedBgImage;
    } else if (selectedColor) {
        document.body.style.backgroundColor = selectedColor;
    }

    // ===== Xử lý chọn hình nền =====
    document.querySelectorAll('.bg-image-option').forEach(img => {
        img.addEventListener('click', function () {
            const imageName = this.dataset.src.split('/').pop();
            document.body.style.backgroundImage = `url('/storage/backgrounds/${imageName}')`;
            document.body.style.backgroundSize = 'cover';
            document.body.style.backgroundPosition = 'center center';
            document.body.style.backgroundRepeat = 'no-repeat';
            document.body.style.backgroundAttachment = 'fixed';
            window.selectedBackgroundImage = imageName;
            selectedColor = null;
            selectedColorName = null;
            document.body.style.backgroundColor = '';

            document.querySelectorAll('.bg-image-option').forEach(i => i.classList.remove('ring-2', 'ring-indigo-500'));
            this.classList.add('ring-2', 'ring-indigo-500');
            document.querySelectorAll('[data-code]').forEach(el => el.classList.remove('ring-2', 'ring-indigo-500'));
        });
    });

    // ===== Hiển thị màu đang chọn khi tải lại trang =====
    const allColorButtons = document.querySelectorAll('[data-code]');
    allColorButtons.forEach(btn => {
        if (btn.dataset.code === selectedColor) {
            btn.classList.add('ring-2', 'ring-indigo-500');
        }
    });

    // ===== Chọn màu nền mới =====
    allColorButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            selectedColor = this.dataset.code;
            selectedColorName = this.dataset.color;
            document.body.style.backgroundColor = selectedColor;
            window.selectedBackgroundImage = null;
            document.body.style.backgroundImage = '';
            document.querySelectorAll('.bg-image-option').forEach(img => img.classList.remove('ring-2', 'ring-indigo-500'));
            allColorButtons.forEach(b => b.classList.remove('ring-2', 'ring-indigo-500'));
            this.classList.add('ring-2', 'ring-indigo-500');
        });
    });

    // ===== Cài đặt giới hạn thời gian và người tham gia =====
    const timeSlider = document.getElementById('setting-time-limit');
    const timeValue = document.getElementById('time-limit-value');
    const participantSlider = document.getElementById('setting-participant-limit');
    const participantValue = document.getElementById('participant-limit-value');
    const enableTimeLimitCheckbox = document.getElementById('enable-time-limit');
    const enableParticipantLimitCheckbox = document.getElementById('enable-participant-limit');

    const updateSliderState = () => {
        timeSlider.disabled = !enableTimeLimitCheckbox.checked;
        participantSlider.disabled = !enableParticipantLimitCheckbox.checked;
        timeSlider.classList.toggle('opacity-50', timeSlider.disabled);
        timeSlider.classList.toggle('cursor-not-allowed', timeSlider.disabled);
        participantSlider.classList.toggle('opacity-50', participantSlider.disabled);
        participantSlider.classList.toggle('cursor-not-allowed', participantSlider.disabled);
    };
    updateSliderState();
    enableTimeLimitCheckbox.addEventListener('change', updateSliderState);
    enableParticipantLimitCheckbox.addEventListener('change', updateSliderState);
    timeSlider?.addEventListener('input', () => timeValue.textContent = timeSlider.value);
    participantSlider?.addEventListener('input', () => participantValue.textContent = participantSlider.value);

    // ===== Xử lý hiển thị ô nhập Excel nếu chọn "biểu mẫu điểm danh theo ngày" =====
    const attendanceToggle = document.getElementById('attendance-mode-toggle');
    const excelUploadWrapper = document.getElementById('excel-upload-wrapper');
    const toggleExcelUpload = () => {
        if (attendanceToggle.checked) {
            excelUploadWrapper.classList.remove('hidden');
        } else {
            excelUploadWrapper.classList.add('hidden');
        }
    };
    attendanceToggle?.addEventListener('change', toggleExcelUpload);
    toggleExcelUpload(); // Gọi lúc tải trang để hiển thị đúng trạng thái

    // ===== Giao diện bảng và cài đặt =====
    document.getElementById('theme-btn')?.addEventListener('click', () => {
        document.getElementById('theme-panel')?.classList.remove('translate-x-full');
    });
    document.getElementById('close-theme-btn')?.addEventListener('click', () => {
        document.getElementById('theme-panel')?.classList.add('translate-x-full');
    });
    document.getElementById('settings-btn')?.addEventListener('click', () => {
        document.getElementById('settings-panel')?.classList.remove('translate-x-full');
    });
    document.getElementById('close-settings-btn')?.addEventListener('click', () => {
        document.getElementById('settings-panel')?.classList.add('translate-x-full');
    });

    // ===== Thêm câu hỏi mới =====
    document.getElementById('add-question')?.addEventListener('click', function () {
        const html = `
            <div class="question-box bg-white rounded-lg shadow-sm p-6 relative group">
                <div class="flex items-start">
                    <div class="flex-1">
                        <input type="text" class="question-title w-full font-medium border-b-2 border-transparent focus:border-indigo-500 focus:outline-none py-1 px-1 mb-2"
                            value="" placeholder="Câu hỏi">
                        <div class="mt-4">
                            <input type="text" class="w-full border-b border-gray-300 py-2 focus:outline-none text-gray-500"
                                placeholder="Văn bản trả lời ngắn" disabled>
                        </div>
                    </div>
                </div>
                <div class="question-toolbar flex items-center justify-between mt-4 pt-4 border-t">
                    <button class="text-gray-500 hover:text-indigo-600 p-1 rounded-full hover:bg-indigo-50" title="Xoá câu hỏi">
                        <span class="material-icons">delete</span>
                    </button>
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                        <label class="px-3 py-1 hover:bg-gray-100 rounded-md">
                            Bắt buộc
                            <input type="checkbox" class="question-required ml-2">
                        </label>
                    </div>
                </div>
            </div>`;
        const addBtn = document.getElementById('add-question').closest('div');
        addBtn.insertAdjacentHTML('beforebegin', html);
    });

    // ===== Xoá câu hỏi =====
    const container = document.getElementById('questions-container');
    container.addEventListener('click', e => {
        if (e.target.closest('.material-icons')?.textContent === 'delete') {
            const questionBox = e.target.closest('.question-box');
            if (confirm('Xóa câu hỏi này?')) questionBox.remove();
        }
    });

    // ===== Xử lý xuất bản =====
    document.getElementById('publish-btn')?.addEventListener('click', async () => {
        const title = document.getElementById('form-title')?.value || '';
        const description = document.getElementById('form-description')?.value || '';
        const time_limit = enableTimeLimitCheckbox.checked ? parseInt(timeSlider?.value || 0) : null;
        const participant_limit = enableParticipantLimitCheckbox.checked ? parseInt(participantSlider?.value || 0) : null;

        const questionBoxes = document.querySelectorAll('.question-box');
        const questions = [];
        let hasEmptyTitle = false;
        questionBoxes.forEach((box) => {
            const title = box.querySelector('.question-title')?.value.trim() || '';
            const required = box.querySelector('.question-required')?.checked || false;
            if (!title) {
                hasEmptyTitle = true;
                box.querySelector('.question-title').classList.add('border-red-500');
            } else {
                box.querySelector('.question-title').classList.remove('border-red-500');
            }
            questions.push({ title, required });
        });

        if (hasEmptyTitle) {
            alert("❌ Bạn cần điền nội dung cho tất cả câu hỏi.");
            return;
        }
        if (questions.length === 0) {
            alert("❌ Bạn phải tạo ít nhất 1 câu hỏi trước khi xuất bản.");
            return;
        }

        const canvas = document.querySelector('#qr-code canvas');
        const base64Image = canvas ? canvas.toDataURL() : null;

        const attendanceCheckbox = document.getElementById('attendance-mode-toggle');
        const isAttendanceForm = attendanceCheckbox?.checked;
        const excelInput = document.getElementById('excel-upload');

        const formData = new FormData();
        formData.append('title', title);
        formData.append('description', description);
        formData.append('time_limit', time_limit);
        formData.append('participant_limit', participant_limit);
        formData.append('theme_color', window.selectedBackgroundImage ? 'Hình ảnh' : (selectedColorName || 'Xanh dương đậm'));
        formData.append('background_image', window.selectedBackgroundImage || '');
        formData.append('loai', isAttendanceForm ? 2 : 1);
        formData.append('qr_image', base64Image || '');
        formData.append('questions', JSON.stringify(questions));

        if (isAttendanceForm && excelInput?.files?.length > 0) {
            formData.append('du_lieu_vao', excelInput.files[0]);
        }

        try {
            const res = await fetch('/bieumau/xuat-ban', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            });

            const data = await res.json();
            if (data.success) {
                alert('🎉 Biểu mẫu đã được xuất bản thành công!');
                window.currentFormCode = data.ma_bieu_mau;
            } else {
                alert('❌ ' + (data.message || 'Xuất bản thất bại. Vui lòng thử lại!'));
            }
        } catch (err) {
            console.error(err);
            alert('Đã có lỗi xảy ra khi gửi dữ liệu.');
        }
    });

    // ===== Hiển thị QR code =====
    document.getElementById('show-qr-btn')?.addEventListener('click', () => {
        let formCode = window.currentFormCode || "{{ $bieumau->ma_bieu_mau ?? '' }}";
        if (!formCode) {
            alert('⚠️ Biểu mẫu chưa được xuất bản!');
            return;
        }
        const qrUrl = `${window.location.origin}/traloi-bieumau/${formCode}`;
        const canvas = document.querySelector('#qr-code canvas');
        new QRious({
            element: canvas,
            value: qrUrl,
            size: 256,
            level: 'H'
        });
        document.getElementById('qr-popup').classList.remove('hidden');
    });

    // ===== Đóng popup QR =====
    document.getElementById('close-qr-btn')?.addEventListener('click', () => {
        document.getElementById('qr-popup').classList.add('hidden');
    });

});
async function taoLaiQR(formId) {
    if (!confirm("Bạn có chắc muốn tạo lại mã QR")) return;

    try {
        const res = await fetch(location.origin + `/bieumau/${formId}/tao-lai-qr`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await res.json(); // ✅ Lấy nội dung trước

        if (res.ok && data.success) {
            const canvas = document.querySelector('#qr-code canvas');
            new QRious({
                element: canvas,
                value: data.url,
                size: 256,
                level: 'H'
            });
            document.getElementById('qr-popup').classList.remove('hidden');
            alert('✅ QR mới đã được tạo cho ngày ' + data.ngay_diem_danh);
        } else {
            // ❗ Nếu không thành công, show message từ server
            alert('⚠️ ' + (data.message || 'Có lỗi xảy ra'));
        }
    } catch (error) {
        alert('⚠️ Lỗi khi tạo lại QR');
        console.error(error);
    }
}
</script>
</body>
</html>
