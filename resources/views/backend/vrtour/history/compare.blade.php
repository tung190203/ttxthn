@extends('backend.index')

@section('title')
    So sánh phiên bản VRTour
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ $backUrl }}">Lịch sử phiên bản</a></li>
    <li class="breadcrumb-item active">So sánh</li>
@endsection

@section('content')
    <style>
        .diff-del {
            background: #ffc9c9;
            color: #a10000;
            text-decoration: line-through;
            padding: 0 1px;
            border-radius: 2px;
        }

        .diff-ins {
            background: #b7f0c1;
            color: #05621d;
            text-decoration: none;
            padding: 0 1px;
            border-radius: 2px;
        }

        .diff-text {
            white-space: pre-line;
            word-break: break-word;
        }
    </style>

    @php
        $statusMeta = [
            'added' => ['label' => 'Thêm', 'badge' => 'success'],
            'removed' => ['label' => 'Xóa', 'badge' => 'danger'],
            'modified' => ['label' => 'Sửa', 'badge' => 'warning'],
            'unchanged' => ['label' => 'Không đổi', 'badge' => 'secondary'],
        ];

        $tabs = [
            'changed' => [
                'label' => 'Thay đổi',
                'count' => $summary['added'] + $summary['removed'] + $summary['modified'],
            ],
            'added' => ['label' => 'Thêm', 'count' => $summary['added']],
            'removed' => ['label' => 'Xóa', 'count' => $summary['removed']],
            'modified' => ['label' => 'Sửa', 'count' => $summary['modified']],
            'unchanged' => ['label' => 'Không đổi', 'count' => $summary['unchanged']],
        ];
    @endphp

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">So sánh phiên bản
                            {{ ['hotspot' => 'Hotspot', 'skin' => 'Skin'][$scope] ?? 'Panorama' }}:
                            {{ $selectedVrtour->name }}</h5>
                        <a href="{{ $backUrl }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Quay lại
                        </a>
                    </div>

                    {{-- Thông tin 2 phiên bản --}}
                    <div class="row mb-3">
                        @foreach ([['Bản cũ', $old], ['Bản mới', $new]] as [$caption, $v])
                            <div class="col-md-6">
                                <div class="border rounded p-2">
                                    <span class="text-muted">{{ $caption }}:</span>
                                    <strong>v.{{ $v->version_number }}</strong>
                                    · {{ $v->created_at?->format('d/m/Y H:i') }}
                                    · {{ $v->creator?->name ?? '—' }}
                                    @if ($v->version_type === 'restore')
                                        <span class="badge badge-info">Khôi phục</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Tab lọc theo trạng thái --}}
                    <ul class="nav nav-tabs mb-3" id="compare-tabs">
                        @foreach ($tabs as $key => $t)
                            <li class="nav-item">
                                <a href="#" class="nav-link" data-filter="{{ $key }}">
                                    {{ $t['label'] }}
                                    <span
                                        class="badge badge-{{ $statusMeta[$key]['badge'] ?? 'dark' }} ml-1">{{ $t['count'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    {{-- Bảng song song --}}
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" id="compare-table">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width:90px">Trạng thái</th>
                                    <th style="width:70px">ID</th>
                                    <th>v.{{ $old->version_number }} (cũ)</th>
                                    <th>v.{{ $new->version_number }} (mới)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rows as $row)
                                    <tr data-status="{{ $row['status'] }}">
                                        <td>
                                            <span class="badge badge-{{ $statusMeta[$row['status']]['badge'] }}">
                                                {{ $statusMeta[$row['status']]['label'] }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($scope === 'skin')
                                                <strong>{{ $row['section'] ?? '' }}</strong>
                                            @else
                                                {{ $row['key'] }}
                                                @if ($scope === 'hotspot')
                                                    <br><small
                                                        class="text-muted">{{ $row['new']['potision'] ?? ($row['old']['potision'] ?? '') }}</small>
                                                @endif
                                            @endif
                                        </td>

                                        @foreach (['old', 'new'] as $side)
                                            <td>
                                                @if (is_null($row[$side]))
                                                    <span class="text-muted">—</span>
                                                @else
                                                    @foreach ($row['show'] as $f)
                                                        <div class="mb-2">
                                                            <small
                                                                class="text-muted d-block">{{ ($row['labels'][$f] ?? $labels[$f] ?? $f) }}</small>
                                                            <div class="diff-text">{!! $row['cells'][$side][$f] !!}</div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                <tr id="compare-empty" class="d-none">
                                    <td colspan="4" class="text-center text-muted py-4">
                                        Không có mục nào trong nhóm này.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        (function() {
            const tabs = document.querySelectorAll('#compare-tabs .nav-link');
            const rows = document.querySelectorAll('#compare-table tbody tr[data-status]');
            const empty = document.getElementById('compare-empty');

            function apply(filter) {
                let visible = 0;

                rows.forEach(tr => {
                    const s = tr.dataset.status;
                    const show = filter === 'changed' ? s !== 'unchanged' : s === filter;
                    tr.classList.toggle('d-none', !show);
                    if (show) visible++;
                });

                empty.classList.toggle('d-none', visible > 0);
                tabs.forEach(t => t.classList.toggle('active', t.dataset.filter === filter));
            }

            tabs.forEach(t => t.addEventListener('click', e => {
                e.preventDefault();
                apply(t.dataset.filter);
            }));

            apply('changed');
        })();
    </script>
@endsection
