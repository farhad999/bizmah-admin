@foreach($templates as $index=>$template)
  @php
    $values = explode(',', $template->values);
    $values = array_combine($values, $values);
  @endphp

  <div class="col-sm-6">
    <x-form.select
      name="template_values[]"
      label="{{'Select ' .$template->name}}"
      id="{{'variable-template-values_'. $index}}"
      class="variable-template-values"
      :options="$values"
      :multiple="true"
      :required="true"
      data-rules="required"
    />
  </div>
@endforeach
<div class="col-12">
  <button class="btn btn-primary btn-sm mb-4" id="create_variation_btn" type="button">Create</button>
</div>

