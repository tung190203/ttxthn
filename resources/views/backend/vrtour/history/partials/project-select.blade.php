<div class="card">
    <div class="card-body">
        <div class="mb-3">
            <h4 class="mb-1"><i class="fas fa-history mr-2"></i> Lịch sử phiên bản VRTour</h4>
            <p class="text-muted mb-0">Chọn dự án để xem lịch sử thay đổi.</p>
        </div>

        <form id="version-project-form" method="GET" action="{{ route('backend_vrtour_history_index') }}"
            class="form-filter-top-index d-flex flex-wrap">

            <div class="mr-2" style="min-width:320px">
                <x-forms.select2 name="slt_vrtour" id="slt_vrtour" :options="$vrtour"
                    placeholder="-- Chọn dự án --" :selected="request('slt_vrtour', request('vrtour_id'))" />
            </div>

            <button type="submit" id="btn-view-version-history" class="btn btn-primary btn-sm mr-2"
                style="height: 38px; white-space: nowrap;" {{ $selectedVrtour ? '' : 'disabled' }}>
                <i class="fas fa-search mr-1"></i> Xem lịch sử
            </button>

            @if ($selectedVrtour)
                <a href="{{ route('backend_vrtour_history_index') }}"
                    class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center"
                    style="height: 38px; white-space: nowrap;">
                    <i class="fas fa-redo mr-1"></i> Bỏ chọn
                </a>
            @endif
        </form>
    </div>
</div>