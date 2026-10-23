@php
    $actionLabel = ['added' => 'Thêm', 'removed' => 'Xóa', 'modified' => 'Sửa'];
    $actionClass = ['added' => 'add', 'removed' => 'del', 'modified' => 'mod'];
@endphp

<table class="list">
    <thead>
        <tr>
            <th style="width:5%">Bản</th>
            <th style="width:11%">Thời gian</th>
            <th style="width:8%">Loại</th>
            <th style="width:11%">Người thực hiện</th>
            <th style="width:7%" class="center">Số lô đổi</th>
            <th>Chi tiết thay đổi</th>
            <th style="width:15%">Lý do khôi phục</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($versions as $v)
            <tr>
                <td class="center">v{{ $v->version_number }}</td>
                <td>{{ $v->created_at?->format('d/m/Y H:i:s') ?? '—' }}</td>
                <td>{{ match ($v->version_type) { 'restore' => 'Khôi phục', 'reset' => 'Reset', default => 'Cập nhật' } }}</td>
                <td>{{ $v->creator?->name ?: 'Không xác định' }}</td>
                <td class="center">
                    {{ $v->item_count }}<br>
                    <span class="add">+{{ $v->added_count }}</span>
                    <span class="del">-{{ $v->removed_count }}</span>
                    <span class="mod">~{{ $v->modified_count }}</span>
                </td>
                <td>
                    @foreach (array_slice($v->details, 0, 30) as $d)
                        <div style="margin-bottom:3px">
                            <span class="{{ $actionClass[$d['action']] }}">[{{ $actionLabel[$d['action']] }}]</span>
                            <strong>{{ $d['title'] }}</strong>
                            @foreach ($d['fields'] as $f)
                                <div style="margin-left:12px">{{ $f }}</div>
                            @endforeach
                        </div>
                    @endforeach
                    @if (count($v->details) > 30)
                        <div><em>... và {{ count($v->details) - 30 }} lô khác</em></div>
                    @endif
                    @if (empty($v->details)) — @endif
                </td>
                <td>
                    @if ($v->version_type === 'restore')
                        {{ $v->restore_reason }}
                        @if ($v->sourceVersion)
                            <br><em>(từ v{{ $v->sourceVersion->version_number }})</em>
                        @endif
                        @if ($v->confirmed_at)
                            <br><small>Xác nhận: {{ $v->confirmed_at->format('d/m/Y H:i') }}</small>
                        @endif
                    @else
                        —
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>