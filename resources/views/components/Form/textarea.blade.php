<div class="form-group mb-3">
    <label for="{{$id ?:$name}}" @class(["$labelClass font-bold"])>{{__($label)}}
        @if(!empty($required))
            <span class="text-danger ml-1">*</span>
        @endif
    </label>
    <textarea id="{{$id ?: $name}}" name="{{$name}}"
              placeholder="{{!empty($placeholder) ? __($placeholder):  __($label)}}"
              rows="{{$rows}}"
              @class(["$class form-control"]) {{$attributes}}
              @if(!empty($required))
                  required="required"
           @endif
    >{{old($name) ?? ($value != null ? $value : '')}}</textarea>
</div>
