<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends ApiController
{

  function __construct()
  {
    parent::__construct();
  }

  function index()
  {

    $user = auth()->user();

    //$locationId = $settings->location_id;

    $carts = Cart::where('customer_id', '=', $user->id)->get();

    $products = Product::with('variations')
      ->whereIn('id', $carts->pluck('product_id'))
      ->get();

    return response()->json(['carts' => $carts, 'products' => $products]);

  }

  function store(Request $request)
  {

    $user = \auth()->user();

    $request->validate(
      [
        'product_id' => 'required|numeric',
        'variation_id' => 'required|numeric',
        'quantity' => 'required|numeric'
      ]
    );

    //add to cart

    $productId = \request()->input('product_id');
    $variationId = \request()->input('variation_id');
    $qty = \request()->input('quantity');
    //get the cart
    $cart = Cart::where('product_id', '=', $productId)
      ->where('customer_id', '=', $user->id)
      ->where('variation_id', '=', $variationId)
      ->first();

    if (!empty($cart)) {
      return $this->respondWithError('Product already in cart');
    }

    if (!$cart) {
      $cart = new Cart();
      $cart['customer_id'] = $user->id;
      $cart['product_id'] = $productId;
      $cart['quantity'] = $qty;
      $cart['variation_id'] = $variationId;
    }

    $cart->save();

    return response(['status' => 'success', 'cart' => $cart]);

  }

  function updateQty(Request $request)
  {

    \request()->validate([
      'quantity' => 'required|numeric|min:0',
      'variation_id' => 'required'
    ]);

    $variation_id = \request()->input('variation_id');
    $user = \auth()->user();
    //find the cart
    $cart = Cart::where('customer_id', '=', $user->id)
      ->where('variation_id', '=', $variation_id)
      ->first();

    $qty = \request()->input('quantity');
    $cart['quantity'] = $qty;
    $cart->save();

    return $this->responseWithSuccess("Quantity Updated");
  }

  function destroy()
  {

    $user = \auth()->user();
    $variation_id = \request()->input('variation_id');

    $cartItem = Cart::where('customer_id', '=', $user->id)
      ->where('variation_id', '=', $variation_id)
      ->first();

    if ($cartItem) {
      $cartItem->delete();
      return $this->responseWithSuccess('Successfully Removed');
    }

    return $this->responseWithFailed('Something went wrong');

  }

  function sync()
  {

    $user = auth()->user();

    \request()->validate([
      'carts' => 'required | array'
    ]);

    $localCarts = \request()->input('carts');

    $carts = Cart::where('contact_id', '=', $user->id)
      ->get();

    foreach ($localCarts as $lc) {
      $flag = 0;

      foreach ($carts as $c) {
        if ($lc['product_id'] == $c['product_id'] && $lc['variation_id'] == $c['variation_id']) {
          $c['quantity'] = $lc['quantity'] + $c['quantity'];
          $c->save();
          $flag = 1;
        }
      }
      if ($flag == 0) {
        try {
          $ct = new Cart();
          $ct['product_id'] = $lc['product_id'];
          $ct['variation_id'] = $lc['variation_id'];
          $ct['quantity'] = $lc['quantity'];
          $ct['contact_id'] = $user->id;
          $ct->save();
        } catch (\Exception $exception) {
          response($exception);
        }

        echo "success";
      }
    }

    return $this->responseWithSuccess('sync successful');

  }
}
