@props(['name', 'value' => [], 'label' => '', 'messages' => [], 'help' => ''])

@php
    $hasTitles = array_key_exists('titles', $value);
    $hasDescs = array_key_exists('descs', $value);

    $images = collect($value['images'] ?? [])
        ->filter()
        ->values()
        ->all();
    $titles = $value['titles'] ?? [];
    $descs = $value['descs'] ?? [];
@endphp

<div class="form-group row">
    <label class="col-sm-3 col-form-label">{{ $label }}</label>
    <div class="col-sm-9">
        <div id="{{ $name }}_wrapper">
            <div class="upload-combo-list mb-3">
                @foreach ($images as $index => $img)
                    <div class="upload-combo-item mt-4">
                        <div class="position-relative">
                            <img src="{{ $img }}" class="rounded"
                                style="max-width:200px; object-fit: cover;">
                            <button type="button"
                                class="btn btn-sm btn-danger rounded-circle btn-close-custom position-absolute top-0 end-0 mt-0 ml-1"
                                onclick="this.closest('.upload-combo-item').remove()">
                                &times;
                            </button>
                            <input type="hidden" name="{{ $name }}_images[]" value="{{ $img }}">
                        </div>

                        @if ($hasTitles)
                            <input type="text" name="{{ $name }}_titles[]" class="form-control mt-2"
                                placeholder="Tiêu đề" value="{{ $titles[$index] ?? '' }}">
                        @endif

                        @if ($hasDescs)
                            <textarea name="{{ $name }}_descs[]" class="form-control mt-2" rows="4"
                                placeholder="Mô tả">{{ $descs[$index] ?? '' }}</textarea>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Nút thêm ảnh -->
            <div class="d-flex justify-content-start">
                <div id="{{ $name }}_add" class="upload-combo-add text-muted"
                    onclick="selectFilesWithCKFinder('{{ $name }}', {{ $hasTitles ? 'true' : 'false' }}, {{ $hasDescs ? 'true' : 'false' }})">
                    +
                </div>
            </div>
        </div>

        @if ($messages)
            @foreach ((array) $messages as $message)
                <div class="text-danger mt-1">{{ $message }}</div>
            @endforeach
        @endif

        @if ($help)
            <div class="text-muted mt-2">{{ $help }}</div>
        @endif
    </div>
</div>

<style>
    .upload-combo-item {
        width: 100%;
    }

    .upload-combo-add {
        width: 200px;
        height: 133px;
        border: 2px dashed #ccc;
        border-radius: 6px;
        cursor: pointer;
        font-size: 48px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .upload-combo-add:hover {
        background-color: #f8f9fa;
        border-color: #888;
    }

    .btn-close-custom {
        width: 28px;
        height: 28px;
        background-color: rgba(255, 0, 0, 0.8);
        color: white;
        border: none;
        font-size: 16px;
        text-align: center;
        line-height: 24px;
        padding: 0;
    }

    .btn-close-custom:hover {
        background-color: rgba(220, 0, 0, 0.9);
    }
</style>

<script>
    function selectFilesWithCKFinder(name, hasTitles = true, hasDescs = true) {
        CKFinder.modal({
            chooseFiles: true,
            width: 800,
            height: 600,
            onInit: function(finder) {
                finder.on('files:choose', function(evt) {
                    const files = evt.data.files.models;
                    const list = document.querySelector(`#${name}_wrapper .upload-combo-list`);

                    files.forEach(function(file) {
                        const url = file.getUrl();
                        const item = document.createElement('div');
                        item.className = 'upload-combo-item mt-4';

                        let html = `
                            <div class="position-relative">
                                <img src="${url}" class="rounded" style="max-width:200px; object-fit: cover;">
                                <button type="button"
                                    class="btn btn-sm btn-danger rounded-circle btn-close-custom position-absolute top-0 end-0 mt-0 ml-1"
                                    onclick="this.closest('.upload-combo-item').remove()">
                                    &times;
                                </button>
                                <input type="hidden" name="${name}_images[]" value="${url}">
                            </div>
                        `;

                        if (hasTitles) {
                            html += `<input type="text" name="${name}_titles[]" class="form-control mt-2" placeholder="Tiêu đề">`;
                        }

                        if (hasDescs) {
                            html += `<textarea name="${name}_descs[]" class="form-control mt-2" rows="2" placeholder="Mô tả"></textarea>`;
                        }

                        item.innerHTML = html;
                        list.appendChild(item);
                    });
                });
            }
        });
    }
</script>
