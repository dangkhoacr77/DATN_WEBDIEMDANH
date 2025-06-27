<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BieuMau extends Model
{
    protected $table = 'BieuMau';
    protected $primaryKey = 'ma_bieu_mau';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ma_bieu_mau', 'tieu_de', 'hinh_anh', 'mau', 'thoi_luong_diem_danh',
        'gioi_han_diem_danh', 'so_luong_da_diem_danh', 'trang_thai', 'ngay_tao', 'tai_khoan_ma'
    ];

    public $timestamps = false;

    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'tai_khoan_ma');
    }

    public function maQr()
    {
        return $this->hasOne(MaQR::class, 'bieu_mau_ma');
    }

    public function danhSach()
    {
        return $this->hasOne(DanhSachDiemDanh::class, 'bieu_mau_ma');
    }

    public function diemDanhs()
    {
        return $this->hasMany(DiemDanh::class, 'bieu_mau_ma');
    }

    public function cauHois()
    {
        return $this->hasMany(CauHoi::class, 'bieu_mau_ma');
    }
    
    
}

