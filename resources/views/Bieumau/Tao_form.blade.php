<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            background-color: rgba(0,0,0,0.5);
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
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
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
                <button class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 flex items-center">
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
                <button  onclick="window.location.href='{{ route('bieumau.tao') }}'"class="text-indigo-600 bg-indigo-50 p-2 rounded-full" title="Biểu mẫu">
                    <span class="material-icons">view_headline</span>
                </button>
                <button  onclick="window.location.href='{{ route('bieumau.ds-cautraloi') }}'" class="text-gray-700 hover:text-indigo-600 p-2 rounded-full hover:bg-indigo-50" title="Câu Trả lời">
                    <span class="material-icons">description</span>
                </button>
                <button onclick="window.location.href='{{ route('bieumau.cai-dat') }}'" class="text-gray-700 hover:text-indigo-600 p-2 rounded-full hover:bg-indigo-50" title="Cài đặt">
                    <span class="material-icons">settings</span>
                </button>
            </div>

            <!-- Main Content -->
            <div class="flex-1 overflow-auto p-8">
                <div class="max-w-3xl mx-auto">
                    <!-- Form Header -->
                    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                        <div class="relative">
                            <input type="text" class="w-full text-2xl font-medium border-b-2 border-transparent focus:border-indigo-500 focus:outline-none py-2 px-1"
                                value="Biểu mẫu không tiêu đề" id="form-title">
                        </div>
                        <input type="text" class="w-full text-gray-500 border-b-2 border-transparent focus:border-indigo-500 focus:outline-none py-2 px-1 mt-2"
                            placeholder="Mô tả biểu mẫu" id="form-description">
                    </div>

                    <!-- Questions Container -->
                    <div id="questions-container" class="space-y-4">
                        <!-- Sample Question -->
                        <div class="question-box bg-white rounded-lg shadow-sm p-6 relative group" draggable="true">
                            <div class="flex items-start">
                                <div class="mr-4 flex flex-col items-center pt-2 drag-handle cursor-move">
                                    <span class="material-icons text-gray-400">drag_indicator</span>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between">
                                        <input type="text" class="w-full font-medium border-b-2 border-transparent focus:border-indigo-500 focus:outline-none py-1 px-1 mb-2"
                                            value="Tiêu đề câu hỏi" placeholder="Câu hỏi">
                                        <select class="text-sm border rounded-md px-3 py-1 focus:outline-none focus:ring-1 focus:ring-indigo-500">
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
                                        <input type="text" class="w-full font-medium border-b-2 border-transparent focus:border-indigo-500 focus:outline-none py-1 px-1 mb-2"
                                            value="Trắc nghiệm" placeholder="Câu hỏi">
                                        <select class="text-sm border rounded-md px-3 py-1 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                            <option>Trả lời ngắn</option>
                                            <option selected>Trắc nghiệm</option>
                                            <option>Hộp kiểm</option>
                                        </select>
                                    </div>
                                    <div class="mt-4 space-y-2">
                                        <div class="flex items-center">
                                            <span class="material-icons text-gray-400 mr-2">radio_button_unchecked</span>
                                            <input type="text" class="flex-1 border-b border-gray-300 py-1 focus:outline-none"
                                                value="Lựa chọn 1">
                                            <button class="text-gray-400 hover:text-gray-600 ml-2">
                                                <span class="material-icons">close</span>
                                            </button>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="material-icons text-gray-400 mr-2">radio_button_unchecked</span>
                                            <input type="text" class="flex-1 border-b border-gray-300 py-1 focus:outline-none"
                                                value="Lựa chọn 2">
                                            <button class="text-gray-400 hover:text-gray-600 ml-2">
                                                <span class="material-icons">close</span>
                                            </button>
                                        </div>
                                        <div class="flex items-center pl-8">
                                            <button class="text-indigo-600 hover:text-indigo-800 flex items-center text-sm">
                                                <span class="material-icons mr-1">add</span>
                                                Thêm lựa chọn
                                            </button>
                                        </div>
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
                                        <input type="checkbox" checked class="ml-2">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Question Button -->
                    <div class="mt-6 flex justify-center">
                        <button id="add-question" class="flex items-center text-indigo-600 hover:text-indigo-800 font-medium py-3 px-6 border-2 border-dashed border-indigo-200 rounded-lg hover:bg-indigo-50">
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
                                <div id="qr-code" class="w-64 h-64 bg-gray-100 flex items-center justify-center mb-4">
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
                                <select class="text-sm border rounded-md px-3 py-1 focus:outline-none focus:ring-1 focus:ring-indigo-500">
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
                newQuestion.scrollIntoView({ behavior: 'smooth' });
                
                // Thêm sự kiện thay đổi loại câu hỏi
                addQuestionTypeChangeHandler(newQuestion);
            });

            // Kéo thả câu hỏi
            const container = document.getElementById('questions-container');
            let draggedItem = null;

            container.addEventListener('dragstart', function(e) {
                if (e.target.classList.contains('question-box') || e.target.closest('.question-box')) {
                    const questionBox = e.target.classList.contains('question-box') ? e.target : e.target.closest('.question-box');
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
                        return { offset: offset, element: child };
                    } else {
                        return closest;
                    }
                }, { offset: Number.NEGATIVE_INFINITY }).element;
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
                    inputHTML = `<input type="text" class="w-full border-b border-gray-300 py-2 focus:outline-none text-gray-500" placeholder="Văn bản trả lời ngắn" disabled>`;
                } else if (selectedType === 'Trắc nghiệm') {
                    inputHTML = `
                        <div class="mt-4 space-y-2">
                            <div class="flex items-center">
                                <span class="material-icons text-gray-400 mr-2">radio_button_unchecked</span>
                                <input type="text" class="flex-1 border-b border-gray-300 py-1 focus:outline-none" value="Lựa chọn 1">
                                <button class="text-gray-400 hover:text-gray-600 ml-2">
                                    <span class="material-icons">close</span>
                                </button>
                            </div>
                            <div class="flex items-center">
                                <span class="material-icons text-gray-400 mr-2">radio_button_unchecked</span>
                                <input type="text" class="flex-1 border-b border-gray-300 py-1 focus:outline-none" value="Lựa chọn 2">
                                <button class="text-gray-400 hover:text-gray-600 ml-2">
                                    <span class="material-icons">close</span>
                                </button>
                            </div>
                            <div class="flex items-center pl-8">
                                <button class="text-indigo-600 hover:text-indigo-800 flex items-center text-sm">
                                    <span class="material-icons mr-1">add</span>
                                    Thêm lựa chọn
                                </button>
                            </div>
                        </div>
                    `;
                } else if (selectedType === 'Hộp kiểm') {
                    inputHTML = `
                        <div class="mt-4 space-y-2">
                            <div class="flex items-center">
                                <span class="material-icons text-gray-400 mr-2">check_box_outline_blank</span>
                                <input type="text" class="flex-1 border-b border-gray-300 py-1 focus:outline-none" value="Lựa chọn 1">
                                <button class="text-gray-400 hover:text-gray-600 ml-2">
                                    <span class="material-icons">close</span>
                                </button>
                            </div>
                            <div class="flex items-center">
                                <span class="material-icons text-gray-400 mr-2">check_box_outline_blank</span>
                                <input type="text" class="flex-1 border-b border-gray-300 py-1 focus:outline-none" value="Lựa chọn 2">
                                <button class="text-gray-400 hover:text-gray-600 ml-2">
                                    <span class="material-icons">close</span>
                                </button>
                            </div>
                            <div class="flex items-center pl-8">
                                <button class="text-indigo-600 hover:text-indigo-800 flex items-center text-sm">
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
    </script>
    <!-- Uncomment to use QR code library in production -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script> -->
</body>
</html>