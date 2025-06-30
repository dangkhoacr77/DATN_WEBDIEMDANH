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
            <!-- Kiểu văn bản -->
            <!-- Màu -->
            <div>
                <h3 class="font-medium mb-4">Màu</h3>
                <div class="grid grid-cols-6 gap-3">
                    <div class="w-6 h-6 bg-white border rounded-full cursor-pointer ring-2 ring-indigo-500"
                        data-color="Trắng" data-code="#ffffff"></div>
                    <div class="w-6 h-6 bg-red-500 rounded-full cursor-pointer" data-color="Đỏ" data-code="#ef4444">
                    </div>
                    <div class="w-6 h-6 bg-purple-600 rounded-full cursor-pointer" data-color="Tím" data-code="#7c3aed">
                    </div>
                    <div class="w-6 h-6 bg-blue-700 rounded-full cursor-pointer" data-color="Xanh dương đậm"
                        data-code="#1d4ed8"></div>
                    <div class="w-6 h-6 bg-blue-500 rounded-full cursor-pointer" data-color="Xanh dương"
                        data-code="#3b82f6"></div>
                    <div class="w-6 h-6 bg-sky-400 rounded-full cursor-pointer" data-color="Xanh trời"
                        data-code="#38bdf8"></div>
                    <div class="w-6 h-6 bg-cyan-400 rounded-full cursor-pointer" data-color="Xanh cyan"
                        data-code="#22d3ee"></div>
                    <div class="w-6 h-6 bg-orange-500 rounded-full cursor-pointer" data-color="Cam" data-code="#f97316">
                    </div>
                    <div class="w-6 h-6 bg-amber-400 rounded-full cursor-pointer" data-color="Vàng đậm"
                        data-code="#fbbf24"></div>
                    <div class="w-6 h-6 bg-teal-500 rounded-full cursor-pointer" data-color="Xanh ngọc"
                        data-code="#14b8a6"></div>
                    <div class="w-6 h-6 bg-green-500 rounded-full cursor-pointer" data-color="Xanh lá"
                        data-code="#22c55e"></div>
                    <div class="w-6 h-6 bg-gray-600 rounded-full cursor-pointer" data-color="Xám đậm"
                        data-code="#4b5563"></div>
                    <div class="w-6 h-6 bg-gray-400 rounded-full cursor-pointer" data-color="Xám nhạt"
                        data-code="#9ca3af"></div>
                </div>

            </div>
        </div>
    </div>
    </div>
    <!-- Nút Reset -->


    <body id="main-body" class="bg-gray-50 min-h-screen">
        <div class="flex flex-col min-h-screen">
            <!-- Header -->
            <header class="bg-white shadow-sm py-4 px-6 flex items-center justify-between border-b">
                <h1 class="text-xl font-medium text-gray-800">Tạo Biểu Mẫu</h1>
                <div class="flex items-center space-x-4">
                    <button class="text-gray-600 hover:text-indigo-600" title="Cài đặt">
                        <span class="material-icons">settings</span>
                    </button>
                    <button class="text-gray-600 hover:text-indigo-600" title="Thay đổi giao diện">
                        <span class="material-icons">palette</span>
                    </button>
                    <button class="text-gray-600 hover:text-indigo-600" title="Mã QR" id="show-qr-btn">
                        <span class="material-icons">qr_code</span>
                    </button>
                    <button id="publish-btn"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 flex items-center">
                        <span class="material-icons mr-2">publish</span>Xuất bản
                    </button>
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
                <!-- Sidebar -->
                <div class="w-16 bg-white shadow-md flex flex-col items-center py-4 space-y-6">
                    <button onclick="window.location.href='{{ route('bieumau.tao') }}'"
                        class="text-indigo-600 bg-indigo-50 p-2 rounded-full" title="Biểu mẫu">
                        <span class="material-icons">view_headline</span>
                    </button>
                    <button onclick="window.location.href='{{ route('bieumau.ds-cautraloi') }}'"
                        class="text-gray-700 hover:text-indigo-600 p-2 rounded-full hover:bg-indigo-50"
                        title="Câu Trả lời">
                        <span class="material-icons">description</span>
                    </button>
                </div>

                <!-- Main Content -->
                <div class="flex-1 overflow-auto p-8">
                    <div class="max-w-3xl mx-auto">
                        <!-- Form Header -->
                        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                            <input type="text" id="form-title"
                                class="form-title w-full text-2xl font-medium border-b-2 border-transparent focus:border-indigo-500 focus:outline-none py-2 px-1"
                                value="Biểu mẫu không tiêu đề">
                            <input type="text" id="form-description"
                                class="w-full text-gray-500 border-b-2 border-transparent focus:border-indigo-500 focus:outline-none py-2 px-1 mt-2"
                                placeholder="Mô tả biểu mẫu">
                        </div>

                        <!-- Questions -->
                        <div id="questions-container" class="space-y-4">
                            <!-- Câu hỏi mẫu -->
                            <div class="question-box bg-white rounded-lg shadow-sm p-6 relative group"
                                draggable="true">
                                <div class="flex items-start">
                                    <div class="mr-4 flex flex-col items-center pt-2 drag-handle cursor-move">
                                        <span class="material-icons text-gray-400">drag_indicator</span>
                                    </div>
                                    <div class="flex-1">
                                        <input type="text"
                                            class="question-title w-full font-medium border-b-2 border-transparent focus:border-indigo-500 focus:outline-none py-1 px-1 mb-2"
                                            value="Tiêu đề câu hỏi" placeholder="Câu hỏi">
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
                                <button
                                    class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 w-full">Tải
                                    xuống</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Thêm câu hỏi mới
                document.getElementById('add-question').addEventListener('click', function() {
                    const questionHTML = `
        <div class="question-box bg-white rounded-lg shadow-sm p-6 relative group" draggable="true">
            <div class="flex items-start">
                <div class="mr-4 flex flex-col items-center pt-2 drag-handle cursor-move">
                    <span class="material-icons text-gray-400">drag_indicator</span>
                </div>
                <div class="flex-1">
                    <input type="text" class="question-title w-full font-medium border-b-2 border-transparent focus:border-indigo-500 focus:outline-none py-1 px-1 mb-2"
                           value="Tiêu đề câu hỏi" placeholder="Câu hỏi">
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
                        <input type="checkbox" class="ml-2">
                    </label>
                </div>
            </div>
        </div>`;
                     document.getElementById('questions-container').insertAdjacentHTML('beforeend', questionHTML);
                });

                // Kéo thả sắp xếp câu hỏi
                const container = document.getElementById('questions-container');
                let draggedItem = null;

                container.addEventListener('dragstart', function(e) {
                    const box = e.target.closest('.question-box');
                    if (box) {
                        draggedItem = box;
                        box.classList.add('dragging');
                    }
                });

                container.addEventListener('dragend', function() {
                    if (draggedItem) {
                        draggedItem.classList.remove('dragging');
                        draggedItem = null;
                    }
                });

                container.addEventListener('dragover', function(e) {
                    e.preventDefault();
                    const afterElement = getDragAfterElement(container, e.clientY);
                    const dragging = document.querySelector('.dragging');
                    if (!dragging) return;
                    if (afterElement == null) {
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
                                offset: offset,
                                element: child
                            };
                        } else {
                            return closest;
                        }
                    }, {
                        offset: Number.NEGATIVE_INFINITY
                    }).element;
                }

                // Xóa câu hỏi
                container.addEventListener('click', function(e) {
                    if (e.target.closest('.material-icons')?.textContent === 'delete') {
                        const questionBox = e.target.closest('.question-box');
                        if (confirm('Xóa câu hỏi này?')) {
                            questionBox.remove();
                        }
                    }
                });

                // Mở/đóng panel giao diện
                document.querySelector('button[title="Thay đổi giao diện"]').addEventListener('click', () => {
                    document.getElementById('theme-panel').classList.remove('translate-x-full');
                });

                document.getElementById('close-theme-btn').addEventListener('click', () => {
                    document.getElementById('theme-panel').classList.add('translate-x-full');
                });

                // Đổi màu giao diện
                let selectedColor = 'Trắng'; // mặc định

                document.querySelectorAll('#theme-panel [data-color]').forEach(colorBtn => {
                    colorBtn.addEventListener('click', () => {
                        selectedColor = colorBtn.getAttribute('data-color');
                        const colorCode = colorBtn.getAttribute('data-code');
                        document.getElementById('main-body').style.backgroundColor = colorCode;

                        document.querySelectorAll('#theme-panel [data-color]').forEach(btn => {
                            btn.classList.remove('ring-2', 'ring-indigo-500');
                        });
                        colorBtn.classList.add('ring-2', 'ring-indigo-500');
                    });
                });


                // Panel cài đặt
                const settingsBtn = document.querySelector('button[title="Cài đặt"]');
                const settingsPanel = document.getElementById('settings-panel');
                const closeSettingsBtn = document.getElementById('close-settings-btn');
                settingsBtn.addEventListener('click', () => settingsPanel.classList.remove('translate-x-full'));
                closeSettingsBtn.addEventListener('click', () => settingsPanel.classList.add('translate-x-full'));

                // Thanh trượt thời gian và số người
                const timeSlider = document.getElementById('setting-time-limit');
                const timeValue = document.getElementById('time-limit-value');
                const participantSlider = document.getElementById('setting-participant-limit');
                const participantValue = document.getElementById('participant-limit-value');

                timeSlider?.addEventListener('input', () => timeValue.textContent = timeSlider.value);
                participantSlider?.addEventListener('input', () => participantValue.textContent = participantSlider
                    .value);

                // Nút xuất bản
                  document.getElementById('publish-btn').addEventListener('click', async () => {
      const title = document.getElementById('form-title')?.value || '';
      const description = document.getElementById('form-description')?.value || '';
      const time_limit = parseInt(document.getElementById('setting-time-limit')?.value || 0);
      const participant_limit = parseInt(document.getElementById('setting-participant-limit')?.value || 0);

      const questions = Array.from(document.querySelectorAll('.question-box')).map(box => {
        return {
          title: box.querySelector('.question-title')?.value || '',
          type: 'Trả lời ngắn',
          required: box.querySelector('.question-required')?.checked || false,
          options: null
        };
      });
const canvas = document.querySelector('#qr-code canvas');
  const base64Image = canvas ? canvas.toDataURL() : null;
  console.log('QR Base64:', base64Image); // Kiểm tra
      try {
        const res = await fetch('/bieumau/xuat-ban', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({
    title,
    description,
    time_limit,
    participant_limit,
    theme_color: selectedColor,
    questions,                // ✅ thêm dấu phẩy ở đây
    qr_image: base64Image     // ✅ gửi hình QR base64 lên server
})
        });

        const data = await res.json();
        if (data.success) {
          alert('🎉 Biểu mẫu đã được xuất bản thành công!');
          currentFormCode = data.ma_bieu_mau;
        } else {
          alert('❌ Xuất bản thất bại. Vui lòng thử lại!');
        }
      } catch (err) {
        console.error(err);
        alert('Đã có lỗi xảy ra khi gửi dữ liệu.');
      }
    });
            // Hiện QR
    document.getElementById('show-qr-btn').addEventListener('click', () => {
      if (!currentFormCode) {
        alert('⚠️ Bạn cần xuất bản trước khi tạo mã QR!');
        return;
      }
      const qrUrl = `${window.location.origin}/traloi-bieumau/${currentFormCode}`;
      const canvas = document.querySelector('#qr-code canvas');
      const qr = new QRious({
        element: canvas,
        value: qrUrl,
        size: 256,
        level: 'H'
      });


      document.getElementById('qr-popup').classList.remove('hidden');
    });

    document.getElementById('close-qr-btn').addEventListener('click', () => {
      document.getElementById('qr-popup').classList.add('hidden');
    });

    document.getElementById('download-qr').addEventListener('click', () => {
      const canvas = document.querySelector('#qr-code canvas');
      const base64Image = canvas.toDataURL();
      if (canvas) {
        const link = document.createElement('a');
        link.download = `${currentFormCode}.png`;
        link.href = canvas.toDataURL();
        link.click();
      }
    });
        });
        </script>
        <!-- Uncomment to use QR code library in production -->
        <!-- <script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script> -->
    </body>

</html>
