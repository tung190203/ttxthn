<?php

namespace Database\Seeders;

use App\Models\ProjectIndustries;
use App\Models\ProjectType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // --- XÓA DỮ LIỆU CŨ ---
        DB::table('project_district')->truncate();
        DB::table('projects')->truncate();
        DB::table('districts')->truncate();
        DB::table('project_industries')->truncate();
        DB::table('project_types')->truncate();

        // --- SEED LOẠI DỰ ÁN ---
        $types = [
            1 => "Dự án hạ tầng",
            2 => "Dự án bất động sản",
            3 => "Khu công nghiệp",
            4 => "Khu phức hợp",
            5 => "Loại khác",
        ];

        foreach ($types as $type) {
            ProjectType::factory()->create(['name' => $type]);
        }

        // --- SEED NGÀNH/LĨNH VỰC ---
        $industries = [
            1 => "Hạ tầng giao thông",
            2 => "Tàu cảng",
            3 => "Môi trường đô thị",
            4 => "Hạ tầng đô thị",
            5 => "Hạ tầng dịch vụ",
            6 => "Công nghệ cao",
            7 => "Hạ tầng khu công nghiệp",
        ];

        foreach ($industries as $industry) {
            ProjectIndustries::factory()->create(['name' => $industry]);
        }

        // --- ĐỌC DỮ LIỆU JSON ---
        $json = file_get_contents(database_path('seeders/data/du_an_ha_noi.json'));
        $projectsData = json_decode($json, true);

        // --- LẤY DANH SÁCH QUẬN/HUYỆN DUY NHẤT ---
        $allDistricts = collect($projectsData)
            ->pluck('districts')
            ->flatten()
            ->unique()
            ->values();

        $districtIdMap = [];

        // --- SEED BẢNG `districts` ---
        foreach ($allDistricts as $districtName) {
            $id = DB::table('districts')->insertGetId([
                'name' => $districtName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $districtIdMap[$districtName] = $id;
        }

        // --- SEED `projects` + bảng trung gian ---
        foreach ($projectsData as $project) {
            $projectId = DB::table('projects')->insertGetId([
                'name' => $project['name'],
                'lat' => $project['lat'],
                'lng' => $project['lng'],
                'link' => $project['link'] ?? null,
                'price' => $project['price'] ?? null,
                'type_number' => $project['type_number'] ?? null,
                'industry_number' => $project['industry_number'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($project['districts'] as $districtName) {
                DB::table('project_district')->insert([
                    'project_id' => $projectId,
                    'district_id' => $districtIdMap[$districtName],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
