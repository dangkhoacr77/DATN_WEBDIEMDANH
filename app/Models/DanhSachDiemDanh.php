<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhSachDiemDanh extends Model
{
    protected $table = 'DanhSachDiemDanh';
    protected $primaryKey = 'ma_danh_sach';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['ma_danh_sach', 'ten_danh_sach', 'du_lieu_ds', 'ngay_tao', 'thoi_gian_tao', 'bieu_mau_ma', 'tai_khoan_ma', 'trang_thai'];
    public $timestamps = false;

    public function bieuMau()
    {
        return $this->belongsTo(BieuMau::class, 'bieu_mau_ma');
    }

    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'tai_khoan_ma');
    }

    public function diemDanhs()
    {
        return $this->hasMany(DiemDanh::class, 'danh_sach_ma');
    }
}

