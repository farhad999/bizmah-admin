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
      value="{{$item->product->id}}"
    />

    {{$item->product->name}}

  </td>

  @php
    $variations = $item->product->variations;
    $selectableVariations = $variations->pluck('name', 'id');
  @endphp
  <td>
    @if($item->product->type == 'variable')
      <x-form.select
        name="{{'items['. $index .'][variation_id]'}}"
        class="td-variation"
        :options="$selectableVariations"
        value="{{$item->variation_id}}"
      />
    @else
      <span>Single Variant</span>
      <input
        type="hidden"
        name="{{'items['. $index .'][variation_id]'}}"
        class="td-variation"
        value="{{$variations->first()->id}}"
        readonly
    @endif
  </td>
  <td>
    <x-form.input
      name="{{'items['. $index .'][quantity]'}}"
      class="td-quantity"
      value="{{$item->quantity}}"
    />
  </td>
  <td>
    <x-form.input
      name="{{'items['. $index .'][price]'}}"
      class="td-price form-control"
      value="{{$item->price}}"
    />
  </td>
  <td>
    <x-form.input
      name="{{'items['. $index .'][total]'}}"
      class="td-total"
      value="{{$item->quantity * $item->price}}"
    />
  </td>

  <td>
    <button class="btn btn-icon rounded btn-danger btn-sm remove-item-btn"
            type="button">
      <i class="ti ti-trash"></i>
    </button>
  </td>

</tr>
