@props(['id' => null, 'name' , 'label', 'value', 'isChecked' => false])
<div class="form-check">
  <label for="{{ $id ?: $name }}" class="form-check-label">
    <input type="hidden" name="{{ $name }}" value="0">
    <input type="checkbox" id="{{ $id ?: $name }}" name="{{ $name }}"
           class="form-check-input"
           value="{{ $value }}" {{ $isChecked ? 'checked' : '' }}>
    {{ __($label) }}</label>
</div>
