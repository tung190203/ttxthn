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
        Schema::table('investment_guides', function (Blueprint $table) {
            $table->text('name')->change();
            $table->text('slug')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('investment_guides', function (Blueprint $table) {
            $table->string('name')->change();
            $table->string('slug')->change();
        });
    }
};
