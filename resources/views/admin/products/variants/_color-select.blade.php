@php
    $selectedColorId = old('color_id', $selectedColorId ?? '');
    $colorSelectId = $colorSelectId ?? 'variant-color';
@endphp

<div class="variant-color-picker" data-color-picker>
    <select
        id="{{ $colorSelectId }}"
        name="color_id"
        class="form-select @error('color_id') is-invalid @enderror"
        data-color-native
        aria-label="رنگ"
    >
        <option value="">انتخاب رنگ</option>
        @foreach($colors as $color)
            <option
                value="{{ $color->id }}"
                data-hex="{{ $color->hex }}"
                data-label="{{ $color->name }} ({{ $color->code }})"
                @selected((string) $selectedColorId === (string) $color->id)
            >{{ $color->name }} ({{ $color->code }})</option>
        @endforeach
    </select>

    <button
        type="button"
        class="form-select d-none variant-color-trigger"
        data-color-trigger
        aria-haspopup="listbox"
        aria-expanded="false"
        aria-controls="{{ $colorSelectId }}-options"
    >
        <span class="variant-color-swatch" data-color-selected-swatch aria-hidden="true"></span>
        <span data-color-selected-label>انتخاب رنگ</span>
    </button>

    <div
        id="{{ $colorSelectId }}-options"
        class="dropdown-menu variant-color-menu"
        data-color-menu
        role="listbox"
        aria-label="رنگ‌های قابل انتخاب"
    >
        @foreach($colors as $color)
            <button
                type="button"
                class="dropdown-item variant-color-option"
                data-color-option
                data-value="{{ $color->id }}"
                data-hex="{{ $color->hex }}"
                data-label="{{ $color->name }} ({{ $color->code }})"
                role="option"
                aria-selected="{{ (string) $selectedColorId === (string) $color->id ? 'true' : 'false' }}"
            >
                <span class="variant-color-swatch" data-color-swatch aria-hidden="true"></span>
                <span>{{ $color->name }}</span>
                <span class="text-muted small">{{ $color->code }}</span>
            </button>
        @endforeach
    </div>
</div>

@error('color_id')
    <div class="invalid-feedback d-block">{{ $message }}</div>
@enderror

@once
    @push('styles')
        <style>
            .variant-color-picker { position: relative; }
            .variant-color-trigger { display: flex; align-items: center; gap: .65rem; text-align: right; }
            .variant-color-swatch { display: inline-block; flex: 0 0 1.15rem; width: 1.15rem; height: 1.15rem; border: 1px solid #98a2b3; border-radius: .3rem; background-color: var(--variant-color, #fff); box-shadow: inset 0 0 0 1px #ffffff55; }
            .variant-color-swatch[hidden] { display: none; }
            .variant-color-menu { inset: calc(100% + .25rem) 0 auto auto; width: 100%; max-height: 16rem; overflow-y: auto; padding: .35rem; }
            .variant-color-option { display: flex; align-items: center; gap: .65rem; border-radius: .4rem; }
            .variant-color-option .text-muted { margin-inline-start: auto; }
            .variant-color-option[aria-selected="true"] { background: #edf4f2; color: #174f44; }
            .variant-color-picker.has-error .variant-color-trigger { border-color: var(--bs-form-invalid-border-color); }
        </style>
    @endpush
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('[data-color-picker]').forEach((picker) => {
                    const select = picker.querySelector('[data-color-native]');
                    const trigger = picker.querySelector('[data-color-trigger]');
                    const menu = picker.querySelector('[data-color-menu]');
                    const selectedLabel = picker.querySelector('[data-color-selected-label]');
                    const selectedSwatch = picker.querySelector('[data-color-selected-swatch]');
                    const options = [...picker.querySelectorAll('[data-color-option]')];

                    const paintSwatch = (swatch, hex) => {
                        swatch.style.backgroundColor = hex || '#fff';
                    };

                    const syncSelection = () => {
                        const selected = select.selectedOptions[0];
                        const hasColor = Boolean(selected && selected.value);
                        selectedLabel.textContent = hasColor ? selected.dataset.label : 'انتخاب رنگ';
                        selectedSwatch.hidden = !hasColor;
                        paintSwatch(selectedSwatch, hasColor ? selected.dataset.hex : '#fff');

                        options.forEach((option) => {
                            const isSelected = option.dataset.value === select.value;
                            option.setAttribute('aria-selected', isSelected ? 'true' : 'false');
                        });
                    };

                    options.forEach((option) => {
                        paintSwatch(option.querySelector('[data-color-swatch]'), option.dataset.hex);
                        option.addEventListener('click', () => {
                            select.value = option.dataset.value;
                            select.dispatchEvent(new Event('change', { bubbles: true }));
                            syncSelection();
                            menu.classList.remove('show');
                            trigger.setAttribute('aria-expanded', 'false');
                            trigger.focus();
                        });
                    });

                    trigger.addEventListener('click', () => {
                        const isOpen = menu.classList.toggle('show');
                        trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    });

                    picker.addEventListener('keydown', (event) => {
                        if (event.key === 'Escape') {
                            menu.classList.remove('show');
                            trigger.setAttribute('aria-expanded', 'false');
                            trigger.focus();
                        }
                    });

                    document.addEventListener('click', (event) => {
                        if (!picker.contains(event.target)) {
                            menu.classList.remove('show');
                            trigger.setAttribute('aria-expanded', 'false');
                        }
                    });

                    picker.classList.toggle('has-error', select.classList.contains('is-invalid'));
                    syncSelection();
                    select.classList.add('d-none');
                    trigger.classList.remove('d-none');
                });
            });
        </script>
    @endpush
@endonce
