<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Variation;
use App\Models\Zone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

  private $orderStatuses = [
    'pending' => 'Pending',
    'confirmed' => 'Confirmed',
    'cancelled' => 'Cancelled',
  ];

  private $shippingStatuses = [
    'processing' => 'Processing',
    'shipped' => 'Shipped',
    'delivered' => 'Delivered',
  ];

  private $deliveredTo = [
    'inside_dhaka' => 'Inside Dhaka',
    'outside_dhaka' => 'Outside Dhaka'
  ];

  function index(Request $request, $type = null)
  {

    if (request()->ajax()) {

      $query = Order::orderBy('date', 'desc');

      if ($type == 'confirmed') {
        $query->where('status', 'confirmed');
      }

      if ($type == 'pending') {
        $query->where('status', 'pending');
      }

      //status
      $status = $request->input('status');
      $shippingStatus = $request->input('shipping_status');

      if (!empty($status)) {
        $query->where('status', $status);
      }

      if (!empty($shippingStatus)) {
        $query->where('shipping_status', $shippingStatus);
      }

      //date range
      $dateRange = $request->input('date_range');

      if (!empty($dateRange)) {
        $dateRange = explode(' - ', $dateRange);
        $startDate = Carbon::createFromFormat('d/m/Y', $dateRange[0])->format('Y-m-d');
        $endDate = Carbon::createFromFormat('d/m/Y', $dateRange[1])->format('Y-m-d');

        $query->whereDate('date', '>=', $startDate)
          ->whereDate('date', '<=', $endDate);

      }


      return datatables()
        ->of($query)
        ->addColumn('action', function ($row) {
          return view('order.action', compact('row'));
        })
        ->editColumn('status', function ($row) {
          if ($row->status == 'confirmed') {
            return '<span class="badge bg-success">Confirmed</span>';
          } else if ($row->status == 'cancelled') {
            return '<span class="badge bg-danger">Cancelled</span>';
          } else {
            return '<span class="badge bg-warning text-capitalize">' . $row->status . '</span>';
          }
        })
        ->rawColumns(['action', 'status'])
        ->make(true);
    }

    return view('order.index', [
      'shippingStatuses' => $this->shippingStatuses,
      'orderStatuses' => $this->orderStatuses,
      'type' => $type
    ]);
  }

  function create()
  {

    $settings = Setting::where('group', 'general')
      ->get();

    $cities = City::getForDropdown();

    return view('order.create', [
      'orderStatuses' => $this->orderStatuses,
      'shippingStatuses' => $this->shippingStatuses,
      'deliveredTo' => $this->deliveredTo,
      'settings' => $settings,
      'cities' => $cities
    ]);
  }

  function store(Request $request)
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
      'shipping_charge', 'customer_id',
      'status', 'date', 'note',
    ]);

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

    $shippingCharge = $request->input('shipping_charge');

    //now items are valid
    $total = $subtotal + $shippingCharge;

    $orderData['subtotal'] = $subtotal;
    $orderData['shipping_charge'] = $shippingCharge;
    $orderData['total_amount'] = $total;
    $orderData['order_no'] = date('ymd') . rand(100000, 999999);
    $orderData['delivered_to'] = $deliveredTo;
    $orderData['source'] = 'pos';

    $orderData['shipping_address'] = $request->input('customer_address') . ', ' . $request->input('customer_zone') . ', ' . $request->input('customer_city');

    DB::beginTransaction();

    try {

      //create order
      $order = Order::create($orderData);

      //now save orderItems

      $order->items()->createMany($orderItemsData);

      DB::commit();
      toastr()->success('Order created successfully');

      return redirect()->route('orders.index');

    } catch (\Exception $e) {
      DB::rollBack();
      toastr()->error($e->getMessage());
      return redirect()->back()->withErrors(['message' => $e->getMessage()]);
    }

  }

  function show($id)
  {
    $order = Order::with(['items'])->find($id);
    return view('order.view-order-modal', compact('order'));
  }

  function edit($id)
  {
    $order = Order::with(['items.product.variations'])
      ->find($id);

    $settings = Setting::where('group', 'general')
      ->get();

    $cities = City::getForDropdown();

    $zones = Zone::join('cities', 'zones.city_id', '=', 'cities.id')
      ->where('cities.name', $order->customer_city)
      ->select('zones.name', 'zones.name')
      ->orderBy('zones.name', 'asc')
      ->pluck('zones.name', 'zones.name');

    return view('order.edit', [
      'order' => $order,
      'orderStatuses' => $this->orderStatuses,
      'shippingStatuses' => $this->shippingStatuses,
      'deliveredTo' => $this->deliveredTo,
      'settings' => $settings,
      'cities' => $cities,
      'zones' => $zones
    ]);

  }

  function update(Request $request, $id)
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
      'shipping_charge', 'customer_id',
      'status', 'date', 'shipping_status',
      'note',
    ]);

    $order = Order::findOrFail($id);

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

    $shippingCharge = $request->input('shipping_charge');

    //now items are valid
    $total = $subtotal + $shippingCharge;

    $orderData['subtotal'] = $subtotal;
    $orderData['shipping_charge'] = $shippingCharge;
    $orderData['total_amount'] = $total;
    $orderData['delivered_to'] = $deliveredTo;
    $orderData['shipping_address'] = $request->input('customer_address') . ', ' . $request->input('customer_zone') . ', ' . $request->input('customer_city');

    DB::beginTransaction();

    try {

      //create order
      $order->update($orderData);

      $order->items()->delete();

      //now save orderItems

      $order->items()->createMany($orderItemsData);

      DB::commit();
      toastr()->success('Order Updated successfully');

      return redirect()->route('orders.index');

    } catch (\Exception $e) {
      DB::rollBack();
      toastr()->error($e->getMessage());
      return redirect()->back()->withErrors(['message' => $e->getMessage()]);
    }
  }

  function getOrderRow()
  {

    $id = \request()->input('id');

    $product = Product::with('variations')
      ->findOrFail($id);

    $variations = $product->variations;

    $selectableVariations = $product->variations->pluck('name', 'id');

    $index = \request()->input('index') ?? 0;

    return view('order.order-item-row', compact('product', 'variations', 'selectableVariations', 'index'));
  }

  function updateStatus(Request $request, $id)
  {
    $order = Order::find($id);
    $order->status = $request->input('status');
    $order->save();
    return response()->json(['status' => 'success', 'message' => 'Order status updated']);
  }

  function updateShippingStatus(Request $request, $id)
  {
    $order = Order::find($id);
    $order->shipping_status = $request->input('shipping_status');
    $order->save();

    return response()->json(['status' => 'success', 'message' => 'Shipping status updated']);

  }

  function getZones(Request $request)
  {
    $cityName = $request->input('city_name');

    $zones = Zone::join('cities', 'zones.city_id', '=', 'cities.id')
      ->where('cities.name', $cityName)
      ->select('zones.name', 'zones.name')
      ->orderBy('zones.name', 'asc')
      ->get();

    return response()->json($zones);

  }

}
