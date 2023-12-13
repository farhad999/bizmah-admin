@props(['accept' => 'image/*', 'name', 'multiple' =>false, 'required' => false,
'label', 'images' => [], 'id' => null, 'hint' => ''])
<div class="image-box mb-3">
  @if(!empty($label))
    <label class="form-label">{{$label}}
      @if($required)
        <span class="text-danger">*</span>
      @endif
    </label>
  @endif
  <div>
    <input
      type="file"
      class="form-control image-input"
      accept="{{$accept}}"
      name="{{$name}}"
      @if(!empty($id)) id="{{$id}}" @endif
      @if($required) required="required" @endif
      @if($multiple) multiple="multiple" @endif
    />
    @if(!empty($hint))
      <small>{{$hint}}</small>
    @endif
  </div>

  @php
    if(!empty($images) && gettype($images) == 'string')
        $images = [$images];
  @endphp

  <div class="image-preview-gallery d-flex px-1">
    @foreach($images as $image)
      <div class="image-preview-old">
        {{--<input type="hidden" name="prev_images[]" value="{{$image}}"/>--}}
        <img src="{{$image}}"/>
      </div>
    @endforeach
  </div>
</div>
