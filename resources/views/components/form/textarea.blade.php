@props(['id' => null, 'name', 'label'=> '', 'placeholder' => '', 'required' => false, 'value' => '', 'class' => '', 'rows' => 3])

<div class="form-group mb-3">
  <label
    @class(["font-weight-bold"])>{{__($label)}}
    @if(!empty($required))
      <span class="text-danger ml-1">*</span>
    @endif
  </label>
  <textarea
    name="{{$name}}"
    placeholder="{{!empty($placeholder) ? __($placeholder):  __($label)}}"
    rows="{{$rows}}"
    @class(["$class form-control"]) {{$attributes}}
    @if(!empty($required))
      required="required"
           @endif
    >{{old($name) ?? ($value != null ? $value : '')}}</textarea>
</div>
