@extends('frontend.index')

@php
if (!function_exists('formatCriteriaDetail')) {
    function formatCriteriaDetail($text) {
        if (!$text) return 'Không có thông tin chi tiết';
        
        // Handle JSON array
        if (is_string($text) && str_starts_with(trim($text), '[')) {
            $decoded = json_decode($text, true);
            if (is_array($decoded)) {
                return '<ul class="ps-3 mb-0"><li>' . implode('</li><li class="mt-2">', $decoded) . '</li></ul>';
            }
        }
        
        // Handle Google Maps iframe
        if (is_string($text) && str_starts_with(trim($text), 'http') && str_contains($text, 'google.com/maps')) {
            return '<div class="ratio ratio-16x9"><iframe src="' . $text . '" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div>';
        }
        
        // Handle other URLs
        if (filter_var($text, FILTER_VALIDATE_URL)) {
            return '<a href="' . $text . '" target="_blank" class="text-primary text-break">' . $text . '</a>';
        }
        
        return nl2br($text);
    }
}
@endphp

@section('content')
<div class="page__content bg-light" style="min-height: 100vh; padding-top: 120px; padding-bottom: 50px;">
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-uppercase mb-1"><i class="fal fa-balance-scale me-2"></i>{{ __('app.compare_parallel_projects') }}</h3>
            </div>
            <div>
                <a href="{{ route('guest.dashboard') }}#project-interest" class="btn btn-outline-secondary me-2"><i class="fal fa-arrow-left me-1"></i>{{ __('app.back_to_dashboard') }}</a>
                <a href="{{ route('guest.compare.export_pdf', $session->id) }}" class="btn btn-success"><i class="fal fa-file-pdf me-1"></i>{{ __('app.export_pdf') }}</a>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <input type="text" id="session-name" class="form-control form-control-sm fw-bold border-0 bg-light" value="{{ $session->name }}" style="width: 300px;">
                    <button class="btn btn-sm btn-link text-decoration-none ms-2" onclick="saveNotes(this)"><i class="fal fa-save"></i> {{ __('app.save_session_name') }}</button>
                </div>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-bordered mb-0" id="compare-table" style="table-layout: fixed; min-width: 1000px;">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 20%;" class="text-primary fw-bold text-uppercase align-middle">{{ __('app.standard_criteria_7') }} <br><button class="btn btn-sm btn-outline-primary mt-2" onclick="showAllCriteria()" id="btn-show-all" style="display:none;"><i class="fal fa-eye"></i> {{ __('app.show_all') }}</button></th>
                            @foreach($projects as $p)
                            <th class="p-3 text-center align-middle" style="width: {{ 80 / count($projects) }}%;">
                                <h5 class="fw-bold mb-1"><a href="{{ route('project_detail', ['slug' => $p->slug ?? 'untitled']) }}" target="_blank" class="text-dark">{{ $p->name }}</a></h5>
                                <small class="text-secondary">{{ $p->districts->pluck('name')->join(', ') }}</small>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <!-- TIÊU CHÍ 1: {{ __('app.criteria_1') }} -->
                        <tr>
                            <td class="fw-bold bg-light align-middle text-secondary">
                                <i class="fal fa-map-marker-alt me-2"></i>1. {{ __('app.criteria_1') }}
                            <button class="btn btn-sm text-muted float-end" title="Ẩn tiêu chí này" onclick="hideCriteria(this)"><i class="fal fa-eye-slash"></i></button></td>
                            @foreach($projects as $p)
                            <td class="p-3 cursor-pointer" onclick="showCriteriaDetail('{{ __('app.criteria_1') }}', '{{ rawurlencode($p->name) }}', '{{ rawurlencode(formatCriteriaDetail($p->location_in_tour)) }}', '')">{{ $p->districts->pluck('name')->join(', ') }} <i class="fal fa-info-circle text-muted ms-1"></i></td>
                            @endforeach
                        </tr>

                        <!-- TIÊU CHÍ 2: {{ __('app.criteria_2') }} -->
                        <tr>
                            <td class="fw-bold bg-light align-middle text-secondary">
                                <i class="fal fa-expand-arrows me-2"></i>2. {{ __('app.criteria_2') }}
                            <button class="btn btn-sm text-muted float-end" title="Ẩn tiêu chí này" onclick="hideCriteria(this)"><i class="fal fa-eye-slash"></i></button></td>
                            @foreach($projects as $p)
                            <td class="p-3 area-cell cursor-pointer" data-val="{{ $p->area ?? 0 }}" onclick="showCriteriaDetail('{{ __('app.criteria_2') }}', '{{ rawurlencode($p->name) }}', '{{ rawurlencode(formatCriteriaDetail($p->description)) }}', '')">
        {{ $p->area ? formatDecimalByLocale($p->area) . ' ' . ($p->unit_type_text ?? '') : __('app.updating') }} <i class="fal fa-info-circle text-muted ms-1"></i>
    </td>
                            @endforeach
                        </tr>

                        <!-- TIÊU CHÍ 3: {{ __('app.criteria_3') }} -->
                        <tr>
                            <td class="fw-bold bg-light align-middle text-secondary">
                                <i class="fal fa-file-contract me-2"></i>3. {{ __('app.criteria_3') }}
                            <button class="btn btn-sm text-muted float-end" title="Ẩn tiêu chí này" onclick="hideCriteria(this)"><i class="fal fa-eye-slash"></i></button></td>
                            @foreach($projects as $p)
                            <td class="p-3 cursor-pointer" onclick="showCriteriaDetail('{{ __('app.criteria_3') }}', '{{ rawurlencode($p->name) }}', '{{ rawurlencode(formatCriteriaDetail($p->legal_description)) }}', '{{ $p->legal_file ? $p->legal_file : '' }}')">{{ $p->legal_short_desc ?: __('app.updating') }} <i class="fal fa-info-circle text-muted ms-1"></i></td>
                            @endforeach
                        </tr>

                        <!-- TIÊU CHÍ 4: {{ __('app.criteria_4') }} -->
                        <tr>
                            <td class="fw-bold bg-light align-middle text-secondary">
                                <i class="fal fa-money-bill me-2"></i>4. {{ __('app.criteria_4') }}
                            <button class="btn btn-sm text-muted float-end" title="Ẩn tiêu chí này" onclick="hideCriteria(this)"><i class="fal fa-eye-slash"></i></button></td>
                            @foreach($projects as $p)
                            <td class="p-3 price-cell cursor-pointer" data-val="{{ $p->price ?? 0 }}" onclick="showCriteriaDetail('{{ __('app.criteria_4') }}', '{{ rawurlencode($p->name) }}', '{{ rawurlencode(formatCriteriaDetail($p->estimated_cost)) }}', '')">
        {{ $p->price ? formatDecimalByLocale($p->price, null, 0) . ' ' . __('app.billion_vnd') : __('app.updating') }} <i class="fal fa-info-circle text-muted ms-1"></i>
    </td>
                            @endforeach
                        </tr>

                        <!-- TIÊU CHÍ 5: Ngành nghề ưu tiên -->
                        <tr>
                            <td class="fw-bold bg-light align-middle text-secondary">
                                <i class="fal fa-industry me-2"></i>5. {{ __('app.criteria_5') }}
                            <button class="btn btn-sm text-muted float-end" title="Ẩn tiêu chí này" onclick="hideCriteria(this)"><i class="fal fa-eye-slash"></i></button></td>
                            @foreach($projects as $p)
                            <td class="p-3">{{ $p->industry->name ?? __('app.updating') }}</td>
                            @endforeach
                        </tr>

                        <!-- TIÊU CHÍ 6: Tỷ lệ lấp đầy -->
                        <tr>
                            <td class="fw-bold bg-light align-middle text-secondary">
                                <i class="fal fa-chart-pie me-2"></i>6. {{ __('app.criteria_6') }}
                            <button class="btn btn-sm text-muted float-end" title="Ẩn tiêu chí này" onclick="hideCriteria(this)"><i class="fal fa-eye-slash"></i></button></td>
                            @foreach($projects as $p)
                            <td class="p-3 occ-cell" data-val="{{ $p->has_occupancy_rate ? $p->occupancy_rate : 0 }}">
                                {{ $p->has_occupancy_rate && $p->occupancy_rate !== null ? $p->occupancy_rate . '%' : __('app.updating') }}
                            </td>
                            @endforeach
                        </tr>

                        <!-- TIÊU CHÍ 7: Loại dự án -->
                        <tr>
                            <td class="fw-bold bg-light align-middle text-secondary">
                                <i class="fal fa-building me-2"></i>7. {{ __('app.criteria_7') }}
                            <button class="btn btn-sm text-muted float-end" title="Ẩn tiêu chí này" onclick="hideCriteria(this)"><i class="fal fa-eye-slash"></i></button></td>
                            @foreach($projects as $p)
                            <td class="p-3">{{ $p->type->name ?? __('app.updating') }}</td>
                            @endforeach
                        </tr>
                        
                        <!-- GHI CHÚ CÁ NHÂN -->
                        <tr class="border-top border-2 border-primary">
                            <td class="fw-bold bg-light align-middle text-primary">
                                <i class="fal fa-pen me-2"></i>{{ __('app.my_notes') }}
                            </td>
                            @foreach($projects as $p)
                            <td class="p-2">
                                @php $noteText = isset($notes[$p->id]['general']) ? $notes[$p->id]['general']->note : ''; @endphp
                                <textarea class="form-control border-0 bg-light compare-note-input" 
                                          rows="3" 
                                          placeholder="{{ __('app.enter_personal_notes') }}" 
                                          data-project="{{ $p->id }}" 
                                          data-key="general">{{ $noteText }}</textarea>
                            </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
                <div class="p-3 bg-light text-end">
                    <button class="btn btn-primary px-4 text-white" onclick="saveNotes(this)"><i class="fal fa-save me-2"></i>{{ __('app.save_compare_session') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Chi Tiết Tiêu Chí -->
<div class="modal fade" id="criteriaDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-primary" id="modalCriteriaTitle">{{ __('app.criteria_detail') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 class="fw-bold text-dark mb-2" id="modalProjectName"></h6>
                <div id="modalCriteriaContent" class="text-secondary mb-3" style="word-break: break-word;"></div>
                <div id="modalAttachment" style="display:none;" class="mt-3">
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
        highlightBestWorst('.price-cell', true); // Price/Capital: lower is better
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

    function saveNotes(btn) {
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
                Swal.fire('Thành công', 'Đã lưu thành công phiên so sánh!', 'success');
            } else {
                Swal.fire('Thất bại', 'Có lỗi xảy ra, vui lòng thử lại sau.', 'error');
            }
        })
        .catch(err => {
            Swal.fire('Lỗi mạng', 'Không thể kết nối máy chủ!', 'error');
        })
        .finally(() => {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        });
    }
    function hideCriteria(btn) {
        btn.closest('tr').style.display = 'none';
        document.getElementById('btn-show-all').style.display = 'inline-block';
    }

    function showAllCriteria() {
        document.querySelectorAll('#compare-table tbody tr').forEach(tr => {
            tr.style.display = '';
        });
        document.getElementById('btn-show-all').style.display = 'none';
    }

        function showCriteriaDetail(title, projectName, content, attachmentUrl) {
        document.getElementById('modalCriteriaTitle').innerText = title;
        document.getElementById('modalProjectName').innerText = decodeURIComponent(projectName);
        document.getElementById('modalCriteriaContent').innerHTML = content ? decodeURIComponent(content) : 'Không có thông tin chi tiết.';
        
        const attachDiv = document.getElementById('modalAttachment');
        attachDiv.innerHTML = ''; // Clear previous
        if (attachmentUrl) {
            let files = attachmentUrl.split(';');
            files.forEach((file, index) => {
                if (file.trim() === '') return;
                // Add base URL if it's a relative path starting with /
                let href = file.trim();
                if (href.startsWith('/')) {
                    href = window.location.origin + href;
                }
                attachDiv.innerHTML += '<a href="' + href + '" class="btn btn-sm btn-outline-success me-2 mb-2" target="_blank"><i class="fal fa-download me-1"></i>{{ __('app.download_doc') }} ' + (index + 1) + '</a>';
            });
            attachDiv.style.display = 'block';
        } else {
            attachDiv.style.display = 'none';
        }
        
        new bootstrap.Modal(document.getElementById('criteriaDetailModal')).show();
    }
</script>
<style>.cursor-pointer { cursor: pointer; transition: background-color 0.2s; } .cursor-pointer:hover { background-color: rgba(0,0,0,0.03); }</style>
@endpush
