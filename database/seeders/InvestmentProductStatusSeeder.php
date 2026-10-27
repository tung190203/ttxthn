<?php

namespace Database\Seeders;

use App\Models\InvestmentProductStatus;
use Illuminate\Database\Seeder;

class InvestmentProductStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['name' => 'Còn trống',         'color_code' => '#22C55E'],
            ['name' => 'Đang thương lượng', 'color_code' => '#F59E0B'],
            ['name' => 'Đã ký',             'color_code' => '#3B82F6'],
            ['name' => 'Đã cho thuê',       'color_code' => '#EF4444'],
        ];

        foreach ($statuses as $status) {
            InvestmentProductStatus::updateOrCreate(
                ['name' => $status['name']],
                ['color_code' => $status['color_code']]
            );
        }
    }
}