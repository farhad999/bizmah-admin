<x-modal.content title="Order Details" size="lg">

  <div class="row">
    <div class="col-sm-6">

      <h5>Order Summary</h5>

      <input type="hidden" name="order_id" id="order_id" value="{{$order->id}}">

      <table class="table table-bordered">
        <tr>
          <th>Order No.</th>
          <td>{{$order->order_no}}</td>
        </tr>
        <tr>
          <th>Date</th>
          <td>{{$order->date}}</td>
        </tr>
        <tr>
          <th>Status</th>
          <td class="d-flex justify-content-between align-items-center">
            @if($order->status == 'confirmed')
              <span class="badge bg-success">Confirmed</span>
            @elseif($order->status == 'cancelled')
              <span class="badge bg-danger">Cancelled</span>
            @else
              <span class="badge bg-primary">Pending</span>
            @endif
            <div>
              @if($order->status != 'confirmed')
                <button class="btn btn-icon rounded-circle btn-outline-success order-confirm-btn btn-sm">
                  <i class="ti ti-check"></i>
                </button>
              @endif
              @if($order->status != 'cancelled')
                <button class="btn btn-icon rounded-circle btn-outline-danger order-cancel-btn btn-sm">
                  <i class="ti ti-x"></i>
                </button>
              @endif
            </div>
          </td>
        </tr>
        <tr>
          <th>Shipping Status</th>
          <td class="d-flex justify-content-between align-items-center">{{$order->shipping_status}}
            <input type="hidden" name="shipping_status" id="shipping_status" value="{{$order->shipping_status}}">
            <button class="btn btn-icon rounded-circle btn-outline-primary btn-sm shipping-status-btn">
              <i class="ti ti-edit"></i>
            </button>
          </td>
        </tr>
      </table>
    </div>

    <div class="col-sm-6">
      <h5>Customer Info</h5>
      <table class="table table-bordered">
        <tr>
          <th>Name</th>
          <td>{{$order->customer_name}}</td>
        </tr>
        <tr>
          <th>Mobile</th>
          <td>{{$order->customer_mobile}}</td>
        </tr>
        <tr>
          <th>Shipping Address</th>
          <td>{{$order->shipping_address}}</td>
        </tr>
      </table>
    </div>

  </div>

  <div>
    <div class="card-datatable table-responsive mt-3">
      <div class="dataTables_wrapper dt-bootstrap5 no-footer">
        <table class="datatables-order-details table border-top dataTable no-footer dtr-column">
          <thead>
          <tr>
            <th>
              products
            </th>
            <th>price</th>
            <th>qty</th>
            <th class="text-end">total</th>
          </tr>
          </thead>
          <tbody>

          @foreach($order->items as $item)
            <tr>
              <td>
                <div class="d-flex justify-content-start align-items-center text-nowrap">
                  <div class="avatar-wrapper">
                    <div class="avatar me-2">
                      <img src="{{$item->product->image_url}}"
                           alt="product-Wooden Chair" class="rounded-2"></div>
                  </div>
                  <div class="d-flex flex-column">
                    <h6 class="text-body mb-0">{{$item->product->name}}</h6>
                    <small class="text-muted">Material:
                      Wooden</small></div>
                </div>
              </td>
              <td><span>{{$item->price}}</span></td>
              <td><span class="text-body">{{$item->quantity}}</span></td>
              <td class="text-end"><h6 class="mb-0">{{$item->price * $item->quantity}}</h6></td>
            </tr>
          @endforeach

          </tbody>
        </table>
      </div>
      <div class="d-flex justify-content-end align-items-center m-3 mb-2 p-1">
        <div>
          <div class="d-flex justify-content-between mb-2">
            <span class="w-px-100 text-heading">Subtotal:</span>
            <h6 class="mb-0">{{$order->subtotal}}</h6>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span class="w-px-100 text-heading">Discount:</span>
            <h6 class="mb-0">{{$order->discount}}</h6>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span class="w-px-100 text-heading">Shipping Charge:</span>
            <h6 class="mb-0">{{$order->shipping_charge}}</h6>
          </div>
          <div class="d-flex justify-content-between">
            <h6 class="w-px-100 mb-0">Total:</h6>
            <h6 class="mb-0">{{$order->total_amount}}</h6>
          </div>
        </div>
      </div>

      {{--<div class="row">
        <div class="col-sm-6">
          <x-form.input
            name="status"
            label="Status"
            :options="$orderStatuses"
          />
        </div>

        <div>
          <x-form.input
            name="shipping_status"
            label="Shipping Status"
            :options="$shippingStatuses"
          />
        </div>

      </div>--}}

    </div>
  </div>
</x-modal.content>
