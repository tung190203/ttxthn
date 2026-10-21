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
        Schema::create('vrtour_version_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('version_id')->comment('ID phiên bản');
            $table->string('entity_type', 50)->comment('Loại dữ liệu');
            $table->string('entity_key', 191)->comment('ID đối tượng gốc');
            $table->string('parent_entity_key', 191)->nullable()->comment('ID đối tượng cha');
            $table->json('data')->comment('Dữ liệu đầy đủ của đối tượng');
            $table->string('content_hash', 64)->nullable()->comment('Mã kiểm tra thay đổi');
            $table->dateTime('created_at')->useCurrent()->comment('Thời điểm lưu');

            $table->unique(['version_id', 'entity_type', 'entity_key']);
            $table->index(['version_id', 'entity_type']);

            $table->foreign('version_id')->references('id')->on('vrtour_versions')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vrtour_version_items');
    }
};
