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
        Schema::create('investment_product_statuses', function (Blueprint $table) {
            $table->comment('trạng thái sản phẩm đầu tư');
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('color_code', 7);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_product_statuses');
    }
};
