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
        Schema::create('vrtour_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vrtour_id')->comment('ID tour');
            $table->unsignedInteger('version_number')->comment('Số phiên bản');
            $table->string('version_type', 20)->default('publish')->comment('Loại phiên bản');
            $table->unsignedBigInteger('source_version_id')->nullable()->comment('Phiên bản nguồn khi khôi phục');
            $table->unsignedBigInteger('created_by')->nullable()->comment('Người tạo phiên bản');
            $table->dateTime('created_at')->useCurrent()->comment('Thời điểm tạo');
            $table->text('change_summary')->nullable()->comment('Tóm tắt thay đổi');
            $table->text('restore_reason')->nullable()->comment('Lý do khôi phục');
            $table->unsignedBigInteger('confirmed_by')->nullable()->comment('Người xác nhận khôi phục');
            $table->dateTime('confirmed_at')->nullable()->comment('Thời điểm xác nhận');

            $table->unique(['vrtour_id', 'version_number']);
            $table->index(['vrtour_id', 'created_at']);

            // $table->foreign('source_version_id')->references('id')->on('vrtour_versions')->restrictOnDelete();
            $table->foreign('source_version_id')->references('id')->on('vrtour_versions')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vrtour_versions');
    }
};
