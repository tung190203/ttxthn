<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vrtour_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vrtour_id')->comment('ID tour (projects.id)');
            $table->string('scope', 20)->comment('Chuỗi phiên bản: panorama | hotspot | skin');
            $table->unsignedInteger('version_number')->comment('Số phiên bản, đánh số riêng theo (tour, scope)');
            $table->string('version_type', 20)->default('publish')->comment('publish | reset | restore');
            $table->unsignedBigInteger('source_version_id')->nullable()->comment('Phiên bản nguồn khi khôi phục');
            $table->unsignedBigInteger('created_by')->nullable()->comment('Người tạo phiên bản');
            $table->dateTime('created_at')->useCurrent()->comment('Thời điểm tạo');
            $table->text('change_summary')->nullable()->comment('Tóm tắt thay đổi');
            $table->text('restore_reason')->nullable()->comment('Lý do khôi phục');
            $table->unsignedBigInteger('confirmed_by')->nullable()->comment('Người xác nhận khôi phục');
            $table->dateTime('confirmed_at')->nullable()->comment('Thời điểm xác nhận');

            // Thống kê theo các item của version, ghi một lần khi tạo
            $table->unsignedInteger('item_count')->default(0)->comment('Số bản ghi thay đổi');
            $table->unsignedInteger('added_count')->default(0)->comment('Số mục thêm');
            $table->unsignedInteger('removed_count')->default(0)->comment('Số mục xóa');
            $table->unsignedInteger('modified_count')->default(0)->comment('Số mục sửa');

            $table->unique(['vrtour_id', 'scope', 'version_number']);
            $table->index(['vrtour_id', 'scope', 'created_at']);

            $table->foreign('source_version_id')->references('id')->on('vrtour_versions')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vrtour_versions');
    }
};