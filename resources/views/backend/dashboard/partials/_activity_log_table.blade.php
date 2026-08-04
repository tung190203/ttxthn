<div class="card-body table-responsive p-0">
    <table class="table table-hover text-nowrap">
        <thead>
            <tr>
                <th>Thời gian</th>
                <th>Người thực hiện</th>
                <th>Hành động</th>
                <th>Đối tượng</th>
                <th>Chi tiết</th>
            </tr>
        </thead>
        <tbody>
            @forelse($activities as $activity)
                <tr>
                    <td>{{ $activity->created_at->format('H:i d/m/Y') }}</td>
                    <td>
                        @if ($activity->causer)
                            {{ $activity->causer->name }}
                        @else
                            System
                        @endif
                    </td>
                    <td>
                        @php
                            $badgeClass = 'secondary';
                            $description = $activity->description;
                            if ($activity->event == 'created' || $description == 'created') {
                                $badgeClass = 'success';
                            } elseif ($activity->event == 'updated' || $description == 'updated') {
                                $badgeClass = 'info';
                            } elseif ($activity->event == 'deleted' || $description == 'deleted') {
                                $badgeClass = 'danger';
                            } elseif ($description == 'logged in') {
                                $badgeClass = 'primary';
                            } elseif ($description == 'logged out') {
                                $badgeClass = 'warning';
                            }
                        @endphp
                        <span class="badge badge-{{ $badgeClass }}">{{ ucfirst($description) }}</span>
                    </td>
                    <td>
                        @if ($activity->subject_type)
                            {{ class_basename($activity->subject_type) }} (ID: {{ $activity->subject_id }})
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if ($activity->properties && count($activity->properties) > 0)
                            <button type="button" class="btn btn-xs btn-outline-warning" data-toggle="modal"
                                data-target="#activity-{{ $activity->id }}">
                                <i class="fas fa-exchange-alt"></i> Xem thay đổi
                            </button>
                            <div class="modal fade" id="activity-{{ $activity->id }}" tabindex="-1" role="dialog"
                                aria-hidden="true">
                                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header bg-warning text-dark">
                                            <h5 class="modal-title">So sánh thay đổi</h5>
                                            <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" style="white-space: normal !important;">
                                            @php
                                                if (!function_exists('formatLogValue')) {
                                                    function formatLogValue($val)
                                                    {
                                                        if (is_array($val)) {
                                                            $val = json_encode(
                                                                $val,
                                                                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
                                                            );
                                                        } elseif (is_string($val)) {
                                                            $decoded = json_decode($val, true);
                                                            if (
                                                                json_last_error() === JSON_ERROR_NONE &&
                                                                (is_array($decoded) || is_object($decoded))
                                                            ) {
                                                                $val = json_encode(
                                                                    $decoded,
                                                                    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES,
                                                                );
                                                            }
                                                        }
                                                        if (is_string($val)) {
                                                            $val = html_entity_decode(
                                                                $val,
                                                                ENT_QUOTES | ENT_HTML5,
                                                                'UTF-8',
                                                            );
                                                        }
                                                        return $val;
                                                    }
                                                }

                                                $properties = $activity->properties;
                                                $attributes = $properties['attributes'] ?? null;
                                                $old = $properties['old'] ?? null;

                                                $translations = [
                                                    'name' => 'Tên',
                                                    'email' => 'Email',
                                                    'phone' => 'Số điện thoại',
                                                    'title' => 'Tiêu đề',
                                                    'content' => 'Nội dung',
                                                    'status' => 'Trạng thái',
                                                    'password' => 'Mật khẩu',
                                                    'avatar' => 'Ảnh đại diện',
                                                    'ip' => 'Địa chỉ IP',
                                                    'user_agent' => 'Trình duyệt',
                                                    'is_approve' => 'Duyệt',
                                                    'status_approve' => 'Trạng thái duyệt',
                                                    'order_number' => 'Thứ tự',
                                                    'slug' => 'Đường dẫn',
                                                    'location_in_tour' => 'Vị trí dự án trong tour',
                                                ];
                                            @endphp

                                            @if ($attributes)
                                                <div class="activity-diff-wrapper">
                                                    @foreach ($attributes as $key => $value)
                                                        @if ($key == 'updated_at' || $key == 'created_at') @continue @endif
                                                        
                                                        @if ($key == 'payload')
                                                            @php
                                                                $oldPayload = [];
                                                                if ($old && isset($old['payload'])) {
                                                                    $oldPayload = is_array($old['payload']) ? $old['payload'] : json_decode($old['payload'], true);
                                                                }
                                                                $newPayload = is_array($value) ? $value : json_decode($value, true);
                                                            @endphp
                                                            @foreach ($newPayload as $payloadKey => $payloadValue)
                                                                @php
                                                                    $oldValue = $oldPayload[$payloadKey] ?? [];
                                                                @endphp
                                                                @if (is_array($payloadValue))
                                                                    @foreach ($payloadValue as $field => $fieldValue)
                                                                        @if (($oldValue[$field] ?? null) != $fieldValue)
                                                                            @php
                                                                                $oVal = is_array($oldValue[$field] ?? null) ? json_encode($oldValue[$field], JSON_UNESCAPED_UNICODE) : $oldValue[$field] ?? '';
                                                                                $nVal = is_array($fieldValue) ? json_encode($fieldValue, JSON_UNESCAPED_UNICODE) : $fieldValue;
                                                                            @endphp
                                                                            <div class="diff-container diff-pending-render">
                                                                                <div class="diff-header">Payload (Văn bản số {{ $payloadKey + 1 }}) - {{ $translations[$field] ?? $field }}</div>
                                                                                <div class="d-none raw-old">{{ $oVal }}</div>
                                                                                <div class="d-none raw-new">{{ $nVal }}</div>
                                                                                <div style="display: grid; grid-template-columns: 1fr 1fr;">
                                                                                    <div class="p-3 border-right diff-col bg-light">
                                                                                        <div class="text-muted mb-2 border-bottom pb-1"><strong><i class="fas fa-file-alt"></i> Giá trị cũ</strong></div>
                                                                                        <div class="diff-left"></div>
                                                                                    </div>
                                                                                    <div class="p-3 diff-col">
                                                                                        <div class="text-primary mb-2 border-bottom pb-1"><strong><i class="fas fa-edit"></i> Giá trị mới</strong></div>
                                                                                        <div class="diff-right"></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                @endif
                                                            @endforeach
                                                        @else
                                                            @php
                                                                $oVal = $old ? formatLogValue($old[$key]) : '';
                                                                $nVal = formatLogValue($value);
                                                            @endphp
                                                            @if ($oVal != $nVal)
                                                                <div class="diff-container diff-pending-render">
                                                                    <div class="diff-header">{{ $translations[$key] ?? $key }}</div>
                                                                    <div class="d-none raw-old">{{ $oVal }}</div>
                                                                    <div class="d-none raw-new">{{ $nVal }}</div>
                                                                    <div style="display: grid; grid-template-columns: 1fr 1fr;">
                                                                        <div class="p-3 border-right diff-col bg-light">
                                                                            <div class="text-muted mb-2 border-bottom pb-1"><strong><i class="fas fa-file-alt"></i> Giá trị cũ</strong></div>
                                                                            <div class="diff-left"></div>
                                                                        </div>
                                                                        <div class="p-3 diff-col">
                                                                            <div class="text-primary mb-2 border-bottom pb-1"><strong><i class="fas fa-edit"></i> Giá trị mới</strong></div>
                                                                            <div class="diff-right"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif

                                            @php
                                                $otherProps = collect($properties)->except(['attributes', 'old']);
                                            @endphp

                                            @if ($otherProps->isNotEmpty())
                                                <div class="mt-3 p-3 bg-light rounded border">
                                                    <h6 class="font-weight-bold">Thông tin bổ sung:</h6>
                                                    <ul class="list-unstyled mb-0">
                                                        @foreach ($otherProps as $key => $value)
                                                            <li
                                                                style="word-break: break-word; overflow-wrap: anywhere;">
                                                                <strong>{{ $translations[$key] ?? $key }}:</strong>
                                                                {{ is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            @if (!$attributes && $otherProps->isEmpty())
                                                <p class="text-center text-muted">Không có chi tiết bổ sung.</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Không có dữ liệu</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="card-footer clearfix">
    <div class="float-right">
        {{ $activities->links() }}
    </div>
</div>

@once
<script src="https://cdnjs.cloudflare.com/ajax/libs/jsdiff/5.1.0/diff.min.js"></script>
<style>
    .diff-added { background-color: #e6ffed; color: #22863a; text-decoration: none; font-weight: bold; }
    .diff-removed { background-color: #ffeef0; color: #cb2431; text-decoration: line-through; }
    .diff-container { border: 1px solid #ddd; margin-bottom: 20px; border-radius: 4px; overflow: hidden; }
    .diff-header { background: #f6f8fa; padding: 10px; font-weight: bold; border-bottom: 1px solid #ddd; }
    .diff-col { white-space: pre-wrap; font-family: 'Courier New', Courier, monospace; word-break: break-word; font-size: 14px; }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function formatTextForDiff(text) {
            if (!text) return '';
            let str = String(text);
            try {
                let arr = JSON.parse(str);
                if (Array.isArray(arr)) {
                    str = arr.join('\n');
                } else if (typeof arr === 'object') {
                    str = JSON.stringify(arr, null, 2);
                }
            } catch (e) {
                if (str.includes(';')) {
                    if (!/<[a-z][\s\S]*>/i.test(str) && !/&[a-z0-9#]+;/i.test(str)) {
                        str = str.split(';').map(s => s.trim()).filter(s => s).join('\n');
                    }
                }
            }
            try { str = decodeURIComponent(str); } catch (e) {}
            if (/<[a-z][\s\S]*>/i.test(str) || /&[a-z0-9#]+;/i.test(str)) {
                let tmp = str;
                tmp = tmp.replace(/<br\s*[\/]?>/gi, '\n');
                tmp = tmp.replace(/<\/p>|<\/div>|<\/li>|<\/h[1-6]>/gi, '\n');
                tmp = tmp.replace(/<[^>]+>/g, '');
                try {
                    let doc = new DOMParser().parseFromString(tmp, "text/html");
                    str = doc.documentElement.textContent;
                } catch(e) {}
            }
            return str;
        }

        // Render when modal opens
        $('.modal').on('show.bs.modal', function () {
            let $modal = $(this);
            if (typeof Diff === 'undefined') return;
            
            $modal.find('.diff-pending-render').each(function() {
                let $container = $(this);
                let oldRaw = $container.find('.raw-old').text();
                let newRaw = $container.find('.raw-new').text();
                
                let oldText = formatTextForDiff(oldRaw);
                let newText = formatTextForDiff(newRaw);
                
                const diff = Diff.diffWordsWithSpace(String(oldText), String(newText));
                let leftTextHtml = '';
                let rightTextHtml = '';
                
                diff.forEach((part) => {
                    let val = part.value.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\n/g, '<br>');
                    if (part.added) {
                        rightTextHtml += `<ins class="diff-added">${val}</ins>`;
                    } else if (part.removed) {
                        leftTextHtml += `<del class="diff-removed">${val}</del>`;
                    } else {
                        leftTextHtml += `<span>${val}</span>`;
                        rightTextHtml += `<span>${val}</span>`;
                    }
                });
                
                $container.find('.diff-left').html(leftTextHtml);
                $container.find('.diff-right').html(rightTextHtml);
                
                $container.removeClass('diff-pending-render');
            });
        });
    });
</script>
@endonce
