<div>
  @if(!empty($images) && count($images) > 0)
    <div class="row mt-1">
      @foreach($images as $image)
        {{-- we are removing wrapper item after image deletation --}}
        <div class="col-3 mb-3 wrapper-item">
          <div class="image-gallery-item">
            <div class="remove-btn">
              <i class="fa fa-x"></i>
            </div>
            <input type="hidden" name="image_id" value="{{$image->id}}">
            <img src="{{asset('storage/' . $image->image)}}"/>
          </div>
        </div>
      @endforeach
    </div>
  @else
    <div class="border mt-2 py-5 text-center">
      No images
    </div>
  @endif
</div>
