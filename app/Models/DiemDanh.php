<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiemDanh extends Model
{
    protected $table = 'DiemDanh';
    protected $primaryKey = 'ma_diem_danh';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ma_diem_danh',
        'thoi_gian_diem_danh',
        'thiet_bi_diem_danh',
        'dinh_vi_thiet_bi',
        'bieu_mau_ma',
        'tai_khoan_ma',
        'danh_sach_ma'
    ];

    public $timestamps = false;

    public function bieuMau()
    {
        return $this->belongsTo(BieuMau::class, 'bieu_mau_ma');
    }

    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'tai_khoan_ma');
    }

    public function danhSach()
    {
        return $this->hasOne(DanhSachDiemDanh::class, 'ma_danh_sach', 'danh_sach_ma')
            ->whereColumn('bieu_mau_ma', 'bieu_mau_ma');
    }

    public function cauTraLoi()
    {
        return $this->hasMany(CauTraLoi::class, 'diem_danh_ma');
    }
}
