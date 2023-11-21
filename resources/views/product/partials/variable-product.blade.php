<div class="col-sm-6">
  <x-form.select
    name="templates[]"
    label="Template"
    :options="$templates"
    :multiple="true"
    id="variable_template"
    :required="true"
    data-rules="required"
  />
</div>
<div class="row" id="variable_template_container"></div>
<div class="col-12" id="variation_table"></div>
