@extends('layout.admin')

@section('title', 'Thông tin cá nhân')
@section('page-title', 'Thông tin cá nhân')

@push('head')
<style>
    .info-input {
        width: 100%;
        max-width: 320px;
        padding: 10px 14px;
        border-radius: 8px;
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
    }

    .form-row {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        align-items: flex-start;
    }

    .form-group {
        flex: 1;
        min-width: 260px;
    }
</style>
@endpush

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

<div style="display: flex; gap: 20px;">
    <!-- Thông tin cá nhân -->
    <div style="flex: 1;">
        <div style="background: white; border-radius: 16px; padding: 32px;">
            <h2 style="font-weight: bold; font-size: 24px; margin-bottom: 24px;">Thông tin cá nhân</h2>
            <div style="display: flex; gap: 40px;">
                <!-- Avatar + Tên -->
                <div style="width: 280px; background: #f9fafb; padding: 24px; border-radius: 16px; text-align: center;">
                    <div style="position: relative; display: inline-block;">
                        <div style="width: 100px; height: 100px; background: #3b82f6; color: white; border-radius: 50%; font-size: 40px; font-weight: bold; display: flex; align-items: center; justify-content: center;">
                            {{ $initial }}
                        </div>
                        <span style="position: absolute; bottom: 0; right: 0; background: white; padding: 3px; border-radius: 50%;">
                            <span style="display: block; background: #22c55e; width: 14px; height: 14px; border-radius: 9999px;"></span>
                        </span>
                    </div>
                    <h3 style="margin-top: 16px; margin-bottom: 4px;">{{ $taiKhoan->ho_ten }}</h3>
                    <p style="margin: 0; color: #6b7280;">Người dùng hệ thống</p>
                    <div style="margin-top: 8px;">
                        <span style="background: #ecfdf5; color: #10b981; padding: 6px 12px; font-size: 14px; border-radius: 999px; display: inline-block;">
                            👤 Quyền: Admin
                        </span>
                    </div>
                </div>

                <!-- Form thông tin -->
                <div style="flex: 1;">
                    <form action="{{ route('thong-tin-ca-nhan.update', $taiKhoan->ma_tai_khoan) }}" method="POST" id="profileForm">
                        @csrf
                        @method('PUT')

                        <div style="display: flex; flex-direction: column; gap: 24px;">
                            <div class="form-row">
                                <div class="form-group">
                                    <label style="font-weight: 600;">Họ tên</label>
                                    <input type="text" name="ho_ten" value="{{ $taiKhoan->ho_ten }}" readonly class="editable info-input">
                                </div>
                                <div class="form-group">
                                    <label style="font-weight: 600;">Số điện thoại</label>
                                    <input type="text" name="so_dien_thoai" value="{{ $taiKhoan->so_dien_thoai }}" readonly class="editable info-input">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label style="font-weight: 600;">Email</label>
                                    <input type="email" name="mail" value="{{ $taiKhoan->mail }}" readonly class="editable info-input">
                                </div>
                                <div class="form-group">
                                    <label style="font-weight: 600;">Ngày sinh</label>
                                    <input type="text" name="ngay_sinh"
                                        value="{{ \Carbon\Carbon::parse($taiKhoan->ngay_sinh)->format('d/m/Y') }}"
                                        readonly class="editable info-input">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label style="font-weight: 600;">Ngày tạo</label><br>
                                    <input type="text" value="{{ \Carbon\Carbon::parse($taiKhoan->ngay_tao)->format('d/m/Y') }}" readonly class="info-input">
                                </div>
                            </div>
                        </div>

                        <!-- Nút -->
                        <div style="margin-top: 32px; display: flex; justify-content: flex-end;">
                            <button type="button" id="editButton"
                                style="background-color: #2563eb; color: white; padding: 10px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                                ✏️ Chỉnh sửa
                            </button>
                        </div>
                    </form>
                </div>
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
            input.style.backgroundColor = isEditing ? '#ffffff' : '#f3f4f6';
            input.style.border = isEditing ? '1px solid #2563eb' : '1px solid #e5e7eb';
        });

        button.innerHTML = isEditing ? '💾 Lưu thay đổi' : '✏️ Chỉnh sửa';
        button.style.backgroundColor = isEditing ? '#10b981' : '#2563eb';

        if (!isEditing) {
            const confirmUpdate = confirm("Bạn có chắc muốn lưu các thay đổi?");
            if (confirmUpdate) {
                document.getElementById('profileForm').submit();
            } else {
                isEditing = true;
                inputs.forEach(input => {
                    input.readOnly = false;
                    input.style.backgroundColor = '#ffffff';
                    input.style.border = '1px solid #2563eb';
                });
                button.innerHTML = '💾 Lưu thay đổi';
                button.style.backgroundColor = '#10b981';
            }
        }
    });

    window.addEventListener('DOMContentLoaded', () => {
        const msg = document.getElementById('successMessage');
        if (msg) {
            setTimeout(() => {
                msg.style.transition = 'opacity 0.5s ease';
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 500);
            }, 3000);
        }
    });
</script>
@endpush
