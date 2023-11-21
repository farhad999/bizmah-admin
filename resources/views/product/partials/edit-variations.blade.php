<div class="mb-3">
  <table class="table table-bordered">
    <thead>
    <tr>
      <th>Name *</th>
      <th>Old Price</th>
      <th>Price *</th>
      <th>Image</th>
      <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($variations as $index=>$variation)
      <tr>
        <td class="p-1">
          <input type="hidden" name="{{'variations['.$index.'][id]'}}"
                 value="{{$variation->id}}"
          />
          <x-form.input
            name="{{'variations['.$index.'][name]'}}"
            value="{{$variation->name}}"
            readonly
          />
        </td>
        <td class="p-1">
          <x-form.input
            name="{{'variations['.$index.'][old_price]'}}"
            placeholder="Old Price"
            data-rules="number"
            value="{{$variation->old_price}}"
          />
        </td>
        <td class="p-1">
          <x-form.input
            name="{{'variations['.$index.'][price]'}}"
            placeholder="Price"
            required="true"
            data-rules="required|number"
            value="{{$variation->price}}"
          />
        </td>
        <td class="p-1">
          <x-form.file-input
            name="{{'variations['.$index.'][image]'}}"
            images="{{$variation->image_url}}"
          />
        </td>
        <td class="p-1">
          <button class="btn btn-danger btn-sm remove-variation-btn">
            <i class="ti ti-trash"></i>
          </button>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>
