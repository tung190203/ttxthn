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
        Schema::table('projects', function (Blueprint $table) {
            $table->text('legal_access_levels')->nullable()->after('legal_file');
        });

        // Tự động gán mặc định 'public' (Công khai) cho toàn bộ file cũ hiện có
        $projects = DB::table('projects')
            ->whereNotNull('legal_file')
            ->where('legal_file', '!=', '')
            ->where('legal_file', '!=', 'null')
            ->select('id', 'legal_file')
            ->get();

        foreach ($projects as $project) {
            $files = array_filter(array_map('trim', explode(';', $project->legal_file)));
            if (count($files) > 0) {
                $levels = array_fill(0, count($files), 'public');
                DB::table('projects')
                    ->where('id', $project->id)
                    ->update([
                        'legal_access_levels' => implode(';', $levels),
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('legal_access_levels');
        });
    }
};
