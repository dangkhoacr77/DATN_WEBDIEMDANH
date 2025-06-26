@extends('layout.user')

@section('title', 'Thông tin cá nhân')
@section('page-title', 'Thông tin cá nhân')

@section('content')
    @if (session('success'))
        <div id="successMessage" class="alert alert-success" style="position: fixed; top: 20px; right: 20px; z-index: 999;">
            ✅ {{ session('success') }}
        </div>
    @endif

    @php
        $hoTen = trim($taiKhoan->ho_ten);
        $tuCuoi = collect(explode(' ', $hoTen))->filter()->last();
        $initial = strtoupper(mb_substr($tuCuoi, 0, 1));
    @endphp

    <div class="d-flex justify-content-center mb-4">
        <div
            style="width: 100px; height: 100px; background-color: #0d6efd; border-radius: 50%; color: white; display: flex; align-items: center; justify-content: center; font-size: 40px; font-weight: bold;">
            {{ $initial }}
        </div>
    </div>

    <form action="{{ route('nguoidung.tt-canhan.update', $taiKhoan->ma_tai_khoan) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label fw-medium">Họ và tên</label>
                <input type="text" name="ho_ten" class="form-control editable" value="{{ $taiKhoan->ho_ten }}" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-medium">Số điện thoại</label>
                <input type="text" name="so_dien_thoai" class="form-control editable"
                    value="{{ $taiKhoan->so_dien_thoai }}" readonly>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label fw-medium">Email</label>
                <input type="email" name="mail" class="form-control editable" value="{{ $taiKhoan->mail }}" readonly>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-medium">Ngày tạo</label>
                <input type="text" class="form-control"
                    value="{{ \Carbon\Carbon::parse($taiKhoan->ngay_tao)->format('d/m/Y') }}" readonly>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <label class="form-label fw-medium">Ngày sinh</label>
                <input type="text" name="ngay_sinh" class="form-control editable"
                    value="{{ \Carbon\Carbon::parse($taiKhoan->ngay_sinh)->format('d/m/Y') }}" readonly>
            </div>
        </div>

        <div class="text-end">
            <button type="button" id="editButton" class="btn btn-primary">Chỉnh sửa</button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        const editButton = document.getElementById('editButton');
        let isEditing = false;

        editButton.addEventListener('click', function() {
            const inputs = document.querySelectorAll('.editable');
            isEditing = !isEditing;

            inputs.forEach(input => {
                input.readOnly = !isEditing;
                input.style.backgroundColor = isEditing ? 'white' : '';
                input.style.border = isEditing ? '1px solid #0d6efd' : '';
            });

            editButton.textContent = isEditing ? 'Lưu' : 'Chỉnh sửa';

            if (!isEditing) {
                const confirmSave = confirm("Bạn có chắc muốn lưu các thay đổi?");
                if (confirmSave) {
                    document.querySelector('form').submit();
                } else {
                    isEditing = true;
                    inputs.forEach(input => {
                        input.readOnly = false;
                        input.style.backgroundColor = 'white';
                        input.style.border = '1px solid #0d6efd';
                    });
                    editButton.textContent = 'Lưu';
                }
            }
        });

        const successMessage = document.getElementById('successMessage');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.transition = 'opacity 0.5s ease';
                successMessage.style.opacity = '0';
                setTimeout(() => successMessage.remove(), 500);
            }, 2000);
        }
    </script>
@endpush