@extends('frontend.index')

@section('content')
<div class="page__content bg-light" style="min-height: 100vh; padding-top: 120px; padding-bottom: 50px;">
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-uppercase mb-1"><i class="fal fa-balance-scale me-2"></i>So sánh Dự án Song song</h3>
                <p class="text-secondary mb-0">Module h-03 - 7 tiêu chí chuẩn • Đang so sánh {{ count($projects) }} dự án • NĐT có thể ghi chú theo từng ô</p>
            </div>
            <div>
                <a href="{{ route('guest.dashboard') }}#project-interest" class="btn btn-outline-secondary me-2"><i class="fal fa-arrow-left me-1"></i>Trở lại Bảng điều khiển</a>
                <a href="{{ route('guest.compare.export_pdf', $session->id) }}" class="btn btn-success"><i class="fal fa-file-pdf me-1"></i>Xuất PDF</a>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <input type="text" id="session-name" class="form-control form-control-sm fw-bold border-0 bg-light" value="{{ $session->name }}" style="width: 300px;">
                    <button class="btn btn-sm btn-link text-decoration-none ms-2" onclick="saveNotes()"><i class="fal fa-save"></i> Lưu tên phiên</button>
                </div>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-bordered mb-0" id="compare-table" style="table-layout: fixed; min-width: 1000px;">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 20%;" class="text-primary fw-bold text-uppercase align-middle">7 Tiêu chí chuẩn</th>
                            @foreach($projects as $p)
                            <th class="p-3 text-center align-middle" style="width: {{ 80 / count($projects) }}%;">
                                <h5 class="fw-bold mb-1"><a href="{{ route('project_detail', ['slug' => $p->slug ?? 'untitled']) }}" target="_blank" class="text-dark">{{ $p->name }}</a></h5>
                                <small class="text-secondary">{{ $p->districts->pluck('name')->join(', ') }}</small>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <!-- TIÊU CHÍ 1: Địa bàn & kết nối vùng -->
                        <tr>
                            <td class="fw-bold bg-light align-middle text-secondary">
                                <i class="fal fa-map-marker-alt me-2"></i>1. Địa bàn & kết nối vùng
                            </td>
                            @foreach($projects as $p)
                            <td class="p-3">{{ $p->districts->pluck('name')->join(', ') }}</td>
                            @endforeach
                        </tr>

                        <!-- TIÊU CHÍ 2: Quy mô diện tích -->
                        <tr>
                            <td class="fw-bold bg-light align-middle text-secondary">
                                <i class="fal fa-expand-arrows me-2"></i>2. Quy mô diện tích
                            </td>
                            @foreach($projects as $p)
                            <td class="p-3 area-cell" data-val="{{ $p->area ?? 0 }}">
                                {{ $p->area ? formatDecimalByLocale($p->area) . ' ' . ($p->unit_type_text ?? '') : 'Đang cập nhật' }}
                            </td>
                            @endforeach
                        </tr>

                        <!-- TIÊU CHÍ 3: Tính chất pháp lý -->
                        <tr>
                            <td class="fw-bold bg-light align-middle text-secondary">
                                <i class="fal fa-file-contract me-2"></i>3. Tính chất pháp lý
                            </td>
                            @foreach($projects as $p)
                            <td class="p-3">{{ $p->legal_short_desc ?: 'Đang cập nhật' }}</td>
                            @endforeach
                        </tr>

                        <!-- TIÊU CHÍ 4: Tổng vốn đầu tư -->
                        <tr>
                            <td class="fw-bold bg-light align-middle text-secondary">
                                <i class="fal fa-money-bill me-2"></i>4. Tổng vốn đầu tư
                            </td>
                            @foreach($projects as $p)
                            <td class="p-3 price-cell" data-val="{{ $p->price ?? 0 }}">
                                {{ $p->price ? number_format($p->price, 0, ',', '.') . ' tỷ VNĐ' : 'Đang cập nhật' }}
                            </td>
                            @endforeach
                        </tr>

                        <!-- TIÊU CHÍ 5: Ngành nghề ưu tiên -->
                        <tr>
                            <td class="fw-bold bg-light align-middle text-secondary">
                                <i class="fal fa-industry me-2"></i>5. Ngành nghề ưu tiên
                            </td>
                            @foreach($projects as $p)
                            <td class="p-3">{{ $p->industry->name ?? 'Đang cập nhật' }}</td>
                            @endforeach
                        </tr>

                        <!-- TIÊU CHÍ 6: Tỷ lệ lấp đầy -->
                        <tr>
                            <td class="fw-bold bg-light align-middle text-secondary">
                                <i class="fal fa-chart-pie me-2"></i>6. Tỷ lệ lấp đầy
                            </td>
                            @foreach($projects as $p)
                            <td class="p-3 occ-cell" data-val="{{ $p->has_occupancy_rate ? $p->occupancy_rate : 0 }}">
                                {{ $p->has_occupancy_rate && $p->occupancy_rate !== null ? $p->occupancy_rate . '%' : 'Đang cập nhật' }}
                            </td>
                            @endforeach
                        </tr>

                        <!-- TIÊU CHÍ 7: Loại dự án -->
                        <tr>
                            <td class="fw-bold bg-light align-middle text-secondary">
                                <i class="fal fa-building me-2"></i>7. Loại dự án
                            </td>
                            @foreach($projects as $p)
                            <td class="p-3">{{ $p->type->name ?? 'Đang cập nhật' }}</td>
                            @endforeach
                        </tr>
                        
                        <!-- GHI CHÚ CÁ NHÂN -->
                        <tr class="border-top border-2 border-primary">
                            <td class="fw-bold bg-light align-middle text-primary">
                                <i class="fal fa-pen me-2"></i>Ghi chú của tôi
                            </td>
                            @foreach($projects as $p)
                            <td class="p-2">
                                @php $noteText = isset($notes[$p->id]['general']) ? $notes[$p->id]['general']->note : ''; @endphp
                                <textarea class="form-control border-0 bg-light compare-note-input" 
                                          rows="3" 
                                          placeholder="Nhập ghi chú cá nhân..." 
                                          data-project="{{ $p->id }}" 
                                          data-key="general">{{ $noteText }}</textarea>
                            </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
                <div class="p-3 bg-light text-end">
                    <button class="btn btn-primary px-4" onclick="saveNotes()"><i class="fal fa-save me-2"></i>Lưu phiên so sánh</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('bottom')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        highlightBestWorst('.area-cell', true); // Area: higher is better
        highlightBestWorst('.occ-cell', true);  // Occupancy: higher is better
        highlightBestWorst('.price-cell', false); // Price/Capital: lower is better
    });

    function highlightBestWorst(selector, higherIsBetter) {
        const cells = document.querySelectorAll(selector);
        let values = [];
        let validCells = [];
        
        cells.forEach(cell => {
            const val = parseFloat(cell.getAttribute('data-val'));
            if (val > 0) {
                values.push(val);
                validCells.push(cell);
            }
        });

        if (values.length < 2) return;

        const max = Math.max(...values);
        const min = Math.min(...values);

        validCells.forEach(cell => {
            const val = parseFloat(cell.getAttribute('data-val'));
            if (val === max) {
                if(higherIsBetter) cell.classList.add('bg-success', 'text-white', 'fw-bold');
                else cell.classList.add('bg-danger', 'text-white', 'fw-bold');
            }
            if (val === min) {
                if(!higherIsBetter) cell.classList.add('bg-success', 'text-white', 'fw-bold');
                else cell.classList.add('bg-danger', 'text-white', 'fw-bold');
            }
        });
    }

    function saveNotes() {
        const btn = event.currentTarget;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang lưu...';
        btn.disabled = true;

        const name = document.getElementById('session-name').value;
        const notes = {};

        document.querySelectorAll('.compare-note-input').forEach(input => {
            const projectId = input.getAttribute('data-project');
            const key = input.getAttribute('data-key');
            if (!notes[projectId]) notes[projectId] = {};
            notes[projectId][key] = input.value;
        });

        fetch('{{ route('guest.compare.notes.save', $session->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name: name, notes: notes })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                toastr.success('Đã lưu thành công phiên so sánh!');
            } else {
                toastr.error('Có lỗi xảy ra!');
            }
        })
        .catch(err => {
            toastr.error('Không thể kết nối máy chủ!');
        })
        .finally(() => {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        });
    }
</script>
@endpush
