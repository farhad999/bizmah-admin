<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryCollection;
use Illuminate\Http\Request;

class FeaturedCategoryController extends Controller
{
  function index()
  {

    $featuredCategories = CategoryCollection::join("categories", "category_collections.category_id", "=", "categories.id")
      ->where('type', 'featured')
      ->orderBy('order', 'asc')
      ->select('category_collections.*', 'categories.name', 'categories.image')
      ->get();

    $categories = Category::whereNotIn('id', $featuredCategories->pluck('category_id')->toArray())
      ->whereNull('parent_id')
      ->where('visibility', 1)
      ->get();

    return view('settings.featured-category.index', compact('categories', 'featuredCategories'));
  }

  function add($id)
  {
    $order = CategoryCollection::where('type', 'featured')->max('order') + 1;

    CategoryCollection::create([
      'category_id' => $id,
      'type' => 'featured',
      'order' => $order,
    ]);

    toastr()->success('Category added successfully');
    return redirect()->back();

  }

  function updateOrder(Request $request)
  {

    $order = 1;

    foreach ($request->input('categories') as $category) {
      CategoryCollection::where('id', $category)
        ->update(['order' => $order]);
      $order++;
    }
    toastr()->success('Order updated successfully');
    return redirect()->back();

  }

  function destroy($id)
  {
    $cat = CategoryCollection::find($id);
    $cat->delete();
    return response()->json(['status' => 'success', 'message' => 'Deleted successfully']);
  }

}
