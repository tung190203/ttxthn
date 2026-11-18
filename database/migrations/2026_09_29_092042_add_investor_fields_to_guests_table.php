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
        Schema::table('guests', function (Blueprint $table) {
            $table->string('investor_type')->default('individual'); // individual, organization, fdi
            $table->json('interested_sectors')->nullable();
            $table->string('organization_name')->nullable();
            $table->string('tax_code')->nullable();
            $table->string('status')->default('pending'); // pending, active, locked
            $table->integer('failed_login_attempts')->default(0);
            $table->timestamp('locked_until')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('guests', function (Blueprint $table) {
            $table->dropColumn([
                'investor_type',
                'interested_sectors',
                'organization_name',
                'tax_code',
                'status',
                'failed_login_attempts',
                'locked_until'
            ]);
        });
    }
};
