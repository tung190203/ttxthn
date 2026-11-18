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
        Schema::create('compare_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id')->index();
            $table->unsignedBigInteger('project_id')->index();
            $table->string('criterion_key')->nullable()->index();
            $table->text('note')->nullable();
            $table->timestamps();
            
            $table->foreign('session_id')->references('id')->on('compare_sessions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compare_notes');
    }
};
