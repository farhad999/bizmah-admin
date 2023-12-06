<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
  public function index(Request $request)
  {

    if ($request->ajax()) {
      //date range
      $date_range = $request->input('date_range');

      $dateRange = explode(' - ', $date_range);

      $start = Carbon::createFromFormat('d/m/Y', $dateRange[0])->format('Y-m-d');
      $end = Carbon::createFromFormat('d/m/Y', $dateRange[1])->format('Y-m-d');

      //orders

      $orders = Order::whereDate('date', '>=', $start)
        ->whereDate('date', '<=', $end)
        ->get();

      $confirmedOrders = $orders->where('status', 'confirmed');
      $pendingOrders = $orders->where('status', 'pending');
      $cancelledOrders = $orders->where('status', 'cancelled');

      //top ordered product

      $topOrderedProducts = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->select('order_items.product_id', 'products.name', \DB::raw('sum(order_items.quantity) as total'))
        ->where('orders.status', 'confirmed')
        ->groupBy('order_items.product_id')
        ->orderBy('total', 'desc')
        ->get();

      //each day sales of last 30 days

      $eachDaySales = [];



      $last30daysSells = Order::whereDate('date', '>=', now()->subDays(30))
        ->whereDate('date', '<=', now())
        ->where('status', 'confirmed')
        ->select(DB::raw('DATE(date) as date'), \DB::raw('count(id) as total'))
        ->groupBy(DB::raw('DATE(date)'))
        ->get();



      $data = [
        'totalOrders' => $orders->count(),
        'totalConfirmedOrders' => $confirmedOrders->count(),
        'totalPendingOrders' => $pendingOrders->count(),
        'totalCancelledOrders' => $cancelledOrders->count(),
        'last30daysSells' => $last30daysSells,
        'topOrderedProductsTable' => view("home.partials.top-ordered-products", compact('topOrderedProducts'))
        ->render(),
      ];

      return response()->json($data);


    }

    return view('home.index');
  }
}
