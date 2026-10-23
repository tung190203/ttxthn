@if ($retention)
    <div class="alert {{ $retention['at_cap'] ? 'alert-warning' : 'alert-light border' }} py-2">
        <i class="fas fa-database mr-1"></i>
        Đã lưu <strong>{{ $retention['count'] }}/{{ $retention['max'] }}</strong> phiên bản
        (luôn giữ tối thiểu {{ config('vrtour.min_keep') }} bản gần nhất).
        @if ($retention['at_cap'] && $retention['oldest'])
            <strong>Lần lưu tiếp theo sẽ xóa v{{ $retention['oldest']['number'] }}
                ({{ $retention['oldest']['at'] }}).</strong>
        @endif
    </div>
@endif

<form id="compare-form" method="GET" action="{{ route('backend_vrtour_history_compare') }}" class="mb-2">
    <input type="hidden" name="vrtour_id" value="{{ $selectedVrtour->id }}">
    <input type="hidden" name="a" id="compare-a">
    <input type="hidden" name="b" id="compare-b">
    <button type="submit" id="btn-compare" class="btn btn-primary btn-sm" disabled>
        <i class="fas fa-columns mr-1"></i> So sánh (<span id="compare-count">0</span>/2)
    </button>
</form>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Các phiên bản Panorama đã lưu</h5>
    <span class="badge badge-info">{{ $versions->total() }} phiên bản</span>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="thead-light">
            <tr>
                <th style="width:40px"></th>
                <th style="width:200px">Phiên bản</th>
                <th style="width:200px">Thời gian lưu</th>
                <th>Người thực hiện</th>
                <th style="width:200px">Nội dung</th>
                <th style="width:200px" class="text-center">Số Panorama</th>
                @if ($canRestore)
                    <th style="width:200px" class="text-center">Thao tác</th>
                @endif
            </tr>
        </thead>

        <tbody>
            @forelse ($versions as $version)
                <tr>
                    <td><input type="checkbox" class="chk-compare" value="{{ $version->id }}"></td>
                    <td>
                        <span class="badge badge-primary">v{{ $version->version_number }}</span>
                        @if ($version->version_type === 'restore')
                            <span class="badge badge-info" title="{{ $version->restore_reason }}">Khôi phục</span>
                        @endif
                    </td>
                    <td>{{ $version->created_at ? $version->created_at->format('d/m/Y H:i:s') : '—' }}</td>
                    <td>{{ $version->creator?->name ?: 'Không xác định' }}</td>
                    <td>{{ $version->change_summary ?: 'Lưu phiên bản Panorama' }}</td>
                    <td class="text-center">
                        <span class="text-success">+{{ $version->added_count }}</span>
                        <span class="text-danger">-{{ $version->removed_count }}</span>
                        <span class="text-warning">~{{ $version->modified_count }}</span>
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
                    <td colspan="{{ $canRestore ? 7 : 6 }}" class="text-center py-4">
                        <i class="fas fa-history fa-2x text-muted mb-2"></i>
                        <p class="mb-0 text-muted">
                            Chưa có lịch sử phiên bản Panorama trong khoảng thời gian đã chọn.
                        </p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-end">
    {{ $versions->links() }}
</div>
