<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('bieumaus', function (Blueprint $table) {
        $table->id();
        $table->string('tieu_de')->nullable();
        $table->text('mo_ta')->nullable();
        $table->json('cau_hoi')->nullable(); // lưu cấu trúc câu hỏi
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bieumaus');
    }
};
