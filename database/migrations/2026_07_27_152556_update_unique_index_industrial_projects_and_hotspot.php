<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('industrial_projects', function (Blueprint $table) {
            $table->dropUnique('industrial_projects_code_unique');
            $table->unique(
                ['project_id', 'code'],
                'industrial_projects_project_code_unique'
            );
        });

        Schema::table('hotspot', function (Blueprint $table) {
            $table->unique(
                ['vrtour_id', 'potision'],
                'hotspot_vrtour_position_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::table('hotspot', function (Blueprint $table) {
            $table->dropUnique('hotspot_vrtour_position_unique');
        });

        Schema::table('industrial_projects', function (Blueprint $table) {
            $table->dropUnique('industrial_projects_project_code_unique');
            $table->unique('code');
        });
    }
};
