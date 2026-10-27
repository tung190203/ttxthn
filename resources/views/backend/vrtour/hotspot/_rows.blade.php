@php
    $money = fn($v) => is_null($v) ? '—' : number_format($v, 0, ',', '.') . ' ₫';
@endphp

@foreach ($hotspots as $hp)
    @php
        $potision = str_starts_with($hp->potision, 'cmss_') ? substr($hp->potision, 5) : $hp->potision;
        $status = $hp->investmentProductStatus;
        $locked = auth()->user()->is_super_admin && $hp->is_draft;
    @endphp
    <tr>
        <td>
            <input type="checkbox" class="chk-row" value="{{ $hp->id }}" @disabled($locked) @if ($locked)
            title="Đang chờ duyệt, hãy duyệt hoặc từ chối trước" @endif>
        </td>
        <td>{{ $hotspots->firstItem() + $loop->index }}</td>
        <td><img src="{{ $hp->url }}" style="width:100px;height:100px;object-fit:contain"></td>
        <td>{{ $potision }}</td>
        <td>
            <div class="mt-1">
                @if ($hp->is_draft)
                    <span class="badge bg-warning">Bản chỉnh sửa</span>
                @endif
                @if ($hp->status === 'pending')
                    @if ($hp->approval_level == 0)
                        <span class="badge bg-secondary">Chờ duyệt cấp 1</span>
                    @elseif ($hp->approval_level == 1)
                        <span class="badge bg-primary">Chờ duyệt cấp 2</span>
                    @endif
                @elseif ($hp->status === 'approved')
                    <span class="badge bg-success">Đã duyệt</span>
                @elseif ($hp->status === 'rejected')
                    <span class="badge bg-danger">Từ chối</span>
                @endif
            </div>
        </td>
        <td>
            @if ($status)
                <span class="badge" style="background-color: {{ $status->color_code }}; color:#fff">
                    {{ $status->name }}
                </span>
            @else
                <span class="text-muted">—</span>
            @endif
        </td>
        <td>{{ $money($hp->price) }}</td>
        <td>{{ $money($hp->lease_price) }}</td>
        <td>{{ $money($hp->sale_price) }}</td>
        <td>{{ \Illuminate\Support\Str::limit($hp->contact_info, 50) ?: '—' }}</td>
        <td><i class="fas {{ $hp->opacity ? 'fa-eye text-success' : 'fa-eye-slash text-muted' }}"
                title="{{ $hp->opacity ? 'Hiển thị' : 'Ẩn' }}"></i></td>
        <td class="grid_row1">
            <a class="btn btn-info btn-sm mr-1"
                href="{{ route('backend_vrtour_hotspot_edit', ['id' => $hp->id, 'type' => $hp->type]) }}" title="Chỉnh sửa">
                <i class="fas fa-pencil-alt"></i>
            </a>
        </td>
    </tr>
@endforeach