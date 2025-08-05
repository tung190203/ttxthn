@props([
    'name',
    'placeholder' => '',
    'value' => '',
    'label' => '',
    'messages' => [],
    'rows' => 5,
    'help' => '',
    'editor' => false,
    'required' => false,
    'class_label' => 'col-sm-3',
    'class_input' => 'col-sm-9',
    'repeatable' => false
])

<div class="form-group row">
    <label class="@if($required) required @endif {{ $class_label }} col-form-label" for="{{ $name }}">
        {{ $label }}
    </label>

    <div class="{{ $class_input }}" id="{{ $name }}_wrapper">
        @php
            $values = $repeatable
                ? (is_array($value) ? $value : explode(';', $value))
                : [$value];
        @endphp

        @foreach ($values as $index => $val)
            @php
                $inputId = $repeatable ? $name . '_' . $index : $name;
                $inputName = $repeatable ? $name . '[]' : $name;
            @endphp

            <div class="repeatable-textarea mb-2 position-relative">
                <textarea
                    name="{{ $inputName }}"
                    id="{{ $inputId }}"
                    class="form-control {{ !empty($messages) ? 'is-invalid' : '' }}"
                    placeholder="{{ $placeholder ?? $label }}"
                    rows="{{ $rows }}"
                    {{ $attributes }}
                >{!! $val !!}</textarea>

                @if($repeatable)
                    <button type="button" class="btn btn-sm btn-danger position-absolute" style="top: 0; right: 0;"
                        onclick="this.parentElement.remove()">×</button>
                @endif

                @if($editor)
                    <script>
                        CKEDITOR.replace("{{ $inputId }}");
                    </script>
                @endif
            </div>
        @endforeach

        @if($repeatable)
            <button type="button" class="btn btn-sm btn-primary add-textarea-btn hover:btn-outline-primary"
                onclick="addTextarea('{{ $name }}', {{ $editor ? 'true' : 'false' }}, {{ $rows }})">
                + Thêm
            </button>
        @endif

        @if (!empty($messages))
            @foreach ((array) $messages as $message)
                <div class="text-danger mt-1">{{ $message }}</div>
            @endforeach
        @endif

        @if($help)
            <div class="text-muted mt-1">{{ $help }}</div>
        @endif
    </div>
</div>

@if($repeatable)
    <script>
        function addTextarea(name, useEditor = false, rows = 5) {
            const wrapper = document.getElementById(`${name}_wrapper`);
            const count = wrapper.querySelectorAll('.repeatable-textarea').length;
            const id = `${name}_${count}`;

            const div = document.createElement('div');
            div.className = 'repeatable-textarea mb-2 position-relative';

            div.innerHTML = `
                <textarea name="${name}[]" id="${id}" class="form-control" rows="${rows}" placeholder=""></textarea>
                <button type="button" class="btn btn-sm btn-danger position-absolute" style="top: 0; right: 0;" onclick="this.parentElement.remove()">×</button>
            `;

            const addBtn = wrapper.querySelector('.add-textarea-btn');
            wrapper.insertBefore(div, addBtn); // Thêm trước nút thêm

            if (useEditor && typeof CKEDITOR !== 'undefined') {
                CKEDITOR.replace(id);
            }
        }
    </script>
@endif
