<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vrtour_3dmodel', function (Blueprint $table) {
            // Xóa các cột không còn dùng
            $table->dropColumn(['title_3d', 'title_3d_en', 'file_3d', 'show']);

            // Thêm các cột mới
            $table->boolean('show_3dmodel')->default(0)->after('user_id')->comment('0: ẩn, 1: hiện mô hình 3D');
            // $table->boolean('show_detail_plan')->default(0)->after('show_3dmodel')->comment('0: ẩn, 1: hiện chi tiết mặt bằng');
        });
    }

    public function down(): void
    {
        Schema::table('vrtour_3dmodel', function (Blueprint $table) {
            $table->dropColumn(['show_3dmodel']);

            $table->string('title_3d')->nullable()->comment('Mô hình 3d');
            $table->string('title_3d_en')->nullable()->comment('Tiêu đề tiếng Anh');
            $table->string('file_3d')->nullable()->comment('file .glb');
            $table->boolean('show')->default(0)->comment('0: ẩn, 1: hiện');
        });
    }
};