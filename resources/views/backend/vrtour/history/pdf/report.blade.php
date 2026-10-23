<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #222; }
        h1 { font-size: 16px; text-align: center; margin: 0 0 4px; }
        .sub { text-align: center; color: #555; margin-bottom: 10px; }
        .meta td { padding: 2px 6px 2px 0; vertical-align: top; }
        table.list { width: 100%; border-collapse: collapse; margin-top: 8px; }
        table.list th, table.list td { border: 1px solid #888; padding: 4px 5px; vertical-align: top; }
        table.list th { background: #e9ecef; text-align: left; }
        .center { text-align: center; }
        .add { color: #05621d; } .del { color: #a10000; } .mod { color: #8a6d00; }
        .sign { margin-top: 30px; width: 100%; }
        .sign td { width: 50%; text-align: center; }
        tr { page-break-inside: avoid; }
    </style>
</head>
<body>
    <h1>BÁO CÁO LỊCH SỬ PHIÊN BẢN VRTOUR</h1>
    <div class="sub">Tab: {{ $tabLabel }}</div>

    <table class="meta">
        <tr><td><strong>Dự án:</strong></td><td>{{ $project->name }}</td></tr>
        <tr>
            <td><strong>Khoảng thời gian:</strong></td>
            <td>
                @if ($from || $to)
                    {{ $from ? \Carbon\Carbon::parse($from)->format('d/m/Y') : '...' }}
                    - {{ $to ? \Carbon\Carbon::parse($to)->format('d/m/Y') : '...' }}
                @else
                    Tất cả
                @endif
            </td>
        </tr>
        <tr><td><strong>Số phiên bản trong báo cáo:</strong></td><td>{{ $versions->count() }}</td></tr>
        <tr><td><strong>Lưu trữ hiện tại:</strong></td>
            <td>{{ $retention['count'] }}/{{ $retention['max'] }} phiên bản (giữ tối thiểu {{ config('vrtour.min_keep') }} bản gần nhất)</td></tr>
        <tr><td><strong>Người xuất:</strong></td><td>{{ $exporter->name }}</td></tr>
        <tr><td><strong>Thời điểm xuất:</strong></td><td>{{ $exportedAt->format('d/m/Y H:i:s') }}</td></tr>
    </table>

    @include('backend.vrtour.history.pdf.tabs.' . $tab)
</body>
</html>