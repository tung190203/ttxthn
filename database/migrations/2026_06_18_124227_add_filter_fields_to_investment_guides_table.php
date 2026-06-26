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
            $table->json('document_types')->nullable();
            $table->unsignedBigInteger('industry_id')->nullable();
            $table->string('issuing_authority')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('investment_guides', function (Blueprint $table) {
            $table->dropColumn(['document_types', 'industry_id', 'issuing_authority']);
        });
    }
};
