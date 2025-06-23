<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaQR extends Model
{
    protected $table = 'Ma_QR';
    protected $primaryKey = 'ma_qr';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['ma_qr', 'hinh_anh', 'duong_dan', 'trang_thai', 'ngay_tao', 'bieu_mau_ma'];
    public $timestamps = false;

    public function bieuMau()
    {
        return $this->belongsTo(BieuMau::class, 'bieu_mau_ma');
    }
}

