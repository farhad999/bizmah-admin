<x-modal.content title="Image Gallery" hideFooter="true">
  <x-form action="{{route('products.upload', $product->id)}}" id="image_gallery_form">
    <div>
      <input class="product-id" type="hidden" name="url" value="{{$product->id}}"/>
      <x-form.file-input
        name="images[]"
        id="upload_gallery"
        multiple
        accept="image/*"
        required
      />
      <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary" id="btn-upload">
          <i class="fa fas fa-upload"></i>
          Upload
        </button>
      </div>
    </div>
  </x-form>

  <hr/>

  <div id="image_gallery_container"></div>

</x-modal.content>


