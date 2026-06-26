<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Change the column type to text first
        Schema::table('investment_guides', function (Blueprint $table) {
            $table->text('industry_id')->nullable()->change();
        });

        // Convert existing bigint values to JSON array
        DB::table('investment_guides')
            ->whereNotNull('industry_id')
            ->where('industry_id', 'not like', '[%')
            ->update([
                'industry_id' => DB::raw("CONCAT('[', industry_id, ']')")
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('investment_guides', function (Blueprint $table) {
            // Note: Data might be lost if converting back to bigint if there are multiple values
            // So we just change the type back
            $table->bigInteger('industry_id')->nullable()->change();
        });
    }
};
