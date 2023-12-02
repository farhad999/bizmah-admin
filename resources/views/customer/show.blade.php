@extends('layouts.layoutMaster')

@section('content')
  <x-content title="Customer Details">
    <div>
      <table class="table table-bordered">
        <tr>
          <th>Name</th>
          <td>{{$customer->name}}</td>
        </tr>
        <tr>
          <th>Mobile</th>
          <td>
            <a href="{{'tel:'.$customer->mobile}}">
            {{$customer->mobile}}
            </a>
          </td>
        </tr>
        <tr>
          <th>Email</th>
          <td>{{$customer->email ?? 'No Email'}}</td>
        </tr>
        <tr>
          <th>Status</th>
          <td>
            @if($customer->status == 1)
              <span class="badge bg-success">Active</span>
            @else
              <span class="badge bg-danger">Inactive</span>
            @endif
          </td>
        </tr>
      </table>
    </div>

    <h4 class="mt-2">Customer's Cart</h4>

    <div>
      @if(count($carts) > 0)
        <table class="table table-bordered">
          <thead>
          <tr>
            <th>Name</th>
            <th>Variation</th>
            <th>Quantity</th>
          </thead>
          <tbody>
          @foreach ($carts as $cart)
            <tr>
              <td>
                <img src="{{$cart->product->image_url}}"
                     class="table-thumb"
                >
                {{$cart->product->name}}</td>
              <td>{{$cart->variation->name}}</td>
              <td>{{$cart->quantity}}</td>
            </tr>
          @endforeach
        </table>
      @else
        <p>Customer's Cart is Empty</p>
      @endif
    </div>

  </x-content>
@endsection
