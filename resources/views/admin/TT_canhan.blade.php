@extends('layout.admin')

@section('title', 'Thông tin cá nhân')

@section('page-title', 'Thông tin cá nhân')

@section('content')
<!-- Success Message -->
<div id="successMessage"
    style="display: none; position: fixed; top: 20px; right: 20px; background-color: #4caf50; color: white; padding: 12px 24px; border-radius: 8px; font-size: 14px; box-shadow: 0 2px 6px rgba(0,0,0,0.2); z-index: 1000;">
    ✅ Cập nhật thành công!
</div>

<!-- Form Thông tin cá nhân -->
<div style="background: white; border-radius: 16px; padding: 40px; max-width: 100%; width: 95%; margin: auto;">
    <div style="width: 100px; height: 100px; background: #ccc; border-radius: 50%; margin-bottom: 30px;"></div>
    <form style="display: flex; flex-direction: column; gap: 24px;">
        <div style="display: flex; gap: 40px;">
            <div style="flex: 1; display: flex; flex-direction: column;">
                <label style="margin-bottom: 8px; font-weight: 500; font-size: 14px;">Họ tên</label>
                <input type="text" value="Lý Thanh Duy" readonly class="editable"
                    style="padding: 10px 14px; border: 1.5px solid #e0e0e0; border-radius: 10px; background: #f9f9f9; font-size: 14px; color: #111;">
            </div>
            <div style="flex: 1; display: flex; flex-direction: column;">
                <label style="margin-bottom: 8px; font-weight: 500; font-size: 14px;">Số điện thoại</label>
                <input type="text" value="0312546971" readonly class="editable"
                    style="padding: 10px 14px; border: 1.5px solid #e0e0e0; border-radius: 10px; background: #f9f9f9; font-size: 14px; color: #111;">
            </div>
        </div>

        <div style="display: flex; gap: 40px;">
            <div style="flex: 1; display: flex; flex-direction: column;">
                <label style="margin-bottom: 8px; font-weight: 500; font-size: 14px;">Email</label>
                <input type="email" value="lythanhduy@gmail.com" readonly class="editable"
                    style="padding: 10px 14px; border: 1.5px solid #e0e0e0; border-radius: 10px; background: #f9f9f9; font-size: 14px; color: #111;">
            </div>
            <div style="flex: 1; display: flex; flex-direction: column;">
                <label style="margin-bottom: 8px; font-weight: 500; font-size: 14px;">Ngày tạo</label>
                <input type="text" value="01/01/2025" readonly
                    style="padding: 10px 14px; border: 1.5px solid #e0e0e0; border-radius: 10px; background: #f9f9f9; font-size: 14px; color: #111;">
            </div>
        </div>

        <div style="display: flex; gap: 40px;">
            <div style="flex: 1; display: flex; flex-direction: column;">
                <label style="margin-bottom: 8px; font-weight: 500; font-size: 14px;">Ngày sinh</label>
                <input type="text" value="01/01/2004" readonly class="editable"
                    style="padding: 10px 14px; border: 1.5px solid #e0e0e0; border-radius: 10px; background: #f9f9f9; font-size: 14px; color: #111;">
            </div>
            <div style="flex: 1;"></div>
        </div>

        <button id="editButton" type="button"
            style="align-self: flex-end; margin-top: 20px; background-color: #0047ff; color: white; padding: 12px 24px; border: none; border-radius: 10px; font-weight: bold; font-size: 14px; cursor: pointer;">Chỉnh
            sửa</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const button = document.getElementById('editButton');
    const successBox = document.getElementById('successMessage');
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
            successBox.style.display = 'block';
            successBox.style.opacity = '1';
            setTimeout(() => {
                successBox.style.opacity = '0';
                setTimeout(() => {
                    successBox.style.display = 'none';
                }, 500);
            }, 2500);
        }
    });
</script>
@endpush
