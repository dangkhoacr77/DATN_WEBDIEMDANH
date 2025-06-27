<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiemDanhConfigToBieumauTable extends Migration
{
    public function up()
    {
        Schema::table('bieumau', function (Blueprint $table) {
            $table->boolean('lay_dinh_vi')->default(false);
            $table->boolean('lay_ten_thiet_bi')->default(false);
            $table->boolean('lay_email')->default(false);
        });
    }

    public function down()
    {
        Schema::table('bieumau', function (Blueprint $table) {
            $table->dropColumn(['lay_dinh_vi', 'lay_ten_thiet_bi', 'lay_email']);
        });
    }
}
