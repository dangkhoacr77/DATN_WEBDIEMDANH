<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CauHoi extends Model
{
    use HasFactory;

    protected $table = 'CauHoi';
    protected $primaryKey = 'ma_cau_hoi';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ma_cau_hoi',
        'cau_hoi',
        'cau_hoi_bat_buoc',
        'noi_dung',
        'loai_cau_hoi',
        'bieu_mau_ma'
    ];

    public $timestamps = false;

    protected $casts = [
        'noi_dung' => 'array', // Tự động chuyển JSON -> array và ngược lại
        'cau_hoi_bat_buoc' => 'boolean'
    ];

    public function bieuMau()
    {
        return $this->belongsTo(BieuMau::class, 'bieu_mau_ma');
    }

    public function cauTraLoi()
    {
        return $this->hasMany(CauTraLoi::class, 'cau_hoi_ma');
    }
}
