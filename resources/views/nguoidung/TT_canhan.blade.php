@extends('layout.user')

@section('title', 'Thông tin cá nhân')
@section('page-title', 'Thông tin cá nhân')

@section('content')

    @php
        $hoTen = trim($taiKhoan->ho_ten);
        $tuCuoi = collect(explode(' ', $hoTen))->filter()->last();
        $initial = strtoupper(mb_substr($tuCuoi, 0, 1));
    @endphp

    @if (session('success'))
        <div id="successMessage"
            style="position: fixed; top: 20px; right: 20px; background-color: #4caf50; color: white; padding: 12px 24px; border-radius: 8px; font-size: 14px; box-shadow: 0 2px 6px rgba(0,0,0,0.2); z-index: 1000;">
            ✅ {{ session('success') }}
        </div>
    @endif

    <div
        style="max-width: 1000px; margin: auto; background: white; padding: 40px; border-radius: 16px; box-shadow: 0 4px 8px rgba(0,0,0,0.05);">
        <!-- Avatar -->
        <div style="display: flex; align-items: center; gap: 24px; margin-bottom: 40px;">
            <div
                style="width: 100px; height: 100px; border-radius: 9999px; background-color: #3b82f6; display: flex; align-items: center; justify-content: center; font-size: 40px; font-weight: bold; color: white; position: relative;">
                {{ $initial }}
                <div
                    style="position: absolute; bottom: -4px; right: -4px; width: 28px; height: 28px; border-radius: 9999px; background-color: white; display: flex; align-items: center; justify-content: center;">
                    <div
                        style="background-color: #22c55e; width: 20px; height: 20px; border-radius: 9999px; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px;">
                        ✔</div>
                </div>
            </div>
            <div>
                <h2 style="font-size: 24px; font-weight: bold; margin: 0;">{{ $taiKhoan->ho_ten }}</h2>
                <p style="margin: 4px 0; color: #6b7280;">Người dùng hệ thống</p>
                <span
                    style="background: #ecfdf5; color: #10b981; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 500;">👤
                    Quyền: Người dùng</span>
            </div>
        </div>

        <form action="{{ route('nguoidung.tt-canhan.update', $taiKhoan->ma_tai_khoan) }}" method="POST" id="profileForm">
            @csrf
            @method('PUT')

            <div style="display: flex; flex-direction: column; gap: 32px;">
                <div style="display: flex; gap: 32px; justify-content: space-between;">
                    <div style="flex: 1;">
                        <label style="display:block; font-weight:600; margin-bottom:6px;">Họ tên</label>
                        <input type="text" name="ho_ten" value="{{ $taiKhoan->ho_ten }}" readonly class="editable"
                            style="width: 100%; padding: 12px 16px; border-radius: 8px; background: #f9fafb; border: 1px solid #e5e7eb;">
                    </div>
                    <div style="flex: 1;">
                        <label style="display:block; font-weight:600; margin-bottom:6px;">Số điện thoại</label>
                        <input type="text" name="so_dien_thoai" value="{{ $taiKhoan->so_dien_thoai }}" readonly
                            class="editable"
                            style="width: 100%; padding: 12px 16px; border-radius: 8px; background: #f9fafb; border: 1px solid #e5e7eb;">
                    </div>
                    <div style="flex: 1;">
                        <label style="display:block; font-weight:600; margin-bottom:6px;">Email</label>
                        <input type="email" name="mail" value="{{ $taiKhoan->mail }}" readonly class="editable"
                            style="width: 100%; padding: 12px 16px; border-radius: 8px; background: #f9fafb; border: 1px solid #e5e7eb;">
                    </div>
                </div>

                <div style="display: flex; gap: 32px; justify-content: space-between;">
                    <div style="flex: 1;">
                        <label style="display:block; font-weight:600; margin-bottom:6px;">Ngày sinh</label>
                        <input type="text" name="ngay_sinh"
                            value="{{ \Carbon\Carbon::parse($taiKhoan->ngay_sinh)->format('d/m/Y') }}" readonly
                            class="editable"
                            style="width: 100%; padding: 12px 16px; border-radius: 8px; background: #f9fafb; border: 1px solid #e5e7eb;">
                    </div>
                    <div style="flex: 1;">
                        <label style="display:block; font-weight:600; margin-bottom:6px;">Ngày tạo</label>
                        <input type="text" value="{{ \Carbon\Carbon::parse($taiKhoan->ngay_tao)->format('d/m/Y') }}"
                            readonly
                            style="width: 100%; padding: 12px 16px; border-radius: 8px; background: #f9fafb; border: 1px solid #e5e7eb;">
                    </div>
                    <div style="flex: 1;"></div>
                </div>
            </div>

            <div style="margin-top: 32px; display: flex; justify-content: flex-end;">
                <button type="button" id="editButton"
                    style="background-color: #3b82f6; color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                    ✏️ Chỉnh sửa
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        const button = document.getElementById('editButton');
        let isEditing = false;

        button.addEventListener('click', function () {
            const inputs = document.querySelectorAll('.editable');
            isEditing = !isEditing;

            inputs.forEach(input => {
                input.readOnly = !isEditing;
                input.style.backgroundColor = isEditing ? 'white' : '#f9fafb';
                input.style.border = isEditing ? '1px solid #3b82f6' : '1px solid #e5e7eb';
            });

            button.innerHTML = isEditing ? '💾 Lưu thay đổi' : '✏️ Chỉnh sửa';
            button.style.backgroundColor = isEditing ? '#10b981' : '#3b82f6';

            if (!isEditing) {
                const confirmUpdate = confirm("Bạn có chắc muốn lưu các thay đổi?");
                if (confirmUpdate) {
                    document.getElementById('profileForm').submit();
                } else {
                    isEditing = true;
                    inputs.forEach(input => {
                        input.readOnly = false;
                        input.style.backgroundColor = 'white';
                        input.style.border = '1px solid #3b82f6';
                    });
                    button.innerHTML = '💾 Lưu thay đổi';
                    button.style.backgroundColor = '#10b981';
                }
            }
        });

        // Tự động ẩn thông báo
        window.addEventListener('DOMContentLoaded', () => {
            const message = document.getElementById('successMessage');
            if (message) {
                setTimeout(() => {
                    message.style.transition = 'opacity 0.5s ease';
                    message.style.opacity = '0';
                    setTimeout(() => {
                        if (message.parentNode) {
                            message.parentNode.removeChild(message);
                        }
                    }, 500);
                }, 3000);
            }
        });
    </script>
@endpush
