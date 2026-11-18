<?php

namespace App\Http\Controllers;

use App\Models\IndustrialProject;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\District;

class MapController extends Controller
{
    private const NUMERIC_VALIDATION_RULE = 'required|numeric';

    public function getProjectsInBounds(Request $request)
    {
        $request->validate([
            'minLat' => self::NUMERIC_VALIDATION_RULE,
            'maxLat' => self::NUMERIC_VALIDATION_RULE,
            'minLng' => self::NUMERIC_VALIDATION_RULE,
            'maxLng' => self::NUMERIC_VALIDATION_RULE,
            'tab' => 'required|in:project,industrial',
        ]);

        $hasFilters = $request->filled('search') ||
            ($request->filled('project_id') && $request->project_id !== 'all') ||
            ($request->filled('product_type') && $request->product_type !== 'all') ||
            ($request->has('price') && (int)$request->price > 0) ||
            ($request->filled('type') && $request->type !== 'all') ||
            ($request->filled('industry') && $request->industry !== 'all') ||
            ($request->filled('invest_status') && $request->invest_status !== 'all') ||
            $request->filled('district');

        if ($request->tab === 'project') {
            $query = Project::with(['type', 'industry', 'districts']);

            if (!$hasFilters) {
                $query->inBounds($request->minLat, $request->maxLat, $request->minLng, $request->maxLng);
            }

            $query->filterByRequest($request);
        } else {
            $query = Project::with(['type', 'industry', 'districts', 'industrialProjects']);

            if (!$hasFilters) {
                $query->inBounds($request->minLat, $request->maxLat, $request->minLng, $request->maxLng);
            } else {
                $projectIds = IndustrialProject::filterProjectIds($request);

                if ($projectIds->isEmpty()) {
                    return response()->json([]);
                }

                $query->with([
                    'industrialProjects' => fn($q) => $q->filterByRequest($request)
                ])
                    ->whereIn('id', $projectIds)
                    ->whereHas('industrialProjects', fn($q) => $q->filterByRequest($request));

                $query->filterProjectOnly($request);
            }
        }

        $projects = $query->whereNull('parent_id')->where('status', 'approved')->get();
        $data = $this->returnData($projects);
        return response()->json($data);
    }

    public function getDistricts()
    {
        $districts = District::query()
            ->select('name', 'boundary')
            ->orderBy('name')
            ->get()
            ->map(function ($district) {
                return [
                    'name' => $district->name,
                    'name_vi' => $district->getTranslation('name', 'vi'),
                    'boundary' => $district->boundary,
                ];
            });

        return response()->json($districts);
    }

    public function getRailwayProjects()
    {
        $projects = Project::with(['type', 'industry', 'districts'])
            ->whereNull('parent_id')
            ->where('status', 'approved')
            ->where('industry_number', Project::RAILWAY_INDUSTRY_NUMBER)
            ->whereNotNull('railway_lines')
            ->get()
            ->filter(fn($project) => !empty($project->railway_lines));

        return response()->json($this->returnData($projects)->values());
    }

    private function returnData($projects)
    {
        return $projects->map(function ($project) {
            return [
                'id' => $project->id,
                'name' => $project->name,
                'lat' => $project->lat,
                'lng' => $project->lng,
                'type_number' => $project->type_number,
                'type_name' => $project->type->name ?? null,
                'industry_name' => $project->industry->name ?? null,
                'industry_number' => $project->industry_number,
                'area' => $project->area,
                'unit' => $project->unit_type_text,
                'price' => $project->price,
                'link' => $project->link,
                'link_vrtour' => $project->hide_vrtour ? null : $project->link_vrtour,
                'is_invest' => $project->is_invest,
                'banner_image' => $project->banner_image,
                'detail_image' => $project->detail_image,
                'boundary' => $project->boundary,
                'has_occupancy_rate' => $project->has_occupancy_rate,
                'occupancy_rate' => $project->occupancy_rate,
                'railway_lines' => $project->railway_lines ?? [],
                'districts' => $project->districts->pluck('name')->toArray(),
                'industrial' => $project->industrialProjects->map(function ($industrialProject) {
                    return [
                        'id' => $industrialProject->id,
                        'name' => $industrialProject->name,
                        'acreage' => $industrialProject->acreage,
                        'code' => $industrialProject->code,
                        'description' => $industrialProject->description,
                        'product_type' => $industrialProject->product_type,
                        'product_type_name' => $industrialProject->productType->name ?? null,
                        'price' => $industrialProject->price,
                        'link' => $industrialProject->link,
                        'intended_use' => $industrialProject->intended_use,
                        'unit' => $industrialProject->unit ? "km" : "ha",
                    ];
                })->toArray(),
            ];
        });
    }

