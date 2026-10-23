<form id="version-filter-form" method="GET" action="{{ route('backend_vrtour_history_index') }}" class="mb-4">
    <input type="hidden" name="slt_vrtour" value="{{ $selectedVrtour->id }}">
    <input type="hidden" name="tab" value="{{ $tab }}">

    <div class="form-row align-items-end">
        <div class="form-group col-md-3">
            <label for="from_date">Từ ngày</label>
            <input type="date" id="from_date" name="from_date" class="form-control"
                value="{{ request('from_date') }}">
        </div>

        <div class="form-group col-md-3">
            <label for="to_date">Đến ngày</label>
            <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request('to_date') }}">
        </div>

        <div class="form-group col-md-6">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search mr-1"></i> Lọc lịch sử
            </button>
            <a href="{{ route('backend_vrtour_history_index', ['slt_vrtour' => $selectedVrtour->id, 'tab' => $tab]) }}"
                class="btn btn-outline-secondary">
                <i class="fas fa-redo mr-1"></i> Đặt lại bộ lọc
            </a>
            @if (in_array($tab, ['panorama', 'hotspot']))
                <button type="submit" formaction="{{ route('backend_vrtour_history_export_pdf') }}"
                    class="btn btn-danger">
                    <i class="fas fa-file-pdf mr-1"></i> Xuất PDF
                </button>
            @endif
        </div>
    </div>
</form>
