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

        /* QR Popup styles */
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
<div id="theme-panel" class="fixed top-0 right-0 w-80 max-w-full h-full bg-white shadow-lg border-l transform translate-x-full transition-transform duration-300 z-50 overflow-y-auto">
  <div class="p-4 border-b flex justify-between items-center">
    <h2 class="text-lg font-medium">Giao diện</h2>
    <button id="close-theme-btn" class="text-gray-500 hover:text-gray-700">
      <span class="material-icons">close</span>
    </button>
  </div>

  <div class="p-4 space-y-4">
    <div>
      <h3 class="font-medium mb-2">Kiểu văn bản</h3>
      <div class="space-y-2">
        <div class="flex items-center justify-between">
          <label class="text-sm">Đầu trang</label>
          <div class="flex space-x-2">
            <select class="text-sm border rounded px-2 py-1">
              <option>Roboto</option>
              <option>Arial</option>
              <option>Inter</option>
            </select>
            <select class="text-sm border rounded px-2 py-1">
              <option>28</option>
              <option>26</option>
              <option>24</option>
               <option>22</option>
                <option>20</option>
                 <option>18</option>
                  <option>16</option>
                   <option>14</option>

            </select>
          </div>
        </div>

        <div class="flex items-center justify-between">
          <label class="text-sm">Câu hỏi</label>
          <div class="flex space-x-2">
            <select class="text-sm border rounded px-2 py-1">
              <option>Roboto</option>
              <option>Arial</option>
              <option>Inter</option>
            </select>
            <select class="text-sm border rounded px-2 py-1">
                <option>22</option>
                <option>20</option>
                <option>18</option>
                <option>16</option>
                <option>14</option>
                <option>12</option>
                <option>10</option>
            </select>
          </div>
        </div>

        <div class="flex items-center justify-between">
          <label class="text-sm">Văn bản</label>
          <div class="flex space-x-2">
            <select class="text-sm border rounded px-2 py-1">
              <option>Roboto</option>
              <option>Arial</option>
              <option>Inter</option>
            </select>
            <select class="text-sm border rounded px-2 py-1">
              <option>12</option>
              <option>11</option>
              <option>10</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-6">
      <h3 class="font-medium mb-2">Màu</h3>
      <div class="grid grid-cols-6 gap-2">
        <div class="w-6 h-6 bg-red-500 rounded-full cursor-pointer" data-color="#ef4444"></div>
        <div class="w-6 h-6 bg-purple-600 rounded-full ring-2 ring-indigo-500" data-color="#7c3aed"></div>
        <div class="w-6 h-6 bg-blue-700 rounded-full cursor-pointer" data-color="#1d4ed8"></div>
        <div class="w-6 h-6 bg-blue-500 rounded-full cursor-pointer" data-color="#3b82f6"></div>
        <div class="w-6 h-6 bg-sky-400 rounded-full cursor-pointer" data-color="#38bdf8"></div>
        <div class="w-6 h-6 bg-cyan-400 rounded-full cursor-pointer" data-color="#22d3ee"></div>
        <div class="w-6 h-6 bg-orange-500 rounded-full cursor-pointer" data-color="#f97316"></div>
        <div class="w-6 h-6 bg-amber-400 rounded-full cursor-pointer" data-color="#fbbf24"></div>
        <div class="w-6 h-6 bg-teal-500 rounded-full cursor-pointer" data-color="#14b8a6"></div>
        <div class="w-6 h-6 bg-green-500 rounded-full cursor-pointer" data-color="#22c55e"></div>
        <div class="w-6 h-6 bg-gray-600 rounded-full cursor-pointer" data-color="#4b5563"></div>
        <div class="w-6 h-6 bg-gray-400 rounded-full cursor-pointer" data-color="#9ca3af"></div>
      </div>
    </div>
  </div>
