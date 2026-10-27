<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vrtour_version_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('version_id')->comment('ID phiên bản');
            // panorama | hotspot | welcome | connect_map | plan | investor | location | document
            $table->string('entity_type', 50)->comment('Loại dữ liệu');
            $table->string('entity_key', 191)->comment('Khóa đối tượng: id, hoặc mã ổn định (vd: hash link của document)');
            $table->string('action', 10)->comment('added | modified | removed');
            $table->json('before_data')->nullable()->comment('Giá trị trước khi đổi (null khi added)');
            $table->json('after_data')->nullable()->comment('Giá trị sau khi đổi (null khi removed)');
            $table->string('before_hash', 64)->nullable();
            $table->string('after_hash', 64)->nullable();
            $table->dateTime('created_at')->useCurrent()->comment('Thời điểm lưu');

            // Trong một version, một bản ghi chỉ xuất hiện một lần
            $table->unique(['version_id', 'entity_type', 'entity_key']);
            // Tra lịch sử của một bản ghi
            $table->index(['entity_type', 'entity_key', 'version_id']);

            $table->foreign('version_id')->references('id')->on('vrtour_versions')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vrtour_version_items');
    }
};