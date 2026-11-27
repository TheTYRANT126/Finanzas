@props(['value' => null, 'placeholder', 'hint' => null])
@php($detail = old('category_detail', $value))
<div class="field category-detail" data-category-detail>
    <label for="category_detail">Detalle de la categoría <small>opcional</small></label>
    <input id="category_detail" name="category_detail" maxlength="{{ \App\Models\Category::DETAIL_MAX_LENGTH }}"
        placeholder="{{ $placeholder }}" value="{{ is_string($detail) ? $detail : '' }}"
        @if($hint) aria-describedby="category-detail-help" @endif>
    @if($hint)
        <p class="field-help" id="category-detail-help">{{ $hint }}</p>
    @else
        <noscript><p class="field-help">Para «Otros ingresos» u «Otros gastos».</p></noscript>
    @endif
</div>
