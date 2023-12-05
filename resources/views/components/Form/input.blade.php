@props(['id' => '', 'name' => 'name', 'label' => '',
 'placeholder' => '', 'required' => false, 'value' => '', 'class' => '',
  'labelClass' => ''])

<div class="form-group mb-3">
  @if(!empty($label))
    <label @class(["$labelClass text-capitalize form-label"])>{{__($label)}}
      @if(!empty($required))
        <span class="text-danger ml-1">*</span>
      @endif
    </label>
  @endif
  <input
    name="{{$name}}"
    placeholder="{{$placeholder ? __($placeholder):  __($label)}}"
    @class(["$class form-control"]) {{$attributes}}
    value="{{old($name) ?? ($value != null ? $value : '')}}"
    @if(!empty($required))required="required" @endif
    @if(!empty($id)) id="{{$id}}" @endif
  >
</div>
