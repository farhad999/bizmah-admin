<div class="table-responsive">
  <table class="table table-hover table-striped">
    <thead>
    <tr>
      <th>#</th>
      <th>Name</th>
      <th>Quantity</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($topOrderedProducts as $key => $product)
      <tr>
        <td>{{$key+1}}</td>
        <td>{{$product->name}}</td>
        <td>{{$product->total}}</td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>
