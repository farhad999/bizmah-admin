<?php
$options = [1 => 'Active', 0 => 'Inactive'];
?>

<div class="form-group">
  <x-form.select
    name="status" label="Status"
    :options="$options" :value="$value ?? 1"
    :required="true"
    data-rules="required"
  />
</div>
