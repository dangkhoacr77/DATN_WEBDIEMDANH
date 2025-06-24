@extends('layout/user')

@section('title', 'Thông tin cá nhân')
@section('page-title', 'Thông tin cá nhân')

@section('content')
    <form id="infoForm">
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label fw-medium">Họ và tên</label>
                <input type="text" class="form-control" id="name" value="Võ Thành Đăng Khoa" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-medium">Email</label>
                <input type="email" class="form-control" id="email" value="khoa@example.com" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label fw-medium">Số điện thoại</label>
                <input type="tel" class="form-control" id="phone" value="0123456789" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-medium">Ngày tạo</label>
                <input type="text" class="form-control" id="created_at" value="2023-06-01" readonly>
            </div>
        </div>
        <div class="text-end mt-3">
            <button type="button" class="btn btn-primary" id="editBtn" onclick="toggleEdit()">Cập nhật</button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        // Avatar dropdown
        function toggleMenu() {
            const menu = document.getElementById('avatarDropdown');
            menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
        }
        window.onclick = event => {
            if (!event.target.closest('.avatar-menu')) {
                const menu = document.getElementById('avatarDropdown');
                if (menu) menu.style.display = 'none';
            }
        };

        // Toggle edit mode
        let isEditing = false;

        function toggleEdit() {
            const fields = ['name', 'email', 'phone'];
            const btn = document.getElementById('editBtn');

            if (!isEditing) {
                fields.forEach(id => document.getElementById(id).removeAttribute('readonly'));
                btn.textContent = 'Lưu';
            } else {
                fields.forEach(id => document.getElementById(id).setAttribute('readonly', true));
                btn.textContent = 'Cập nhật';
                // Xử lý lưu dữ liệu
                console.log('Dữ liệu đã cập nhật:', {
                    name: document.getElementById('name').value,
                    email: document.getElementById('email').value,
                    phone: document.getElementById('phone').value,
                    created_at: document.getElementById('created_at').value
                });
            }

            isEditing = !isEditing;
        }
    </script>
@endpush
