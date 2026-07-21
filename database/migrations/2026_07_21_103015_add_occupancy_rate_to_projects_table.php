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
        Schema::table('projects', function (Blueprint $table) {
            $table->boolean('has_occupancy_rate')->default(false)->after('is_hidden');
            $table->decimal('occupancy_rate', 5, 2)->nullable()->after('has_occupancy_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['has_occupancy_rate', 'occupancy_rate']);
        });
    }
};
