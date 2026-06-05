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
        Schema::table('hotspot', function (Blueprint $table) {
            $table->unsignedBigInteger('product_type')->nullable()->comment('Loại hình sản phẩm')->after('user_id');
            $table->unsignedTinyInteger('approval_level')->default(0)->after('product_type');
            $table->unsignedTinyInteger('max_approval')->default(2)->after('approval_level');
            $table->boolean('is_draft')->default(false)->after('max_approval');
            $table->unsignedBigInteger('parent_id')->nullable()->after('is_draft');
            $table->string('status', 20)->default('approved')->after('parent_id');
        });
    }

    public function down(): void
    {
        Schema::table('hotspot', function (Blueprint $table) {
            $table->dropColumn([
                'product_type',
                'approval_level',
                'max_approval',
                'is_draft',
                'parent_id',
                'status',
            ]);
        });
    }
};
