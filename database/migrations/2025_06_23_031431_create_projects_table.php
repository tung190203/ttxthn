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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Project Name');
            $table->double('lat')->nullable()->comment('Latitude');
            $table->double('lng')->nullable()->comment('Longitude');
            $table->unsignedBigInteger('type_number')->comment('Type of Project');
            $table->unsignedBigInteger('industry_number')->comment('Industry of Project');
            $table->bigInteger('price')->nullable()->comment('Price in VND');
            $table->string('link')->nullable()->comment('Link to Project');
            $table->string('image')->nullable()->comment('Image URL');
            $table->text('description')->nullable()->comment('Project Description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
