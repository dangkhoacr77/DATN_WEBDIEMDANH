<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaiKhoan extends Model
{
    protected $table = 'TaiKhoan';
    protected $primaryKey = 'ma_tai_khoan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ma_tai_khoan', 'hinh_anh', 'mail', 'so_dien_thoai', 'ngay_sinh',
        'ho_ten', 'mat_khau', 'loai_tai_khoan', 'trang_thai', 'ngay_tao'
    ];

    public $timestamps = false;

    public function bieuMaus()
    {
        return $this->hasMany(BieuMau::class, 'tai_khoan_ma');
    }

    public function danhSachDiemDanhs()
    {
        return $this->hasMany(DanhSachDiemDanh::class, 'tai_khoan_ma');
    }

    public function diemDanhs()
    {
        return $this->hasMany(DiemDanh::class, 'tai_khoan_ma');
    }
}
