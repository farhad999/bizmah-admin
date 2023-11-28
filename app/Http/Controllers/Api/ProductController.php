<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Variation;
use App\Models\VariationTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
  function index(Request $request)
  {

    $query = Product::join('variations', 'products.id', '=', 'variations.product_id')
      ->select('products.id', 'products.name', 'products.slug', 'products.image', 'type', 'sku',
        DB::raw('(SELECT(MAX(variations.price)) as price FROM variations WHERE variations.product_id = products.id) as max_price'),
        DB::raw('(SELECT(MIN(variations.price)) as price FROM variations WHERE variations.product_id = products.id) as min_price'),
        DB::raw('(SELECT(MAX(variations.old_price)) as price FROM variations WHERE variations.product_id = products.id) as max_old_price'),
        DB::raw('(SELECT(MIN(variations.old_price)) as price FROM variations WHERE variations.product_id = products.id) as min_old_price')
      )
      ->where('visibility', 1);

    $orderBy = $request->input('sort', 'default');
    $categoryId = $request->input('category_id', null);
    $categorySlug = $request->input('category_slug', null);
    $subCategoryId = $request->input('sub_category_id', null);
    $subSubCategoryId = $request->input('sub_sub_category_id', null);
    $brandId = $request->input('brand_id', null);
    $min = $request->input('min', 0);
    $max = $request->input('max', null);

    if (!empty($categoryId || $categorySlug)) {
      $query->where('category_id', $categoryId);
    }

    if (!empty($subCategoryId)) {
      $query->where('sub_category_id', $subCategoryId);
    }

    if (!empty($subSubCategoryId)) {
      $query->where('sub_sub_category_id', $subSubCategoryId);
    }

    if (!empty($brandId)) {
      $query->where('brand_id', $brandId);
    }

    //variation filtering

    $templates = $request->input('variation_templates', []);
    $values = $request->input('variation_values', []);

    /*if (!empty($templates)) {

      $templates = explode(',', $templates);

      $query->where(function ($query) use ($templates) {
        foreach ($templates as $template) {
          $query->orWhere('products.template', 'Like', "%$template%");
        }
      });
    }*/

    if (!empty($values)) {
      $values = explode(',', $values);

      $query->where(function ($query) use ($values) {
        foreach ($values as $value) {
          $query->orWhere('variations.name', $value)
            ->orWhere('variations.name', 'LIKE', $value.'|%')
            ->orWhere('variations.name', 'LIKE', '%|'.$value.'|%')
            ->orWhere('variations.name', 'LIKE', '%|'.$value);
        }
      });

    }

    $query->groupBy('products.id');

    if (!empty($min)) {
      $query->having('min_price', '>=', $min);
    }
    if (!empty($max)) {
      $query->having('max_price', '<=', $max);
    }

    switch ($orderBy) {
      case 'name_asc':
        $query->orderBy('name');
        break;
      case 'name_desc':
        $query->orderBy('name', 'desc');
        break;
      case 'price_asc':
        $query->orderBy('min_price');
        break;
      case 'price_desc':
        $query->orderBy('min_price', 'desc');
        break;
      case "date_asc":
        $query->orderBy('products.created_at');
        break;
      case "date_desc":
        $query->orderBy('products.created_at', 'desc');
        break;
      case 'default':
        $query->orderBy('products.created_at', 'desc');
    }

    $products = $query->paginate(10);

    return response()->json($products);

  }

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

  function show($slug)
  {
    $product = Product::with(['variations', 'images'])
      ->where('slug', $slug)
      ->first();

    if (empty($product)) {
      return response(['status' => 'error', 'message' => 'Product not found'], 404);
    }

    return response()->json($product);
  }

  function getFilters()
  {
    $filters = VariationTemplate::select('id', 'name', 'values')
      ->get()
      ->map(function ($filter) {
        return [
          'id' => $filter->id,
          'name' => $filter->name,
          'values' => collect(explode(',', $filter->values))->sort()->values(),
        ];
      });

    return response()->json($filters);
  }

}
