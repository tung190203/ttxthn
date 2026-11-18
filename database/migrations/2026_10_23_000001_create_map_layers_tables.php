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
        // 1. Bảng danh mục lớp dữ liệu bản đồ (LayerBanDo)
        Schema::create('map_layers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200); // Tên layer (bắt buộc, tối đa 200 ký tự)
            $table->enum('type', ['WMS', 'WFS', 'GeoJSON', 'Shapefile'])->default('GeoJSON'); // Loại nguồn
            $table->text('url_endpoint'); // URL endpoint của nguồn dữ liệu
            $table->string('file_path', 500)->nullable(); // Đường dẫn file lưu trữ nội bộ nếu upload
            $table->text('description')->nullable(); // Mô tả
            $table->integer('display_order')->default(0); // Thứ tự hiển thị
            $table->boolean('is_active')->default(true); // Trạng thái: Hiện (1) / Ẩn (0)
            $table->json('layer_options')->nullable(); // Thuộc tính hiển thị (color, opacity, wms layers, etc.)
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->index('is_active');
            $table->index('display_order');
        });

        // 2. Bảng nhật ký thay đổi layer (NhatKyThayDoiLayer) - Lưu tối thiểu 90 ngày
        Schema::create('map_layer_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('map_layer_id')->nullable()->index();
            $table->string('layer_name', 255);
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('user_name', 255)->nullable(); // Tên QTHT
            $table->string('action', 50); // CREATE, UPDATE, DELETE, TOGGLE_STATUS, REORDER
            $table->string('action_label', 100); // Thêm mới, Cập nhật, Xóa, Bật/Ẩn, Đổi thứ tự
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address', 45)->nullable(); // Địa chỉ IP
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });

        // 3. Bảng liên kết dự án tham chiếu layer (để cảnh báo khi xóa)
        Schema::create('project_map_layer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('map_layer_id');
            $table->timestamps();

            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('map_layer_id')->references('id')->on('map_layers')->onDelete('cascade');
            $table->unique(['project_id', 'map_layer_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_map_layer');
        Schema::dropIfExists('map_layer_audit_logs');
        Schema::dropIfExists('map_layers');
    }
};
