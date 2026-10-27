<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vrtour_3dmodel', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vrtour_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('title_3d')->nullable()->comment('Mô hình 3d');
            $table->string('title_3d_en')->nullable()->comment('Tiêu đề tiếng Anh');
            $table->string('file_3d')->nullable()->comment('file .glb');
            $table->boolean('show')->default(0)->comment('0: ẩn, 1: hiện');
            $table->foreign('vrtour_id')->references('id')->on('projects')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vrtour_3dmodel');
    }
};