<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Xoá bảng legal_document (văn bản pháp quy đã chuyển sang quản lý ở project).
     */
    public function up(): void
    {
        Schema::dropIfExists('legal_document');
    }

    /**
     * Rollback: tạo lại cấu trúc bảng (KHÔNG khôi phục dữ liệu cũ).
     * Cột dựa theo $fillable của model LegalDocument.
     */
    public function down(): void
    {
        if (Schema::hasTable('legal_document')) {
            return;
        }

        Schema::create('legal_document', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vrtour_id')->index();
            $table->text('name')->nullable();
            $table->text('name_en')->nullable();
            $table->string('download', 500)->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });
    }
};