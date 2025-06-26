@extends('layout.admin')

@section('title', 'Thông tin cá nhân')

@section('page-title', 'Thông tin cá nhân')

@section('content')
@if(session('success'))
    <div id="successMessage"
        style="position: fixed; top: 20px; right: 20px; background-color: #4caf50; color: white; padding: 12px 24px; border-radius: 8px; font-size: 14px; box-shadow: 0 2px 6px rgba(0,0,0,0.2); z-index: 1000;">
        ✅ {{ session('success') }}
    </div>
@endif

@php
    $hoTen = trim($taiKhoan->ho_ten);
    $tuCuoi = collect(explode(' ', $hoTen))->filter()->last();
    $initial = strtoupper(mb_substr($tuCuoi, 0, 1));
@endphp

<div style="background: white; border-radius: 16px; padding: 40px; max-width: 100%; width: 95%; margin: auto;">
    <div style="
        width: 100px;
        height: 100px;
        background-color: #4CAF50;
        border-radius: 50%;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 40px;
        font-weight: bold;
        text-transform: uppercase;">
        {{ $initial }}
    </div>

    <form action="{{ route('thong-tin-ca-nhan.update', $taiKhoan->ma_tai_khoan) }}" method="POST"
        style="display: flex; flex-direction: column; gap: 24px;">
        @csrf
        @method('PUT')

        <div style="display: flex; gap: 40px;">
            <div style="flex: 1; display: flex; flex-direction: column;">
                <label style="margin-bottom: 8px; font-weight: 500; font-size: 14px;">Họ tên</label>
                <input type="text" name="ho_ten" value="{{ $taiKhoan->ho_ten }}" readonly class="editable"
                    style="padding: 10px 14px; border: 1.5px solid #e0e0e0; border-radius: 10px;
                           background: #f9f9f9; font-size: 14px; color: #111;">
            </div>

            <div style="flex: 1; display: flex; flex-direction: column;">
                <label style="margin-bottom: 8px; font-weight: 500; font-size: 14px;">Số điện thoại</label>
                <input type="text" name="so_dien_thoai" value="{{ $taiKhoan->so_dien_thoai }}" readonly class="editable"
                    style="padding: 10px 14px; border: 1.5px solid #e0e0e0; border-radius: 10px;
                           background: #f9f9f9; font-size: 14px; color: #111;">
            </div>
        </div>

        <div style="display: flex; gap: 40px;">
            <div style="flex: 1; display: flex; flex-direction: column;">
                <label style="margin-bottom: 8px; font-weight: 500; font-size: 14px;">Email</label>
                <input type="email" name="mail" value="{{ $taiKhoan->mail }}" readonly class="editable"
                    style="padding: 10px 14px; border: 1.5px solid #e0e0e0; border-radius: 10px;
                           background: #f9f9f9; font-size: 14px; color: #111;">
            </div>

            <div style="flex: 1; display: flex; flex-direction: column;">
                <label style="margin-bottom: 8px; font-weight: 500; font-size: 14px;">Ngày tạo</label>
                <input type="text" value="{{ \Carbon\Carbon::parse($taiKhoan->ngay_tao)->format('d/m/Y') }}" readonly
                    style="padding: 10px 14px; border: 1.5px solid #e0e0e0; border-radius: 10px;
                           background: #f9f9f9; font-size: 14px; color: #111;">
            </div>
        </div>

        <div style="display: flex; gap: 40px;">
            <div style="flex: 1; display: flex; flex-direction: column;">
                <label style="margin-bottom: 8px; font-weight: 500; font-size: 14px;">Ngày sinh</label>
                <input type="text" name="ngay_sinh"
                    value="{{ \Carbon\Carbon::parse($taiKhoan->ngay_sinh)->format('d/m/Y') }}" readonly class="editable"
                    style="padding: 10px 14px; border: 1.5px solid #e0e0e0; border-radius: 10px;
                           background: #f9f9f9; font-size: 14px; color: #111;">
            </div>
            <div style="flex: 1;"></div>
        </div>

        <button id="editButton" type="button"
            style="align-self: flex-end; margin-top: 20px; background-color: #0047ff; color: white;
                   padding: 12px 24px; border: none; border-radius: 10px; font-weight: bold;
                   font-size: 14px; cursor: pointer;">
            Chỉnh sửa
        </button>
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
            input.style.backgroundColor = isEditing ? 'white' : '#f9f9f9';
            input.style.border = isEditing ? '1.5px solid #0047ff' : '1.5px solid #e0e0e0';
        });

        button.textContent = isEditing ? 'Lưu' : 'Chỉnh sửa';

        if (!isEditing) {
            const confirmUpdate = confirm("Bạn có chắc muốn lưu các thay đổi?");
            if (confirmUpdate) {
                document.querySelector('form').submit();
            } else {
                // Quay lại chế độ chỉnh sửa nếu hủy
                isEditing = true;
                inputs.forEach(input => {
                    input.readOnly = false;
                    input.style.backgroundColor = 'white';
                    input.style.border = '1.5px solid #0047ff';
                });
                button.textContent = 'Lưu';
            }
        }
    });
</script>
@endpush
