<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCauHoiTableV2 extends Migration
{
    public function up()
    {
        Schema::create('CauHoi', function (Blueprint $table) {
            $table->string('ma_cau_hoi')->primary(); // Khóa chính
            $table->string('cau_hoi'); // Tiêu đề câu hỏi
            $table->boolean('cau_hoi_bat_buoc')->default(false); // Câu hỏi bắt buộc hay không
            $table->text('noi_dung')->nullable(); // Các lựa chọn dạng JSON hoặc nội dung
            $table->string('loai_cau_hoi'); // Loại câu hỏi: trắc nghiệm, hộp kiểm, tự luận
            $table->string('bieu_mau_ma'); // Khóa ngoại liên kết biểu mẫu

            $table->foreign('bieu_mau_ma')->references('ma_bieu_mau')->on('BieuMau')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('CauHoi');
    }
}