</div>
<body id="main-body" class="bg-gray-50 min-h-screen">
    <div class="flex flex-col min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm py-4 px-6 flex items-center justify-between border-b">
            <div class="flex items-center space-x-4">
                <h1 class="text-xl font-medium text-gray-800">Tạo Biểu Mẫu</h1>
            </div>
            <div class="flex items-center space-x-4">
                <button class="text-gray-600 hover:text-indigo-600" title="Thay đổi giao diện">
                    <span class="material-icons">palette</span>
                </button>
                <button class="text-gray-600 hover:text-indigo-600 mr-2" title="Mã QR" id="show-qr-btn">
                    <span class="material-icons">qr_code</span>
                </button>
                <button id="publish-btn" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 flex items-center">
  <span class="material-icons mr-2">publish</span>
  Xuất bản
</button>
                <button class="text-gray-600 hover:text-indigo-600" title="Tài khoản">
                    <span class="material-icons">account_circle</span>
                </button>
            </div>
        </header>

        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar -->
            <div class="w-16 bg-white shadow-md flex flex-col items-center py-4 space-y-6">
                <button
                    onclick="window.location.href='{{ route('bieumau.tao') }}'"class="text-indigo-600 bg-indigo-50 p-2 rounded-full"
                    title="Biểu mẫu">
                    <span class="material-icons">view_headline</span>
                </button>
                <button onclick="window.location.href='{{ route('bieumau.ds-cautraloi') }}'"
                    class="text-gray-700 hover:text-indigo-600 p-2 rounded-full hover:bg-indigo-50" title="Câu Trả lời">
                    <span class="material-icons">description</span>
                </button>
                <a href="{{ route('chon-bieumau', ['ma_bieu_mau' => $bieuMau->ma_bieu_mau]) }}">
    <button class="btn">Cài đặt</button>
</a>
            </div>

            <!-- Main Content -->
            <div class="flex-1 overflow-auto p-8">
                <div class="max-w-3xl mx-auto">
                    <!-- Form Header -->
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                        <div class="relative">
                            <input type="text"
                                class="w-full text-2xl font-medium border-b-2 border-transparent focus:border-indigo-500 focus:outline-none py-2 px-1"
                                value="Biểu mẫu không tiêu đề" id="form-title">
                        </div>
                        <input type="text"
                            class="w-full text-gray-500 border-b-2 border-transparent focus:border-indigo-500 focus:outline-none py-2 px-1 mt-2"
                            placeholder="Mô tả biểu mẫu" id="form-description">
                    </div>

                    <!-- Questions Container -->
                    <div id="questions-container" class="space-y-4">
                        <!-- Sample Question -->
                       <!-- Sample Question - Đã sửa thành Trắc nghiệm -->
<div class="question-box bg-white rounded-lg shadow-sm p-6 relative group" draggable="true">
    <div class="flex items-start">
        <div class="mr-4 flex flex-col items-center pt-2 drag-handle cursor-move">
            <span class="material-icons text-gray-400">drag_indicator</span>
        </div>
        <div class="flex-1">
            <div class="flex items-center justify-between">
                <input type="text"
                    class="w-full font-medium border-b-2 border-transparent focus:border-indigo-500 focus:outline-none py-1 px-1 mb-2"
                    value="Tiêu đề câu hỏi" placeholder="Câu hỏi">
                <select class="question-type text-sm border rounded-md px-3 py-1 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    <option>Trả lời ngắn</option>
                    <option selected>Trắc nghiệm</option>
                    <option>Hộp kiểm</option>
                </select>
            </div>
            <div class="question-options mt-4 space-y-2">
                <div class="flex items-center">
                    <span class="material-icons text-gray-400 mr-2">radio_button_unchecked</span>
                    <input type="text" class="flex-1 border-b border-gray-300 py-1 focus:outline-none" value="Lựa chọn 1">
                    <button class="remove-option text-gray-400 hover:text-gray-600 ml-2">
                        <span class="material-icons">close</span>
                    </button>
                </div>
                <div class="flex items-center">
                    <span class="material-icons text-gray-400 mr-2">radio_button_unchecked</span>
                    <input type="text" class="flex-1 border-b border-gray-300 py-1 focus:outline-none" value="Lựa chọn 2">
                    <button class="remove-option text-gray-400 hover:text-gray-600 ml-2">
                        <span class="material-icons">close</span>
                    </button>
                </div>
                <div class="flex items-center pl-8">
                    <button class="add-option text-indigo-600 hover:text-indigo-800 flex items-center text-sm">
                        <span class="material-icons mr-1">add</span>
                        Thêm lựa chọn
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="question-toolbar flex items-center justify-between mt-4 pt-4 border-t">
        <div class="flex space-x-2">
            <button class="text-gray-500 hover:text-indigo-600 p-1 rounded-full hover:bg-indigo-50" title="Xóa câu hỏi">
                <span class="material-icons">delete</span>
            </button>
        </div>
        <div class="flex items-center space-x-2 text-sm text-gray-500">
            <button class="px-3 py-1 hover:bg-gray-100 rounded-md">
                Bắt buộc
                <input type="checkbox" class="ml-2">
            </button>
        </div>
    </div>
