<tr>
  <td>

    <input
      type="hidden"
      class="td-index"
      value="{{$index}}"
    />

    <input
      type="hidden"
      name="{{'items['. $index .'][product_id]'}}"
      class="td-product"
      value="{{$product->id}}"
    />

    {{$product->name}}

  </td>
  <td>
    @if($product->type == 'variable')
      <x-form.select
        name="{{'items['. $index .'][variation_id]'}}"
        class="td-variation"
        :options="$selectableVariations"
        value="{{$variations->first()->id}}"
      />
    @else
      <span>Single Variant</span>
      <input
        type="hidden"
        name="{{'items['. $index .'][variation_id]'}}"
        class="td-variation"
        value="{{$variations->first()->id}}"
        readonly
      />
    @endif
  </td>
  <td>
    <x-form.input
      name="{{'items['. $index .'][quantity]'}}"
      class="td-quantity"
      value="1"
    />
  </td>
  <td>
    <x-form.input
      name="{{'items['. $index .'][price]'}}"
      class="td-price"
      value="{{$variations->first()->price}}"
    />
  </td>
  <td>
    <x-form.input
      name="{{'items['. $index .'][total]'}}"
      class="td-total"
      value="{{$variations->first()->price}}"
    />
  </td>
  <td>
    <button class="btn btn-icon rounded btn-danger btn-sm remove-item-btn"
            type="button">
      <i class="ti ti-trash"></i>
    </button>
  </td>
</tr>
