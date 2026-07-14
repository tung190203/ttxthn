<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skin_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vrtour_id')->constrained('projects')->cascadeOnDelete();
            // 1: Welcome
            // 3: Connect Map
            // 4: Document
            // 5: Plan
            // 6: Investor
            // 7: Location
            $table->unsignedTinyInteger('type');
            // id của bản ghi thật
            $table->unsignedBigInteger('record_id')->nullable();
            // dữ liệu chờ duyệt
            $table->longText('payload');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('approval_level')->default(0);
            $table->unsignedTinyInteger('max_approval')->default(2);
            $table->boolean('is_draft')->default(true);
            $table->string('status', 20)->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skin_approvals');
    }
};
