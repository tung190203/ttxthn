<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>So sánh dự án đầu tư</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
        }
        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .text-success { color: green; }
        .text-danger { color: red; }
    </style>
</head>
<body>
    <h1>BẢNG SO SÁNH DỰ ÁN ĐẦU TƯ</h1>
    <p><strong>Ngày xuất:</strong> {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th style="width: 20%">Tiêu chí</th>
                @foreach($projects as $project)
                <th>{{ $project->name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Lĩnh vực / Ngành nghề</strong></td>
                @foreach($projects as $project)
                <td>{{ $project->industry->name ?? 'Đang cập nhật' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Loại dự án</strong></td>
                @foreach($projects as $project)
                <td>{{ $project->type->name ?? 'Đang cập nhật' }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Địa điểm</strong></td>
                @foreach($projects as $project)
                <td>
                    @if($project->districts->count() > 0)
                        {{ implode(', ', $project->districts->pluck('name')->toArray()) }}
                    @else
                        Đang cập nhật
                    @endif
                </td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Tổng vốn đầu tư</strong></td>
                @foreach($projects as $project)
                <td>
                    @if($project->investment_capital)
                        {{ number_format($project->investment_capital, 0, ',', '.') }} tỷ VNĐ
                    @else
                        Thỏa thuận
                    @endif
                    
                    @if(!empty($notes[$project->id]['investment_capital']))
                        <div style="margin-top:5px; font-size:11px; font-style:italic;">Ghi chú: {{ $notes[$project->id]['investment_capital'] }}</div>
                    @endif
                </td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Hình thức đầu tư</strong></td>
                @foreach($projects as $project)
                <td>
                    {{ $project->investment_form ?? 'Đang cập nhật' }}
                    @if(!empty($notes[$project->id]['investment_form']))
                        <div style="margin-top:5px; font-size:11px; font-style:italic;">Ghi chú: {{ $notes[$project->id]['investment_form'] }}</div>
                    @endif
                </td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Tỷ lệ lấp đầy</strong></td>
                @foreach($projects as $project)
                <td>
                    @if($project->has_occupancy_rate)
                        {{ $project->occupancy_rate ?? 0 }}%
                    @else
                        Không có
                    @endif
                    @if(!empty($notes[$project->id]['occupancy_rate']))
                        <div style="margin-top:5px; font-size:11px; font-style:italic;">Ghi chú: {{ $notes[$project->id]['occupancy_rate'] }}</div>
                    @endif
                </td>
                @endforeach
            </tr>
            <tr>
                <td><strong>Nhu cầu sử dụng đất</strong></td>
                @foreach($projects as $project)
                <td>
                    @if($project->land_area)
                        {{ number_format($project->land_area, 2, ',', '.') }} ha
                    @else
                        Đang cập nhật
                    @endif
                    @if(!empty($notes[$project->id]['land_area']))
                        <div style="margin-top:5px; font-size:11px; font-style:italic;">Ghi chú: {{ $notes[$project->id]['land_area'] }}</div>
                    @endif
                </td>
                @endforeach
            </tr>
        </tbody>
    </table>
</body>
</html>
