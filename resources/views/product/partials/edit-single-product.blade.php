<div class="mb-3">
  <h5>Product Price</h5>
  @php
    $variation = $variations->first();
  @endphp

  <table class="table table-bordered">
    <thead>
    <tr>
      <th>Old Price</th>
      <th>Price</th>
    </tr>
    </thead>
    <tbody>
    <tr>
      <td>
        <input type="hidden"
               name="{{'variations[0][id]'}}"
               value="{{$variation->id}}"
        >
        <input type="hidden"
               name="{{'variations[0][name]'}}"
               value="{{$variation->name}}"
        >
        <x-form.input
          name="{{'variations[0][old_price]'}}"
          placeholder="Old Price"
          value="{{$variation->old_price}}"
        />
      </td>
      <td>
        <x-form.input
          name="{{'variations[0][price]'}}"
          placeholder="Price"
          value="{{$variation->price}}"
        />
      </td>

    </tr>
    </tbody>
  </table>
</div>
