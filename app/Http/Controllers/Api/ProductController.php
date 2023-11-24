<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
  function categories()
  {
    $categories = Category::with(['children' => function ($query) {
      $query->select('id', 'name', 'slug', 'parent_id', 'image')
        ->with(['children' => function ($query) {
          $query->select('id', 'name', 'slug', 'parent_id', 'image');
        }]);
    }])
      ->select('id', 'name', 'slug', 'parent_id', 'image')
      ->whereNull('parent_id')
      ->get();

    return response()->json($categories);
  }

}