    /**
     * Xuất dữ liệu không gian bản đồ theo bộ lọc người dùng (Module G-05)
     * Hỗ trợ 2 định dạng: GeoJSON (RFC 7946 / CRS84 cho QGIS/ArcGIS) và CSV (Excel UTF-8 BOM).
     */
    public function export(Request $request)
    {
        $format = strtolower($request->get('format', 'geojson'));
        $tab = $request->get('tab', 'project');

        $hasFilters = $request->filled('search') ||
            ($request->filled('project_id') && $request->project_id !== 'all') ||
            ($request->filled('product_type') && $request->product_type !== 'all') ||
            ($request->has('price') && (int)$request->price > 0) ||
            ($request->filled('type') && $request->type !== 'all') ||
            ($request->filled('industry') && $request->industry !== 'all') ||
            ($request->filled('invest_status') && $request->invest_status !== 'all') ||
            $request->filled('district');

        if ($tab === 'project') {
            $query = Project::with(['type', 'industry', 'districts']);

            if (!$hasFilters && $request->filled(['minLat', 'maxLat', 'minLng', 'maxLng'])) {
                $query->inBounds($request->minLat, $request->maxLat, $request->minLng, $request->maxLng);
            }

            $query->filterByRequest($request);
        } else {
            $query = Project::with(['type', 'industry', 'districts', 'industrialProjects']);

            if (!$hasFilters && $request->filled(['minLat', 'maxLat', 'minLng', 'maxLng'])) {
                $query->inBounds($request->minLat, $request->maxLat, $request->minLng, $request->maxLng);
            } elseif ($hasFilters) {
                $projectIds = IndustrialProject::filterProjectIds($request);

                if ($projectIds->isEmpty()) {
                    return $format === 'csv'
                        ? $this->exportCsv(collect([]), 'hanoi_gis_projects_' . date('Ymd_His') . '.csv')
                        : $this->exportGeoJson(collect([]), 'hanoi_gis_projects_' . date('Ymd_His') . '.geojson');
                }

                $query->with([
                    'industrialProjects' => fn($q) => $q->filterByRequest($request)
                ])
                    ->whereIn('id', $projectIds)
                    ->whereHas('industrialProjects', fn($q) => $q->filterByRequest($request));

                $query->filterProjectOnly($request);
            }
        }

        $projects = $query->whereNull('parent_id')
            ->where('status', 'approved')
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->get();

        $data = $this->returnData($projects);
        $timestamp = date('Ymd_His');

        if ($format === 'csv') {
            return $this->exportCsv($data, "hanoi_gis_projects_{$timestamp}.csv");
        } elseif ($format === 'kml') {
            return $this->exportKml($data, "hanoi_gis_projects_{$timestamp}.kml");
        }

        return $this->exportGeoJson($data, "hanoi_gis_projects_{$timestamp}.geojson");
    }

