<div class="mb-3">
  <table class="table table-bordered">
    <thead>
    <tr>
      <th>Name *</th>
      <th>
        <i class="fa fa-check text-primary cursor-pointer" id="set_all_old_price"></i>
        Old Price</th>
      <th>
        <i class="fa fa-check text-primary cursor-pointer" id="set_all_price"></i>
        Price*
      </th>
      <th>Image</th>
      <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($variations as $index=>$variation)
      <tr>
        <td class="p-1">
          <x-form.input
            name="{{'variations['.$index.'][name]'}}"
            value="{{$variation}}"
            readonly
          />
        </td>
        <td class="p-1">
          <x-form.input
            name="{{'variations['.$index.'][old_price]'}}"
            placeholder="Old Price"
            data-rules="number"
            class="td-old-price"
          />
        </td>
        <td class="p-1">
          <x-form.input
            name="{{'variations['.$index.'][price]'}}"
            placeholder="Price"
            required="true"
            data-rules="required|number"
            class="td-price"
          />
        </td>
        <td class="p-1">
          <x-form.file-input
            name="{{'variations['.$index.'][image]'}}"
          />
        </td>
        <td>
          <button class="btn btn-danger btn-sm remove-variation-btn">
            <i class="ti ti-trash"></i>
          </button>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>
</div>
