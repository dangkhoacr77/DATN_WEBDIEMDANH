<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CauTraLoi extends Model
{
    protected $table = 'CauTraLoi';
    protected $primaryKey = 'ma_cau_tra_loi';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['ma_cau_tra_loi', 'cau_tra_loi', 'diem_danh_ma', 'cau_hoi_ma'];
    public $timestamps = false;

    public function cauHoi()
    {
        return $this->belongsTo(CauHoi::class, 'cau_hoi_ma');
    }

    public function diemDanh()
    {
        return $this->belongsTo(DiemDanh::class, 'diem_danh_ma');
    }
}