    private function exportGeoJson($data, $filename)
    {
        $features = [];

        foreach ($data as $item) {
            $lat = (float) $item['lat'];
            $lng = (float) $item['lng'];

            if ($lat == 0.0 && $lng == 0.0) {
                continue;
            }

            $investStatus = 'Chưa xác định';
            $markerColor = '#64748b'; // default slate
            if ($item['is_invest'] == 1) {
                $investStatus = 'Dự án đã có chủ đầu tư';
                $markerColor = '#2563eb'; // blue
            } elseif ($item['is_invest'] == 2) {
                $investStatus = 'Dự án dự kiến đề xuất';
                $markerColor = '#10b981'; // green
            } elseif ($item['is_invest'] == 0 || $item['is_invest'] == 3) {
                $investStatus = 'Dự án kêu gọi đầu tư';
                $markerColor = '#ef4444'; // red
            }

            $districtText = is_array($item['districts']) ? implode(', ', $item['districts']) : ($item['district'] ?? '');

            // Rich HTML balloon popup cho Google Earth / GIS tools
            $descHtml = "<div style='font-family:sans-serif;line-height:1.5;font-size:13px;max-width:320px;'>";
            $descHtml .= "<h3 style='margin:0 0 8px;color:#1e40af;font-size:15px;font-weight:bold;'>" . htmlspecialchars($item['name']) . "</h3>";
            $descHtml .= "<div style='margin-bottom:6px;'><span style='background:" . $markerColor . ";color:#fff;padding:2px 8px;border-radius:12px;font-size:11px;font-weight:600;'>" . htmlspecialchars($investStatus) . "</span></div>";
            $descHtml .= "<p style='margin:4px 0;'><b>Địa bàn:</b> " . htmlspecialchars($districtText ?: 'Chưa cập nhật') . "</p>";
            $descHtml .= "<p style='margin:4px 0;'><b>Lĩnh vực:</b> " . htmlspecialchars($item['industry_name'] ?? 'Chưa cập nhật') . "</p>";
            if (!empty($item['type_name'])) {
                $descHtml .= "<p style='margin:4px 0;'><b>Hình thức:</b> " . htmlspecialchars($item['type_name']) . "</p>";
            }
            if (!empty($item['price'])) {
                $descHtml .= "<p style='margin:4px 0;'><b>Quy mô vốn:</b> " . number_format($item['price'], 0, ',', '.') . " tỷ VNĐ</p>";
            }
            if (!empty($item['area'])) {
                $descHtml .= "<p style='margin:4px 0;'><b>Diện tích:</b> " . htmlspecialchars($item['area']) . " " . htmlspecialchars($item['unit'] ?? 'ha') . "</p>";
            }
            if (!empty($item['occupancy_rate'])) {
                $descHtml .= "<p style='margin:4px 0;'><b>Tỷ lệ lấp đầy:</b> " . htmlspecialchars($item['occupancy_rate']) . "%</p>";
            }
            if (!empty($item['link'])) {
                $descHtml .= "<div style='margin-top:10px;'><a href='" . htmlspecialchars($item['link']) . "' target='_blank' style='display:inline-block;background:#1e40af;color:#fff;text-decoration:none;padding:5px 10px;border-radius:4px;font-size:12px;font-weight:600;'>👉 Xem chi tiết trên Cổng</a></div>";
            }
            if (!empty($item['link_vrtour'])) {
                $descHtml .= "<div style='margin-top:6px;'><a href='" . htmlspecialchars($item['link_vrtour']) . "' target='_blank' style='display:inline-block;background:#059669;color:#fff;text-decoration:none;padding:5px 10px;border-radius:4px;font-size:12px;font-weight:600;'>🌐 Trải nghiệm VR Tour 360</a></div>";
            }
            $descHtml .= "</div>";

            $properties = [
                'id' => $item['id'],
                'name' => $item['name'],
                'title' => $item['name'],
                'description' => $descHtml,
                'marker-color' => $markerColor,
                'marker-size' => 'medium',
                'marker-symbol' => 'building',
                'district' => $districtText,
                'industry' => $item['industry_name'] ?? '',
                'type' => $item['type_name'] ?? '',
                'investment_scale_billion' => $item['price'] !== null ? (float) $item['price'] : null,
                'area' => $item['area'],
                'unit' => $item['unit'] ?? 'ha',
                'invest_status' => $investStatus,
                'occupancy_rate' => $item['occupancy_rate'] ?? 0,
                'detail_url' => $item['link'] ?? '',
                'vrtour_url' => $item['link_vrtour'] ?? '',
            ];

            if (!empty($item['industrial'])) {
                $properties['industrial_products_count'] = count($item['industrial']);
            }

            $features[] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [$lng, $lat],
                ],
                'properties' => $properties,
            ];
        }

        $geoJson = [
            'type' => 'FeatureCollection',
            'name' => 'Hanoi_Investment_Projects',
            'crs' => [
                'type' => 'name',
                'properties' => [
                    'name' => 'urn:ogc:def:crs:OGC:1.3:CRS84'
                ]
            ],
            'features' => $features,
        ];

        return response()->json($geoJson, 200, [
            'Content-Type' => 'application/geo+json; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    private function exportKml($data, $filename)
    {
        $headers = [
            'Content-Type' => 'application/vnd.google-earth.kml+xml; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $kml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $kml .= '<kml xmlns="http://www.opengis.net/kml/2.2">' . "\n";
        $kml .= '  <Document>' . "\n";
        $kml .= '    <name>Dự án xúc tiến đầu tư TP. Hà Nội</name>' . "\n";
        $kml .= '    <description>Dữ liệu không gian vị trí các dự án đầu tư Hà Nội từ Cổng XTTM &amp; ĐT</description>' . "\n\n";

        // Style định dạng ghim đẹp mắt cho Google Earth (đỏ: kêu gọi đầu tư, xanh dương: đã có CĐT, xanh lá: đề xuất)
        $kml .= '    <Style id="style_invest_calling">' . "\n";
        $kml .= '      <IconStyle>' . "\n";
        $kml .= '        <scale>1.2</scale>' . "\n";
        $kml .= '        <Icon>' . "\n";
        $kml .= '          <href>http://maps.google.com/mapfiles/kml/paddle/red-circle.png</href>' . "\n";
        $kml .= '        </Icon>' . "\n";
        $kml .= '      </IconStyle>' . "\n";
        $kml .= '      <LabelStyle><scale>0.85</scale></LabelStyle>' . "\n";
        $kml .= '    </Style>' . "\n\n";

        $kml .= '    <Style id="style_invest_have">' . "\n";
        $kml .= '      <IconStyle>' . "\n";
        $kml .= '        <scale>1.2</scale>' . "\n";
        $kml .= '        <Icon>' . "\n";
        $kml .= '          <href>http://maps.google.com/mapfiles/kml/paddle/blu-circle.png</href>' . "\n";
        $kml .= '        </Icon>' . "\n";
        $kml .= '      </IconStyle>' . "\n";
        $kml .= '      <LabelStyle><scale>0.85</scale></LabelStyle>' . "\n";
        $kml .= '    </Style>' . "\n\n";

        $kml .= '    <Style id="style_invest_proposal">' . "\n";
        $kml .= '      <IconStyle>' . "\n";
        $kml .= '        <scale>1.2</scale>' . "\n";
        $kml .= '        <Icon>' . "\n";
        $kml .= '          <href>http://maps.google.com/mapfiles/kml/paddle/grn-circle.png</href>' . "\n";
        $kml .= '        </Icon>' . "\n";
        $kml .= '      </IconStyle>' . "\n";
        $kml .= '      <LabelStyle><scale>0.85</scale></LabelStyle>' . "\n";
        $kml .= '    </Style>' . "\n\n";

        // Gom nhóm theo trạng thái đầu tư để Google Earth hiển thị cây thư mục gọn gàng
        $groups = [
            'Dự án kêu gọi đầu tư' => [],
            'Dự án đã có chủ đầu tư' => [],
            'Dự án dự kiến đề xuất' => [],
        ];

        foreach ($data as $item) {
            $lat = (float) $item['lat'];
            $lng = (float) $item['lng'];
            if ($lat == 0.0 && $lng == 0.0) {
                continue;
            }

            $statusKey = 'Dự án kêu gọi đầu tư';
            $styleUrl = '#style_invest_calling';

            if ($item['is_invest'] == 1) {
                $statusKey = 'Dự án đã có chủ đầu tư';
                $styleUrl = '#style_invest_have';
            } elseif ($item['is_invest'] == 2) {
                $statusKey = 'Dự án dự kiến đề xuất';
                $styleUrl = '#style_invest_proposal';
            }

            $districtText = is_array($item['districts']) ? implode(', ', $item['districts']) : ($item['district'] ?? '');

            $descHtml = "<div style='font-family:sans-serif;line-height:1.5;font-size:13px;max-width:320px;'>";
            $descHtml .= "<h3 style='margin:0 0 8px;color:#1e40af;font-size:15px;font-weight:bold;'>" . htmlspecialchars($item['name']) . "</h3>";
            $descHtml .= "<div style='margin-bottom:6px;'><b>Trạng thái:</b> " . htmlspecialchars($statusKey) . "</div>";
            $descHtml .= "<p style='margin:4px 0;'><b>Địa bàn:</b> " . htmlspecialchars($districtText ?: 'Chưa cập nhật') . "</p>";
            $descHtml .= "<p style='margin:4px 0;'><b>Lĩnh vực:</b> " . htmlspecialchars($item['industry_name'] ?? 'Chưa cập nhật') . "</p>";
            if (!empty($item['type_name'])) {
                $descHtml .= "<p style='margin:4px 0;'><b>Hình thức:</b> " . htmlspecialchars($item['type_name']) . "</p>";
            }
            if (!empty($item['price'])) {
                $descHtml .= "<p style='margin:4px 0;'><b>Quy mô vốn:</b> " . number_format($item['price'], 0, ',', '.') . " tỷ VNĐ</p>";
            }
            if (!empty($item['area'])) {
                $descHtml .= "<p style='margin:4px 0;'><b>Diện tích:</b> " . htmlspecialchars($item['area']) . " " . htmlspecialchars($item['unit'] ?? 'ha') . "</p>";
            }
            if (!empty($item['occupancy_rate'])) {
                $descHtml .= "<p style='margin:4px 0;'><b>Tỷ lệ lấp đầy:</b> " . htmlspecialchars($item['occupancy_rate']) . "%</p>";
            }
            if (!empty($item['link'])) {
                $descHtml .= "<div style='margin-top:10px;'><a href='" . htmlspecialchars($item['link']) . "' target='_blank' style='display:inline-block;background:#1e40af;color:#fff;text-decoration:none;padding:5px 10px;border-radius:4px;font-size:12px;font-weight:600;'>👉 Xem chi tiết trên Cổng</a></div>";
            }
            if (!empty($item['link_vrtour'])) {
                $descHtml .= "<div style='margin-top:6px;'><a href='" . htmlspecialchars($item['link_vrtour']) . "' target='_blank' style='display:inline-block;background:#059669;color:#fff;text-decoration:none;padding:5px 10px;border-radius:4px;font-size:12px;font-weight:600;'>🌐 Trải nghiệm VR Tour 360</a></div>";
            }
            $descHtml .= "</div>";

            $groups[$statusKey][] = [
                'name' => $item['name'],
                'desc' => $descHtml,
                'styleUrl' => $styleUrl,
                'lng' => $lng,
                'lat' => $lat,
            ];
        }

        foreach ($groups as $groupName => $placemarks) {
            if (empty($placemarks)) {
                continue;
            }
            $kml .= '    <Folder>' . "\n";
            $kml .= '      <name>' . htmlspecialchars($groupName) . ' (' . count($placemarks) . ')</name>' . "\n";
            foreach ($placemarks as $p) {
                $kml .= '      <Placemark>' . "\n";
                $kml .= '        <name>' . htmlspecialchars($p['name']) . '</name>' . "\n";
                $kml .= '        <description><![CDATA[' . $p['desc'] . ']]></description>' . "\n";
                $kml .= '        <styleUrl>' . $p['styleUrl'] . '</styleUrl>' . "\n";
                $kml .= '        <Point>' . "\n";
                $kml .= '          <coordinates>' . $p['lng'] . ',' . $p['lat'] . ',0</coordinates>' . "\n";
                $kml .= '        </Point>' . "\n";
                $kml .= '      </Placemark>' . "\n";
            }
            $kml .= '    </Folder>' . "\n";
        }

        $kml .= '  </Document>' . "\n";
        $kml .= '</kml>';

        return response($kml, 200, $headers);
    }

    private function exportCsv($data, $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        // Sắp xếp cột chuẩn: Vĩ độ (Latitude) trước Kinh độ (Longitude) theo thói quen bản đồ và GPS
        $columns = [
            'ID',
            'Tên dự án',
            'Vĩ độ (Latitude)',
            'Kinh độ (Longitude)',
            'Quận / Huyện',
            'Lĩnh vực / Ngành',
            'Hình thức đầu tư',
            'Quy mô vốn (tỷ VNĐ)',
            'Diện tích',
            'Đơn vị',
            'Trạng thái đầu tư',
            'Tỷ lệ lấp đầy (%)',
            'Link chi tiết',
            'Link VR Tour',
        ];

        $callback = function () use ($data, $columns) {
            $file = fopen('php://output', 'w');
            // Ghi UTF-8 BOM để Excel và QGIS hiển thị đúng tiếng Việt có dấu
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($file, $columns);

            foreach ($data as $item) {
                $investStatus = 'Chưa xác định';
                if ($item['is_invest'] == 1) {
                    $investStatus = 'Dự án đã có chủ đầu tư';
                } elseif ($item['is_invest'] == 2) {
                    $investStatus = 'Dự án dự kiến đề xuất';
                } elseif ($item['is_invest'] == 0 || $item['is_invest'] == 3) {
                    $investStatus = 'Dự án kêu gọi đầu tư';
                }

                $districtText = is_array($item['districts']) ? implode(', ', $item['districts']) : ($item['district'] ?? '');

                fputcsv($file, [
                    $item['id'],
                    $item['name'],
                    $item['lat'],
                    $item['lng'],
                    $districtText,
                    $item['industry_name'] ?? '',
                    $item['type_name'] ?? '',
                    $item['price'] ?? '',
                    $item['area'] ?? '',
                    $item['unit'] ?? 'ha',
                    $investStatus,
                    $item['occupancy_rate'] ?? 0,
                    $item['link'] ?? '',
                    $item['link_vrtour'] ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
