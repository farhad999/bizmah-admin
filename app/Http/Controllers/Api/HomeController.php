<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Carousel;
use App\Models\Category;
use App\Models\CategoryCollection;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
  function index()
  {

  }

  function latestProducts()
  {

    $query = Product::join('variations', 'products.id', '=', 'variations.product_id')
      ->select('products.id', 'products.name', 'products.slug', 'products.image', 'type', 'sku',
        DB::raw('(SELECT(MAX(variations.price)) as price FROM variations WHERE variations.product_id = products.id) as max_price'),
        DB::raw('(SELECT(MIN(variations.price)) as price FROM variations WHERE variations.product_id = products.id) as min_price'),
        DB::raw('(SELECT(MAX(variations.old_price)) as price FROM variations WHERE variations.product_id = products.id) as max_old_price'),
        DB::raw('(SELECT(MIN(variations.old_price)) as price FROM variations WHERE variations.product_id = products.id) as min_old_price')
      )->where('visibility', 1)
      ->groupBy('products.id')
      ->orderBy('products.created_at', 'desc');

    $products = $query->paginate(5);

    return response()->json($products);

  }

  function getSettings()
  {
    $settings = Setting::where('group', 'general')
      ->pluck('value', 'name');

    return response()->json($settings);
  }

  function getFeaturedCategories()
  {
    $categories = Category::with('children')
      ->join('category_collections', 'categories.id', '=', 'category_collections.category_id')
      ->where('type', 'featured')
      ->where('visibility', 1)
      ->orderBy('category_collections.order', 'asc')
      ->select('categories.id', 'categories.name', 'categories.slug', 'categories.image')
      ->get();

    return response()->json($categories);

  }

  function homeSlides()
  {
    $carousel = Carousel::with('slides')
      ->where('status', 1)
      ->first();

    $sliders = $carousel->slides;
    return response()->json($sliders);
  }

}
