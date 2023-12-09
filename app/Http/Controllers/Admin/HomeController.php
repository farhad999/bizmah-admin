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

  function getLast30daysOrders()
  {
    $endDate = now();
    $startDate = now()->subDays(29);

    // Fetch data for the last 30 days
    $data = Order::whereDate('date', '>=', $startDate)
      ->whereDate('date', '<=', $endDate)
      ->select(DB::raw('DATE(date) as date'), DB::raw('COUNT(id) as total'))
      ->groupBy(DB::raw('DATE(date)'))
      ->orderBy('date', 'asc')
      ->get();

    // Fill in missing dates with zero values
    $filledData = [];

    $currentDate = $startDate;

    while ($currentDate <= $endDate) {

      $matchingEntry = $data->first(function ($entry) use ($currentDate) {
        return $entry->date === $currentDate->format('Y-m-d');
      });

      if ($matchingEntry) {
        $filledData[] = $matchingEntry;
      } else {
        // If no matching entry, create an entry with zero value
        $filledData[] = (object)['date' => $currentDate->format('Y-m-d'), 'total' => 0];
      }

      $currentDate->addDay();
    }

    return response()->json($filledData);

  }

  function getComparisonOrders()
  {

    //data of last month

    $startDate = now()->subMonth(1)->startOfMonth();
    $endDate = now()->subMonth(1)->endOfMonth();

    //now get orders and count them as before
    $lastMonthOrders = Order::whereDate('date', '>=', $startDate)
      ->whereDate('date', '<=', $endDate)
      ->select(DB::raw('DATE(date) as date'), DB::raw('COUNT(id) as total'))
      ->groupBy(DB::raw('DATE(date)'))
      ->orderBy('date', 'asc')
      ->get();

    //fill for all date

    $lastMonthData = [];


    $currentDate = $startDate;

    while ($currentDate <= $endDate) {


      $matchingEntry = $lastMonthOrders->first(function ($entry) use ($currentDate) {
        return $entry->date === $currentDate->format('Y-m-d');
      });

      if ($matchingEntry) {
        $lastMonthData[] = $matchingEntry;
      } else {
        // If no matching entry, create an entry with zero value
        $lastMonthData[] = (object)['date' => $currentDate->format('Y-m-d'), 'total' => 0];
      }

      $currentDate->addDay();

    }

    //do for current month

    $startDate = now()->startOfMonth();
    $endDate = now()->endOfMonth();

    //now get orders and count them as before

    $currentMonthOrders = Order::whereDate('date', '>=', $startDate)
      ->whereDate('date', '<=', $endDate)
      ->select(DB::raw('DATE(date) as date'), DB::raw('COUNT(id) as total'))
      ->groupBy(DB::raw('DATE(date)'))
      ->orderBy('date', 'asc')
      ->get();

    //fill for all date

    $currentMonthData = [];


    $currentDate = $startDate;

    while ($currentDate <= $endDate) {

      $matchingEntry = $currentMonthOrders->first(function ($entry) use ($currentDate) {
        return $entry->date === $currentDate->format('Y-m-d');
      });

      if ($matchingEntry) {
        $currentMonthData[] = $matchingEntry;
      } else {
        // If no matching entry, create an entry with zero value
        $currentMonthData[] = (object)['date' => $currentDate->format('Y-m-d'), 'total' => 0];
      }

      $currentDate->addDay();

    }

    return response()->json([
      'lastMonth' => $lastMonthData,
      'currentMonth' => $currentMonthData
    ]);


  }

}
