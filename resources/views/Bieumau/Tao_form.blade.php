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
        .question-box:hover .question-toolbar {
            opacity: 1;
        }

        .question-toolbar {
            transition: opacity 0.2s ease;
            opacity: 0;
        }

        .dragging {
            opacity: 0.5;
            border: 2px dashed #4f46e5;
        }

        .drag-over {
            background-color: #e0e7ff;
        }

        .qr-popup {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(2px);
            transition: opacity 0.2s ease;
            opacity: 0;
            pointer-events: none;
        }

        .qr-popup.active {
            opacity: 1;
            pointer-events: auto;
        }

        .qr-popup-content {
            background-color: white;
            padding: 24px;
            border-radius: 8px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-header-background {
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 200px;
            position: relative;
            border-radius: 12px 12px 0 0;
            overflow: hidden;
        }

        .form-header-overlay {
            background-color: rgba(255, 255, 255, 0.6);
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
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
                    @for ($i = 1; $i <= 5; $i++)
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
                    <div class="color-option w-6 h-6 bg-[#ffffff] border rounded-full cursor-pointer ring-2 ring-indigo-500"
                        data-color="Trắng" data-code="#ffffff"></div>
                    <div class="color-option w-6 h-6 bg-[#fca5a5] rounded-full cursor-pointer" data-color="Đỏ"
                        data-code="#fca5a5"></div>
                    <div class="color-option w-6 h-6 bg-[#c4b5fd] rounded-full cursor-pointer" data-color="Tím"
                        data-code="#c4b5fd"></div>
                    <div class="color-option w-6 h-6 bg-[#93c5fd] rounded-full cursor-pointer"
                        data-color="Xanh dương đậm" data-code="#93c5fd"></div>
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
    <!-- Nút Reset -->


    <body id="main-body" class="bg-gray-50 min-h-screen">
        <div class="flex flex-col min-h-screen">
            <header class="bg-white shadow-sm py-4 px-6 flex items-center justify-between border-b">
                <h1 class="text-xl font-medium text-gray-800">Tạo Biểu Mẫu</h1>
                <div class="flex items-center space-x-4">
                    <button onclick="window.location.href='{{ route('trangchu') }}'"
                        class="text-gray-600 hover:text-indigo-600" title="Trang chủ">
                        <span class="material-icons">home</span>
                    </button>
                    <button id="settings-btn" class="text-gray-600 hover:text-indigo-600" title="Cài đặt">
                        <span class="material-icons">settings</span>
                    </button>
                    <button id="theme-btn" class="text-gray-600 hover:text-indigo-600" title="Thay đổi giao diện">
                        <span class="material-icons">palette</span>
                    </button>
                    <button id="show-qr-btn" class="text-gray-600 hover:text-indigo-600" title="Mã QR">
                        <span class="material-icons">qr_code</span>
                    </button>
                    @php
                        $isPreviewMode = Str::contains(request()->url(), '/bieumau/tao');
                    @endphp

                    @if ($isPreviewMode)
                        <button id="publish-btn"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 flex items-center">
                            <span class="material-icons mr-2">publish</span>Xuất bản
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

                <!-- ✅ Thêm thẻ DIV bị thiếu ở đây -->
                <div class="p-4 space-y-4">
                    <!-- Giới hạn thời gian -->
                    <div>
                        <label class="block font-medium mb-2">Giới hạn thời gian (phút)</label>
                        <input type="range" id="setting-time-limit" min="0" max="60" value="30"
                            class="w-full accent-indigo-600">
                        <p class="text-sm text-gray-600 mt-1">Đang chọn: <span id="time-limit-value">30</span> phút</p>
                    </div>

                    <!-- Giới hạn số người -->
                    <div>
                        <label class="block font-medium mb-2">Giới hạn số người</label>
                        <input type="range" id="setting-participant-limit" min="0" max="200"
                            value="100" class="w-full accent-indigo-600">
                        <p class="text-sm text-gray-600 mt-1">Đang chọn: <span id="participant-limit-value">100</span>
                            người</p>
                    </div>
                </div> <!-- ✅ Thẻ đóng đúng -->

            </div>


            <div class="flex flex-1 overflow-hidden">
                <!-- Main Content -->
                <div class="flex-1 overflow-auto p-8">
                    <div class="max-w-3xl mx-auto">
                        <!-- Form Header -->
                        <div class="bg-white rounded-lg shadow-sm mb-6 overflow-hidden">
                            <div id="form-background" class="form-header-background relative"
                                style="background-image: url('{{ $bieumau->hinh_anh ?? '' }}');">
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
                                    <div class="question-box bg-white rounded-lg shadow-sm p-6 relative group"
                                        draggable="true">
                                        <div class="flex items-start">
                                            <div class="mr-4 flex flex-col items-center pt-2 drag-handle cursor-move">
                                                <span class="material-icons text-gray-400">drag_indicator</span>
                                            </div>
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
                                        <div
                                            class="question-toolbar flex items-center justify-between mt-4 pt-4 border-t">
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
                        </div>

                        <!-- Thêm câu hỏi -->
                        <div class="mt-6 flex justify-center">
                            <button id="add-question"
                                class="flex items-center text-indigo-600 hover:text-indigo-800 font-medium py-3 px-6 border-2 border-dashed border-indigo-200 rounded-lg hover:bg-indigo-50">
                                <span class="material-icons mr-2">add</span>Thêm câu hỏi
                            </button>
                        </div>

                        <!-- QR Popup -->
                        <!-- ✅ Đặt ở cuối body, ngay trước </body> -->
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
                let selectedColor = "{{ $bieumau->mau ?? '#ffffff' }}"; // Mặc định hoặc từ DB
                let selectedBgImage = "{{ $bieumau->hinh_nen ?? '' }}";

                if (selectedBgImage) {
                    document.body.style.backgroundImage = `url('${selectedBgImage}')`;
                    document.body.style.backgroundSize = 'cover';
                }
                document.querySelectorAll('.bg-image-option').forEach(img => {
                    img.addEventListener('click', function() {
                        const imageName = this.dataset.src.split('/').pop(); // ví dụ: bg2.jpg

                        document.body.style.backgroundImage =
                        `url('/storage/backgrounds/${imageName}')`;
                        document.body.style.backgroundSize = 'cover';

                        window.selectedBackgroundImage = imageName; // ✅ Gửi tên file

                        document.querySelectorAll('.bg-image-option').forEach(i => i.classList.remove(
                            'ring-2', 'ring-indigo-500'));
                        this.classList.add('ring-2', 'ring-indigo-500');
                    });
                });
                const uploadInput = document.getElementById('custom-bg-upload');
                uploadInput?.addEventListener('change', async function(e) {
                    const file = e.target.files[0];
                    if (!file) return;

                    const formData = new FormData();
                    formData.append('background', file);

                    try {
                        const res = await fetch('/api/upload-background', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content
                            },
                            body: formData
                        });

                        const data = await res.json();
                        if (data.success) {
                            const imageUrl = data.url;
                            document.body.style.backgroundImage = `url('${imageUrl}')`;
                            document.body.style.backgroundSize = 'cover';
                            document.body.style.backgroundRepeat = 'no-repeat';

                            window.selectedBackgroundImage = imageUrl;
                            alert('✅ Ảnh nền đã được tải lên!');
                        } else {
                            alert('❌ Upload ảnh thất bại!');
                        }
                    } catch (err) {
                        console.error(err);
                        alert('⚠️ Có lỗi khi upload ảnh.');
                    }
                });

                // Đặt màu nền ban đầu
                document.body.style.backgroundColor = selectedColor;

                // Đặt hiệu ứng viền cho màu đã chọn
                const allColorButtons = document.querySelectorAll('[data-code]');
                allColorButtons.forEach(btn => {
                    if (btn.dataset.code === selectedColor) {
                        btn.classList.add('ring-2', 'ring-indigo-500');
                    }
                });

                // Xử lý chọn màu
                allColorButtons.forEach(btn => {
                    btn.addEventListener('click', function() {
                        selectedColor = this.dataset.code;
                        document.body.style.backgroundColor = selectedColor;

                        // Reset viền
                        allColorButtons.forEach(b => b.classList.remove('ring-2', 'ring-indigo-500'));
                        // Viền cho nút hiện tại
                        this.classList.add('ring-2', 'ring-indigo-500');
                    });
                });

                const currentFormCode = "{{ $bieumau->ma_bieu_mau ?? '' }}";

                // Thêm câu hỏi
                document.getElementById('add-question')?.addEventListener('click', function() {
                    const html = `
            <div class="question-box bg-white rounded-lg shadow-sm p-6 relative group" draggable="true">
                <div class="flex items-start">
                    <div class="mr-4 flex flex-col items-center pt-2 drag-handle cursor-move">
                        <span class="material-icons text-gray-400">drag_indicator</span>
                    </div>
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
                    document.getElementById('questions-container').insertAdjacentHTML('beforeend', html);
                });

                // Kéo thả
                const container = document.getElementById('questions-container');
                let draggedItem = null;
                container.addEventListener('dragstart', e => {
                    const box = e.target.closest('.question-box');
                    if (box) {
                        draggedItem = box;
                        box.classList.add('dragging');
                    }
                });
                container.addEventListener('dragend', () => {
                    if (draggedItem) {
                        draggedItem.classList.remove('dragging');
                        draggedItem = null;
                    }
                });
                container.addEventListener('dragover', e => {
                    e.preventDefault();
                    const afterElement = getDragAfterElement(container, e.clientY);
                    const dragging = document.querySelector('.dragging');
                    if (!dragging) return;
                    if (!afterElement) {
                        container.appendChild(dragging);
                    } else {
                        container.insertBefore(dragging, afterElement);
                    }
                });

                function getDragAfterElement(container, y) {
                    const elements = [...container.querySelectorAll('.question-box:not(.dragging)')];
                    return elements.reduce((closest, child) => {
                        const box = child.getBoundingClientRect();
                        const offset = y - box.top - box.height / 2;
                        if (offset < 0 && offset > closest.offset) {
                            return {
                                offset,
                                element: child
                            };
                        } else return closest;
                    }, {
                        offset: Number.NEGATIVE_INFINITY
                    }).element;
                }

                // Xoá câu hỏi
                container.addEventListener('click', e => {
                    if (e.target.closest('.material-icons')?.textContent === 'delete') {
                        const questionBox = e.target.closest('.question-box');
                        if (confirm('Xóa câu hỏi này?')) questionBox.remove();
                    }
                });

                // Mở/đóng panel giao diện
                document.getElementById('theme-btn')?.addEventListener('click', () => {
                    document.getElementById('theme-panel')?.classList.remove('translate-x-full');
                });
                document.getElementById('close-theme-btn')?.addEventListener('click', () => {
                    document.getElementById('theme-panel')?.classList.add('translate-x-full');
                });

                // Mở/đóng panel cài đặt
                document.getElementById('settings-btn')?.addEventListener('click', () => {
                    document.getElementById('settings-panel')?.classList.remove('translate-x-full');
                });
                document.getElementById('close-settings-btn')?.addEventListener('click', () => {
                    document.getElementById('settings-panel')?.classList.add('translate-x-full');
                });

                // Thanh trượt thời gian và số người
                const timeSlider = document.getElementById('setting-time-limit');
                const timeValue = document.getElementById('time-limit-value');
                const participantSlider = document.getElementById('setting-participant-limit');
                const participantValue = document.getElementById('participant-limit-value');
                timeSlider?.addEventListener('input', () => timeValue.textContent = timeSlider.value);
                participantSlider?.addEventListener('input', () => participantValue.textContent = participantSlider
                    .value);

                // Nút xuất bản
                document.getElementById('publish-btn')?.addEventListener('click', async () => {
                    const title = document.getElementById('form-title')?.value || '';
                    const description = document.getElementById('form-description')?.value || '';
                    const time_limit = parseInt(timeSlider?.value || 0);
                    const participant_limit = parseInt(participantSlider?.value || 0);

                    const questions = Array.from(document.querySelectorAll('.question-box')).map(box => ({
                        title: box.querySelector('.question-title')?.value || '',
                        required: box.querySelector('.question-required')?.checked || false,
                    }));

                    const canvas = document.querySelector('#qr-code canvas');
                    const base64Image = canvas ? canvas.toDataURL() : null;

                    try {
                        console.log("Ảnh nền đang gửi:", window.selectedBackgroundImage);
                        const res = await fetch('/bieumau/xuat-ban', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .content
                            },
                            body: JSON.stringify({
                                title,
                                description,
                                time_limit,
                                participant_limit,
                                theme_color: selectedColor, // ✅ Đưa selectedColor vào đây
                                background_image: window.selectedBackgroundImage || null,
                                questions,
                                qr_image: base64Image
                            })
                        });

                        const data = await res.json();
                        if (data.success) {
                            alert('🎉 Biểu mẫu đã được xuất bản thành công!');
                            window.currentFormCode = data.ma_bieu_mau;
                        } else {
                            alert('❌ Xuất bản thất bại. Vui lòng thử lại!');
                        }
                    } catch (err) {
                        console.error(err);
                        alert('Đã có lỗi xảy ra khi gửi dữ liệu.');
                    }
                });

                // Hiện QR
                document.getElementById('show-qr-btn')?.addEventListener('click', () => {
                    let formCode = window.currentFormCode || "{{ $bieumau->ma_bieu_mau ?? '' }}";
                    if (!formCode) {
                        alert('⚠️ Không tìm thấy mã biểu mẫu!');
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

                document.getElementById('close-qr-btn')?.addEventListener('click', () => {
                    document.getElementById('qr-popup').classList.add('hidden');
                });
            });
        </script>
        <!-- Uncomment to use QR code library in production -->
        <!-- <script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script> -->
    </body>

</html>
