@php
    $names = \App\Services\VrtourVersionDiffService::SKIN_NAMES;
    $typeLabel = ['publish' => 'Cập nhật', 'restore' => 'Khôi phục'];
    $actClass = ['added' => 'success', 'removed' => 'danger', 'modified' => 'warning'];
@endphp

@if ($retention)
    <div class="alert {{ $retention['at_cap'] ? 'alert-warning' : 'alert-light border' }} py-2">
        <i class="fas fa-database mr-1"></i>
        Đã lưu <strong>{{ $retention['count'] }}/{{ $retention['max'] }}</strong> phiên bản Skin
        (luôn giữ tối thiểu {{ config('vrtour.min_keep') }} bản gần nhất).
        @if ($retention['at_cap'] && $retention['oldest'])
            <strong>Lần lưu tiếp theo sẽ xóa v{{ $retention['oldest']['number'] }}
                ({{ $retention['oldest']['at'] }}).</strong>
        @endif
    </div>
@endif

<form id="compare-form" method="GET" action="{{ route('backend_vrtour_history_compare') }}" class="mb-2">
    <input type="hidden" name="vrtour_id" value="{{ $selectedVrtour->id }}">
    <input type="hidden" name="scope" value="skin">
    <input type="hidden" name="a" id="compare-a">
    <input type="hidden" name="b" id="compare-b">
    <button type="submit" id="btn-compare" class="btn btn-primary btn-sm" disabled>
        <i class="fas fa-columns mr-1"></i> So sánh (<span id="compare-count">0</span>/2)
    </button>
</form>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Các phiên bản Skin đã lưu</h5>
    <span class="badge badge-info">{{ $versions->total() }} phiên bản</span>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="thead-light">
            <tr>
                <th style="width:40px"></th>
                <th style="width:100px">Phiên bản</th>
                <th style="width:160px">Thời gian lưu</th>
                <th style="width:160px">Người thực hiện</th>
                <th>Nội dung</th>
                <th style="width:110px" class="text-center">Phần đã đổi</th>
                <th>Các phần Skin đã đổi</th>
                @if ($canRestore)
                    <th style="width:130px" class="text-center">Thao tác</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($versions as $version)
                <tr>
                    <td><input type="checkbox" class="chk-compare" value="{{ $version->id }}"></td>
                    <td>
                        <span class="badge badge-primary">v{{ $version->version_number }}</span>
                        @if ($version->version_type !== 'publish')
                            <span class="badge badge-info" title="{{ $version->restore_reason }}">
                                {{ $typeLabel[$version->version_type] ?? $version->version_type }}
                            </span>
                        @endif
                    </td>
                    <td>{{ $version->created_at?->format('d/m/Y H:i:s') ?? '—' }}</td>
                    <td>{{ $version->creator?->name ?: 'Không xác định' }}</td>
                    <td>{{ $version->change_summary ?: 'Cập nhật Skin' }}</td>
                    <td class="text-center">
                        <span class="badge badge-secondary">{{ $version->item_count }}</span><br>
                        <small>
                            <span class="text-success">+{{ $version->added_count }}</span>
                            <span class="text-danger">-{{ $version->removed_count }}</span>
                            <span class="text-warning">~{{ $version->modified_count }}</span>
                        </small>
                    </td>
                    <td>
                        @foreach ($version->items as $it)
                            <span class="badge badge-{{ $actClass[$it->action] ?? 'secondary' }}">
                                {{ $names[$it->entity_type] ?? $it->entity_type }}
                            </span>
                        @endforeach
                    </td>
                    @if ($canRestore)
                        <td class="text-center">
                            <button type="button" class="btn btn-outline-warning btn-sm btn-restore"
                                data-id="{{ $version->id }}" data-number="{{ $version->version_number }}">
                                <i class="fas fa-undo mr-1"></i> Khôi phục
                            </button>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <i class="fas fa-history fa-2x text-muted mb-2"></i>
                        <p class="mb-0 text-muted">Chưa có lịch sử phiên bản Skin trong khoảng thời gian đã chọn.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-end">
    {{ $versions->links() }}
</div>
