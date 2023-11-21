<x-modal.content title="Product Details" size="lg" hideFooter="true">

  <div class="row">
    <div class="col-sm-4">
      <div class="product-view-thumb">
        <img src="{{$product->image_url}}" alt="product-image"/>
      </div>
    </div>
    <div class="col-sm-8">
      <table class="table table-bordered">
        <tr>
          <th>Product Name</th>
          <td>{{$product->name}}</td>
        </tr>
        <tr>
          <th>Sku</th>
          <td>{{$product->sku}}</td>
        </tr>
        <tr>
          <th>Product Type</th>
          <td>{{$product->type}}</td>
        </tr>
        <tr>
          <th>Category</th>
          <td>{{$product->category->name ?? ''}}</td>
        </tr>
        <tr>
          <th>Brand</th>
          <td>{{$product->brand->name ?? 'No Brand'}}</td>
        </tr>
        <tr>
          <th>Visibility</th>
          <td>
            @if($product->visibility)
              <span class="badge bg-success">Visible</span>
            @else
              <span class="badge bg-warning">Hidden</span>
            @endif
          </td>
        </tr>
      </table>
    </div>
  </div>

  <div>
    <h4 class="mt-2">Variations</h4>
    <table class="table table-bordered">
      <thead>
      <tr>
        <th>Variation Name</th>
        <th>Old Price</th>
        <th>Price</th>
        <th>Image</th>
      </tr>
      </thead>
      <tbody>
      @foreach($product->variations as $variation)
        <tr>
          <td>{{$variation->name}}</td>
          <td>{{$variation->price}}</td>
          <td>{{$variation->old_price}}</td>
          <td><img src="{{$variation->image_url}}"
                   class="table-thumb"
                   alt="variation-image"/>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>

  </div>

  <div class="mt-2">
    <h5>Short Description</h5>
    <p>{{$product->short_description}}</p>
  </div>

  <div class="mt-2">
    <h5>Description</h5>
    <div>{!! $product->description !!}</div>
  </div>

</x-modal.content>

