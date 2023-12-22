<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use App\Models\Setting;
use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends ApiController
{

  public function index()
  {
    $user = auth()->user();

    //get customer's orders
    $orders = Order::where('customer_id', $user->id)
      ->orderBy('created_at', 'desc')
      ->get();
    return response()->json($orders);

  }


  public function create()
  {
    //
  }


  public function store(Request $request)
  {
    $request->validate([
      'customer_name' => 'required',
      'customer_mobile' => 'required',
      'customer_address' => 'required',
      'items.*.variation_id' => 'required',
      'items.*.quantity' => 'required',
      'delivered_to' => 'required',
    ]);


    $orderData = $request->only([
      'customer_name', 'customer_mobile', 'customer_address',
      'customer_city', 'customer_zone',
      'shipping_charge',
    ]);

    //check if items are valid value

    $items = $request->input('items');

    $orderItemsData = [];
    $hasProductError = false;
    $subtotal = 0;

    foreach ($items as $key => $item) {
      //find variation for each item and product is visible
      $variation = Variation::join('products', 'variations.product_id', '=', 'products.id')
        ->where("variations.id", $item['variation_id'])
        ->where("products.visibility", 1)
        ->first();

      if (!$variation) {
        $hasProductError = true;
        break;
      }

      $orderItemsData[] = [
        'variation_id' => $item['variation_id'],
        'product_id' => $variation->product_id,
        'quantity' => $item['quantity'],
        'price' => $variation->price,
        'old_price' => $variation->old_price
      ];

      //calculate subtotal

      $subtotal += $variation->price * $item['quantity'];

    }

    if ($hasProductError) {
      return response()->json([
        'message' => 'One or more products are not visible'
      ]);
    }

    $deliveredTo = $request->input('delivered_to');

    if ($deliveredTo == 'inside_dhaka') {
      //get shipping charge
      $shippingCharge = Setting::where('name', 'shipping_charge_inside_dhaka')
        ->first()->value;
    } else {
      //get shipping charge
      $shippingCharge = Setting::where('name', 'shipping_charge_outside_dhaka')
        ->first()->value;
    }

    $user = $request->user('sanctum');

    //now items are valid
    $total = $subtotal + $shippingCharge;

    $orderData['subtotal'] = $subtotal;
    $orderData['shipping_charge'] = $shippingCharge;
    $orderData['total_amount'] = $total;
    $orderData['order_no'] = date('ymd') . rand(100000, 999999);
    $orderData['customer_id'] = $user->id ?? null;
    $orderData['delivered_to'] = $deliveredTo;
    $orderData['source'] = 'website';
    $orderData['date'] = now()->timezone('Asia/Dhaka');

    DB::beginTransaction();

    try {

      //create order
      $order = Order::create($orderData);

      //now save orderItems

      $order->items()->createMany($orderItemsData);

      $isUserInfoUpdated = false;

      //now clear customer cart

      if ($user) {

        //if user has no address
        //then save address for user
        Address::create([
          'customer_id' => $user->id,
          'address' => $request->input('customer_address'),
          'city' => $request->input('customer_city'),
          'zone' => $request->input('customer_zone'),
          'customer_name' => $request->input('customer_name'),
          'mobile' => $request->input('customer_mobile'),
        ]);

        //if user has no profile name

        if ($user->name == 'online-customer') {
          $user->name = $request->input('customer_name');
          $user->save();
        }

        $isUserInfoUpdated = true;

        $user->carts()->delete();
      }

      DB::commit();

      return $this->responseWithData([
        'message' => 'Order created successfully',
        'order_no' => $order->order_no,
        'user_info_updated' => $isUserInfoUpdated
      ]);

    } catch (\Exception $e) {
      DB::rollBack();
      return $this->handleException($e);
    }


  }


  public function show(string $id)
  {
    $order = Order::with('items.product', 'items.variation')
      ->find($id);

    return response()->json($order);
  }


  public function destroy(string $id)
  {
    //
  }
}
