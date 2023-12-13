<?php
$options = [1 => 'Visible', 0 => 'Hidden'];
?>

<div class="form-group">
  <x-form.select
    name="visibility" label="Visible (E-commerce)"
    :options="$options"
    :value="$value ?? 1"
    :required="true"
    data-rules="required"
    noPlaceholder="true"
  />
</div>