</div>
                        <!-- Another Question Type -->
                        <div class="question-box bg-white rounded-lg shadow-sm p-6 relative group" draggable="true">
                            <div class="flex items-start">
                                <div class="mr-4 flex flex-col items-center pt-2 drag-handle cursor-move">
                                    <span class="material-icons text-gray-400">drag_indicator</span>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <input type="text"
                                            class="w-full font-medium border-b-2 border-transparent focus:border-indigo-500 focus:outline-none py-1 px-1 mb-2"
                                            value="Trắc nghiệm" placeholder="Câu hỏi">
                                        <select
                                            class=" question-type text-sm border rounded-md px-3 py-1 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                            <option>Trả lời ngắn</option>
                                            <option selected>Trắc nghiệm</option>
                                            <option>Hộp kiểm</option>
                                        </select>
                                    </div>
                                    <div class="question-options mt-4 space-y-2">
                                        <div class="flex items-center">
                                            <span
                                                class="material-icons text-gray-400 mr-2">radio_button_unchecked</span>
                                            <input type="text"
                                                class="flex-1 border-b border-gray-300 py-1 focus:outline-none"
                                                value="Lựa chọn 1">
                                            <button class="text-gray-400 hover:text-gray-600 ml-2">
                                                <span class="material-icons">close</span>
                                            </button>
                                        </div>
                                        <div class="flex items-center">
            <span class="material-icons text-gray-400 mr-2">radio_button_unchecked</span>
            <input type="text" class="flex-1 border-b border-gray-300 py-1 focus:outline-none" value="Lựa chọn 2">
            <button class="remove-option text-gray-400 hover:text-gray-600 ml-2">
                <span class="material-icons">close</span>
            </button>
        </div>

        <div class="flex items-center pl-8">
            <button class="add-option text-indigo-600 hover:text-indigo-800 flex items-center text-sm">
                <span class="material-icons mr-1">add</span>
                Thêm lựa chọn
            </button>
        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="question-toolbar flex items-center justify-between mt-4 pt-4 border-t">
                                <div class="flex space-x-2">
                                    <button
                                        class="text-gray-500 hover:text-indigo-600 p-1 rounded-full hover:bg-indigo-50">
                                        <span class="material-icons">delete</span>
                                    </button>
                                </div>
                                <div class="flex items-center space-x-2 text-sm text-gray-500">
                                    <button class="px-3 py-1 hover:bg-gray-100 rounded-md">
                                        Bắt buộc
                                        <input type="checkbox" checked class="ml-2">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Question Button -->
                    <div class="mt-6 flex justify-center">
                        <button id="add-question"
                            class="flex items-center text-indigo-600 hover:text-indigo-800 font-medium py-3 px-6 border-2 border-dashed border-indigo-200 rounded-lg hover:bg-indigo-50">
                            <span class="material-icons mr-2">add</span>
                            Thêm câu hỏi
                        </button>
                    </div>

                    <!-- Form Footer -->
                    <div class="mt-8 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                        <button class="flex items-center text-gray-600 hover:text-gray-800">
                            <span class="material-icons mr-1">preview</span>
                            Xem trước
                        </button>
                    </div>

                    <!-- QR Popup -->
                    <div id="qr-popup" class="qr-popup">
                        <div class="qr-popup-content">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium">Mã QR Biểu Mẫu</h3>
                                <button id="close-qr-btn" class="text-gray-500 hover:text-gray-700">
                                    <span class="material-icons">close</span>
                                </button>
                            </div>
                            <div class="flex flex-col items-center">
                                <div id="qr-code"
                                    class="w-64 h-64 bg-gray-100 flex items-center justify-center mb-4">
                                    <span class="text-gray-400">Mã QR sẽ hiển thị ở đây</span>
                                </div>
                                <button class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                                    Tải xuống
                                </button>
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
                            <div class="flex items-center justify-between">
                                <input type="text" class="w-full font-medium border-b-2 border-transparent focus:border-indigo-500 focus:outline-none py-1 px-1 mb-2"
                                    placeholder="Câu hỏi">
                                <select class=" text-sm border rounded-md px-3 py-1 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                    <option>Trả lời ngắn</option>                                   
                                    <option>Trắc nghiệm</option>
                                    <option>Hộp kiểm</option>
                                </select>
                            </div>
                            <div class="mt-4">
                                <input type="text" class="w-full border-b border-gray-300 py-2 focus:outline-none text-gray-500"
                                    placeholder="Văn bản trả lời ngắn" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="question-toolbar flex items-center justify-between mt-4 pt-4 border-t">
                        <div class="flex space-x-2">
                            <button class="text-gray-500 hover:text-indigo-600 p-1 rounded-full hover:bg-indigo-50">
                                <span class="material-icons">delete</span>
                            </button>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            <button class="px-3 py-1 hover:bg-gray-100 rounded-md">
                                Bắt buộc
                                <input type="checkbox" class="ml-2">
                            </button>
                        </div>
                    </div>
                </div>
                `;

                        const container = document.getElementById('questions-container');
                        const newQuestion = document.createElement('div');
                        newQuestion.innerHTML = questionHTML;
                        container.appendChild(newQuestion);

                        // Cuộn đến câu hỏi mới
                        newQuestion.scrollIntoView({
                            behavior: 'smooth'
                        });

                        // Thêm sự kiện thay đổi loại câu hỏi
                        addQuestionTypeChangeHandler(newQuestion);
                    });

                    // Kéo thả câu hỏi
                    const container = document.getElementById('questions-container');
                    let draggedItem = null;

                    container.addEventListener('dragstart', function(e) {
                        if (e.target.classList.contains('question-box') || e.target.closest('.question-box')) {
                            const questionBox = e.target.classList.contains('question-box') ? e.target : e.target
                                .closest('.question-box');
                            draggedItem = questionBox;
                            setTimeout(() => {
                                questionBox.classList.add('dragging');
                            }, 0);
                        }
                    });

                    container.addEventListener('dragend', function(e) {
                        if (draggedItem) {
                            draggedItem.classList.remove('dragging');
                            draggedItem = null;
                        }
                    });

                    container.addEventListener('dragover', function(e) {
                        e.preventDefault();
                        const afterElement = getDragAfterElement(container, e.clientY);
                        const currentItem = document.querySelector('.dragging');

                        if (!currentItem) return;

                        if (afterElement == null) {
                            container.appendChild(currentItem);
                        } else {
                            container.insertBefore(currentItem, afterElement);
                        }
                    });

                    function getDragAfterElement(container, y) {
                        const draggableElements = [...container.querySelectorAll('.question-box:not(.dragging)')];

                        return draggableElements.reduce((closest, child) => {
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

                    // Xử lý công tắc bật/tắt
                    document.querySelectorAll('.toggle-checkbox').forEach(checkbox => {
                        checkbox.addEventListener('change', function() {
                            const label = this.nextElementSibling;
                            if (this.checked) {
                                label.classList.remove('bg-gray-300');
                                label.classList.add('bg-indigo-500');
                            } else {
                                label.classList.remove('bg-indigo-500');
                                label.classList.add('bg-gray-300');
                            }
                        });
                    });

                    // Xử lý thay đổi loại câu hỏi
                    function addQuestionTypeChangeHandler(questionBox) {
                        const select = questionBox.querySelector('select');
                        if (select) {
                            select.addEventListener('change', function() {
                                changeQuestionType(questionBox, this.value);
                            });
                        }
                    }

                    // Thay đổi loại câu hỏi
                   function changeQuestionType(questionBox, selectedType) {
    const inputArea = questionBox.querySelector('.flex-1 > div:last-child');
    let inputHTML = '';

    if (selectedType === 'Trả lời ngắn') {
        inputHTML = `
            <input type="text" class="w-full border-b border-gray-300 py-2 focus:outline-none text-gray-500" placeholder="Văn bản trả lời ngắn" disabled>
        `;
    } else {
        const isMultiple = selectedType === 'Hộp kiểm';
        const icon = isMultiple ? 'check_box_outline_blank' : 'radio_button_unchecked';

        inputHTML = `
            <div class="question-options mt-4 space-y-2">
                <div class="flex items-center">
                    <span class="material-icons text-gray-400 mr-2">${icon}</span>
                    <input type="text" class="flex-1 border-b border-gray-300 py-1 focus:outline-none" value="Lựa chọn 1">
                    <button class="remove-option text-gray-400 hover:text-gray-600 ml-2">
                        <span class="material-icons">close</span>
                    </button>
                </div>
                <div class="flex items-center">
                    <span class="material-icons text-gray-400 mr-2">${icon}</span>
                    <input type="text" class="flex-1 border-b border-gray-300 py-1 focus:outline-none" value="Lựa chọn 2">
                    <button class="remove-option text-gray-400 hover:text-gray-600 ml-2">
                        <span class="material-icons">close</span>
                    </button>
                </div>
                <div class="flex items-center pl-8">
                    <button class="add-option text-indigo-600 hover:text-indigo-800 flex items-center text-sm">
                        <span class="material-icons mr-1">add</span>
                        Thêm lựa chọn
                    </button>
                </div>
            </div>
        `;
    }

    inputArea.innerHTML = inputHTML;
}


                    // Xử lý xóa câu hỏi
                    container.addEventListener('click', function(e) {
                        if (e.target.classList.contains('material-icons') && e.target.textContent === 'delete') {
                            const questionBox = e.target.closest('.question-box');
                            if (confirm('Xóa câu hỏi này?')) {
                                questionBox.remove();
                            }
                        }
                    });

                    // Thêm trình xử lý thay đổi loại câu hỏi cho các câu hỏi hiện có
                    document.querySelectorAll('.question-box').forEach(questionBox => {
                        addQuestionTypeChangeHandler(questionBox);
                    });
                });

                // QR Code functionality
                const showQrBtn = document.getElementById('show-qr-btn');
                const closeQrBtn = document.getElementById('close-qr-btn');
                const qrPopup = document.getElementById('qr-popup');

                showQrBtn.addEventListener('click', function() {
                    qrPopup.classList.add('active');

                    // In a real app, you would generate QR code here using a library like QRious
                    // For example:
                    // const qr = new QRious({
                    //     element: document.getElementById('qr-code'),
                    //     value: window.location.href,
                    //     size: 200
                    // });
                });

                closeQrBtn.addEventListener('click', function() {
                    qrPopup.classList.remove('active');
                });

                // Close popup when clicking outside
                qrPopup.addEventListener('click', function(e) {
                    if (e.target === qrPopup) {
                        qrPopup.classList.remove('active');
                    }
                });

                // thêm
                // Xử lý sự kiện thêm / xóa lựa chọn
document.addEventListener('click', function (e) {
    const addBtn = e.target.closest('.add-option');
    const removeBtn = e.target.closest('.remove-option');

    if (addBtn) {
        const box = addBtn.closest('.question-box');

        // Lấy đúng dropdown trong form này
        const selectedType = box.querySelector('.question-type')?.value || 'Trắc nghiệm';

        // Icon phù hợp loại câu hỏi trong box này
        const icon = selectedType === 'Hộp kiểm' ? 'check_box_outline_blank' : 'radio_button_unchecked';

        const container = addBtn.closest('.question-options');
        if (container) {
            const currentOptions = Array.from(container.querySelectorAll('.flex.items-center'))
                .filter(option => !option.contains(addBtn));

            const nextIndex = currentOptions.length + 1;

            const newOption = document.createElement('div');
            newOption.className = 'flex items-center';
            newOption.innerHTML = `
                <span class="material-icons text-gray-400 mr-2">${icon}</span>
                <input type="text" class="flex-1 border-b border-gray-300 py-1 focus:outline-none" value="Lựa chọn ${nextIndex}">
                <button class="remove-option text-gray-400 hover:text-gray-600 ml-2">
                    <span class="material-icons">close</span>
                </button>
            `;
            container.insertBefore(newOption, addBtn.parentElement);
        }
    }

    if (removeBtn) {
        const option = removeBtn.closest('.flex.items-center');
        const container = option?.closest('.question-options');
        if (option) {
            option.remove();
            // Cập nhật lại thứ tự
            const options = container.querySelectorAll('.flex.items-center');
            let index = 1;
            options.forEach(opt => {
                const input = opt.querySelector('input[type="text"]');
                if (input) {
                    input.value = `Lựa chọn ${index++}`;
                }
            });
        }
    }
});
// Mở / Đóng panel Giao diện
document.querySelector('button[title="Thay đổi giao diện"]').addEventListener('click', () => {
  document.getElementById('theme-panel').classList.remove('translate-x-full');
});

document.getElementById('close-theme-btn').addEventListener('click', () => {
  document.getElementById('theme-panel').classList.add('translate-x-full');
});

// Đổi màu nền
document.querySelectorAll('#theme-panel [data-color]').forEach(colorBtn => {
  colorBtn.addEventListener('click', () => {
    const color = colorBtn.getAttribute('data-color');
    document.getElementById('main-body').style.backgroundColor = color;

    // Highlight màu
    document.querySelectorAll('#theme-panel [data-color]').forEach(btn => btn.classList.remove('ring-2', 'ring-indigo-500'));
    colorBtn.classList.add('ring-2', 'ring-indigo-500');
  });
});

// Hàm đổi font và size
function updateFont(selectors, value) {
  document.querySelectorAll(selectors).forEach(el => {
    el.style.fontFamily = value;
  });
}
function updateFontSize(selectors, size) {
  document.querySelectorAll(selectors).forEach(el => {
    el.style.fontSize = size + 'px';
  });
}

// Xử lý sự kiện thay đổi chọn
document.querySelectorAll('#theme-panel [data-color]').forEach(colorBtn => {
  colorBtn.addEventListener('click', () => {
    const color = colorBtn.getAttribute('data-color');
    const body = document.getElementById('main-body');
    body.classList.remove('bg-gray-50'); // Xóa nền mặc định
    body.style.backgroundColor = color;

    // Highlight ô màu đã chọn
    document.querySelectorAll('#theme-panel [data-color]').forEach(btn => {
      btn.classList.remove('ring-2', 'ring-indigo-500');
    });
    colorBtn.classList.add('ring-2', 'ring-indigo-500');
  });
});
// Xử lý nút xuất bản
document.getElementById('publish-btn').addEventListener('click', async () => {
  const title = document.getElementById('form-title')?.value || 'Biểu mẫu không tiêu đề';
  const description = document.getElementById('form-description')?.value || '';

  const questions = Array.from(document.querySelectorAll('.question-box')).map(box => {
    const title = box.querySelector('input[type="text"]')?.value || '';
    const type = box.querySelector('select')?.value || 'Trả lời ngắn';
    const required = box.querySelector('input[type="checkbox"]')?.checked || false;

    let options = [];
    const optionInputs = box.querySelectorAll('.question-options input[type="text"]');
    optionInputs.forEach(input => options.push(input.value));

    return {
      title,
      type,
      options: options.length ? options : null,
      required,
    };
  });

  try {
    const res = await fetch('/api/forms', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({
        title,
        description,
        questions
      })
    });

    const data = await res.json();
    if (data.success) {
      alert('🎉 Biểu mẫu đã được xuất bản thành công!');
      // window.location.href = '/bieumau/xem-truoc'; // Nếu muốn chuyển hướng
    } else {
      alert('❌ Xuất bản thất bại. Vui lòng thử lại!');
    }
  } catch (err) {
    console.error(err);
    alert('Đã có lỗi xảy ra khi gửi dữ liệu.');
  }
});

 

            </script>
            <!-- Uncomment to use QR code library in production -->
            <!-- <script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script> -->
</body>

</html>
