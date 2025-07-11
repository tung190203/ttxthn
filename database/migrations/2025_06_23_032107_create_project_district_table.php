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
        Schema::create('project_district', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id')->comment('Project ID');
            $table->unsignedBigInteger('district_id')->comment('District ID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_district');
    }
};
