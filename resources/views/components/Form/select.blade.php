<div class="form-group mb-3">

  @if (!empty($label))
    <label for="{{ $id ?? $name}}" class="form-label">{{__($label)}}
      @if(!empty($required))
        <span class="text-danger ml-1">*</span>
      @endif
    </label>
  @endif

  @if(!is_array($options) && !is_iterable($options))
    <pre class="text-danger">Data Error</pre>
  @else

    <select class="form-control" @class="{{$class}}" id="{{$id ?? $name}}" name="{{$name}}" {{$attributes}}
    @if(!empty($required))
      required="required"
    @endif
    >
    @if(empty($noPlaceholder))
      <option
        value="">{{ ($placeholder ? '--'. $placeholder.'--' : !empty($label)) ? '--'. __($label). '--' : '--Select One--'}}</option>
    @endif

    @foreach($options as $key=>$option)
      <option value="{{$key}}"
              @if(gettype($value) == 'array' ? in_array($key, $value) : strval($key) == strval($value)) selected="selected" @endif>{{$option}}</option>
        @endforeach
    </select>

  @endif
</div>
