<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('luot_truy_cap', function (Blueprint $table) {
            $table->id();
            $table->string('ip');
            $table->string('user_agent')->nullable(); // thông tin trình duyệt/thiết bị
            $table->timestamp('thoi_gian')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('luot_truy_cap');
    }
};
