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
        style="position: fixed; top: 20px; right: 20px; background-color: #22c55e; color: white; padding: 12px 24px; border-radius: 12px; font-size: 14px; box-shadow: 0 2px 10px rgba(0,0,0,0.15); z-index: 999;">
        ✅ {{ session('success') }}
    </div>
@endif

<div class="container" style="max-width: 1100px;">
    <div class="row g-4">
        <!-- Sidebar Avatar -->
        <div class="col-lg-4 col-md-12">
            <div class="bg-white shadow-sm rounded-4 p-4 text-center h-100">
                <div class="position-relative d-inline-block mb-3">
                    <div
                        style="width: 100px; height: 100px; background: #3b82f6; color: white; font-size: 36px; font-weight: bold; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                        {{ $initial }}
                    </div>
                    <span class="position-absolute bottom-0 end-0 bg-white rounded-circle p-1">
                        <span class="d-block bg-success rounded-circle" style="width: 14px; height: 14px;"></span>
                    </span>
                </div>
                <h4 class="fw-bold mb-1">{{ $taiKhoan->ho_ten }}</h4>
                <p class="text-muted mb-1">Người dùng hệ thống</p>
                <span class="badge bg-success-subtle text-success fs-6">👤 Quyền: Người dùng</span>
            </div>
        </div>

        <!-- Form Section -->
        <div class="col-lg-8 col-md-12">
            <div class="bg-white shadow-sm rounded-4 p-4">
                <form action="{{ route('nguoidung.tt-canhan.update', $taiKhoan->ma_tai_khoan) }}" method="POST" id="profileForm">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Họ tên</label>
                            <input type="text" name="ho_ten" value="{{ $taiKhoan->ho_ten }}" readonly class="form-control editable">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Số điện thoại</label>
                            <input type="text" name="so_dien_thoai" value="{{ $taiKhoan->so_dien_thoai }}" readonly class="form-control editable">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="mail" value="{{ $taiKhoan->mail }}" readonly class="form-control editable">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ngày sinh</label>
                            <input type="text" name="ngay_sinh"
                                value="{{ \Carbon\Carbon::parse($taiKhoan->ngay_sinh)->format('d/m/Y') }}"
                                readonly class="form-control editable">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ngày tạo</label>
                            <input type="text" value="{{ \Carbon\Carbon::parse($taiKhoan->ngay_tao)->format('d/m/Y') }}" readonly class="form-control">
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="button" id="editButton"
                            class="btn btn-primary d-inline-flex align-items-center gap-2 px-4 py-2">
                            ✏️ <span>Chỉnh sửa</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
            input.style.backgroundColor = isEditing ? '#ffffff' : '';
            input.style.borderColor = isEditing ? '#0d6efd' : '#dee2e6';
        });

        button.innerHTML = isEditing ? '💾 <span>Lưu thay đổi</span>' : '✏️ <span>Chỉnh sửa</span>';
        button.classList.toggle('btn-primary', !isEditing);
        button.classList.toggle('btn-success', isEditing);

        if (!isEditing) {
            const confirmUpdate = confirm("Bạn có chắc muốn lưu các thay đổi?");
            if (confirmUpdate) {
                document.getElementById('profileForm').submit();
            } else {
                // Không lưu thì tiếp tục chỉnh
                isEditing = true;
                inputs.forEach(input => {
                    input.readOnly = false;
                    input.style.backgroundColor = '#ffffff';
                    input.style.borderColor = '#0d6efd';
                });
                button.innerHTML = '💾 <span>Lưu thay đổi</span>';
                button.classList.remove('btn-primary');
                button.classList.add('btn-success');
            }
        }
    });

    window.addEventListener('DOMContentLoaded', () => {
        const msg = document.getElementById('successMessage');
        if (msg) {
            setTimeout(() => {
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 500);
            }, 3000);
        }
    });
</script>
@endpush
