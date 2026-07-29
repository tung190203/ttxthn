<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('hotspot', function (Blueprint $table) {
            $table->dropUnique('hotspot_vrtour_position_unique');
        });

        Schema::table('hotspot', function (Blueprint $table) {
            $table->unique(
                ['vrtour_id', 'potision', 'is_draft'],
                'hotspot_vrtour_position_unique'
            );
        });
    }

    public function down()
    {
        Schema::table('hotspot', function (Blueprint $table) {
            $table->dropUnique('hotspot_vrtour_position_unique');
        });

        Schema::table('hotspot', function (Blueprint $table) {
            $table->unique(
                ['vrtour_id', 'potision'],
                'hotspot_vrtour_position_unique'
            );
        });
    }
};
