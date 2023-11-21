<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\VariationTemplate;
use App\Services\FileService;
use App\Models\Variation;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  function index()
  {

    if (\request()->ajax()) {

      $query = Product::with(['category', 'brand', 'variations']);

      return datatables()->of($query)
        ->addColumn('category_name', function ($row) {
          return $row->category->name ?? '';
        })
        ->addColumn('brand_name', function ($row) {
          return $row->brand->name ?? '';
        })
        ->editColumn('visibility', function ($row) {
          if ($row->visibility) {
            return "<span class='badge bg-success'>Visible</span>";
          } else {
            return "<span class='badge bg-warning'>Hidden</span>";
          }
        })->addColumn('action', function ($row) {
          return view('product.action-buttons', compact('row'));
        })
        ->editColumn('image', function ($row) {
          return "<img src='" . $row->image_url . "' class='table-thumb' />";
        })
        ->addColumn('price', function ($row){
          $min = $row->variations->pluck('price')->min();
          $max = $row->variations->pluck('price')->max();
          return $min . ' - ' . $max;
        })
        ->rawColumns(['visibility', 'action', 'image'])
        ->make(true);
    }

    return view("product.index");
  }

  function create()
  {

    $categories = Category::getForDropdown();
    $brands = Brand::getForDropdown();

    if (\request()->ajax()) {
      $templates = VariationTemplate::getForDropdown();
      $type = \request()->input('type');
      if ($type == 'variable') {
        return view('product.partials.variable-product', compact('templates'));
      } else {
        return view('product.partials.single-product');
      }
    }

    return view('product.create', compact('categories', 'brands'));
  }

  function show($id)
  {
    $product = Product::with(['variations', 'category', 'subCategory', 'subSubCategory', 'brand'])
      ->findOrFail($id);

    return view('product.partials.view-modal', compact('product'));
  }


  function store(Request $request)
  {

    $request->validate([
      'name' => 'required',
      'variations.*.price' => 'required',
      'variations.*.name' => 'required',
      'sku' => 'required:unique:products',
      'image' => 'mimes:jpeg,png,jpg'
    ]);

    $productType = $request->input('type');
    $template = null;
    if ($productType == 'variable') {
      $templateIds = $request->input('templates');
      $template = VariationTemplate::whereIn('id', $templateIds)
        ->pluck('name')->join('|');
    }

    $productData = $request->only(['name', 'short_description', 'description', 'category_id',
      'sub_category_id', 'sub_sub_category_id', 'brand_id', 'visibility', 'type'
    ]);

    $productData['sku'] = $request->input('sku') ?? 'sku001';
    $productData['image'] = (new FileService())->upload($request, 'image');
    $productData['template'] = $template;

    $galleryImages = (new FileService())->uploadMulti($request, 'gallery_images');

    DB::beginTransaction();

    try {

      $product = Product::create($productData);

      //add gallery images

      if (!empty($galleryImages)) {
        $images = array_map(function ($item) {
          return [
            'image' => $item,
          ];
        }, $galleryImages);
        $product->images()->createMany($images);
      }

      //variations
      $variations = $request->variations;

      foreach ($variations as $index => $variation) {
        $image = null;
        if (array_key_exists('image', $variation) && $request->hasFile("variations.{$index}.image")) {
          $image = (new FileService())->fileUpload($variation['image']);
        }
        //Variation
        Variation::create([
          'product_id' => $product->id,
          'name' => $variation['name'],
          'old_price' => $variation['old_price'],
          'price' => $variation['price'],
          'image' => $image,
        ]);
      }

      DB::commit();

      return redirect()->route("products.index");

    } catch (\Exception $exception) {
      DB::rollBack();
      toastr()->error($exception->getMessage());
      return redirect()->back()->withErrors(['message' => $exception->getMessage()]);
    }

  }

  function edit($id)
  {
    $product = Product::with(['variations', 'category', 'subCategory', 'subSubCategory', 'brand'])
      ->findOrFail($id);

    $categories = Category::getForDropdown();

    $subCategories = Category::whereNotNull('parent_id')
      ->where('parent_id', $product->category_id)
      ->pluck('name', 'id');

    $subSubCategories = Category::whereNotNull('parent_id')
      ->where('parent_id', $product->sub_category_id)
      ->pluck('name', 'id');

    $brands = Brand::getForDropdown();

    return view('product.edit', compact('product', 'categories', 'subCategories', 'subSubCategories', 'brands'));
  }

  function update(Request $request, $id)
  {

    $product = Product::findOrFail($id);

    $request->validate([
      'name' => 'required',
      'variations.*.price' => 'required',
      'sku' => 'required|unique:products,id,' . $id,
      'image' => 'mimes:jpeg,png,jpg'
    ]);

    $productData = $request->only(['name', 'short_description', 'description', 'category_id',
      'sub_category_id', 'sub_sub_category_id', 'brand_id', 'visibility'
    ]);

    $productData['sku'] = $request->input('sku') ?? 'sku001';
    $image = (new FileService())->upload($request, 'image');
    //update image if image provided
    if (!empty($image)) {
      $productData['image'] = $image;
    }

    DB::beginTransaction();

    try {

      $product->update($productData);

      //variations
      $variations = $request->variations;

      $existingVariationIds = $product->variations->pluck('id')->toArray();

      foreach ($variations as $index => $v) {

        //variation

        $variationId = $v['id'] ?? null;

        if ($variationId) {
          $variation = Variation::find($variationId);
        } else {
          $variation = new Variation();
          $variation->product_id = $product->id;
        }

        if (array_key_exists('image', $v) && $request->hasFile("variations.{$index}.image")) {
          $image = (new FileService())->fileUpload($variation['image']);
          $variation['image'] = $image;
        }

        $variation->name = $v['name'];
        $variation->old_price = $v['old_price'];
        $variation->price = $v['price'];
        $variation->save();

      }


      //now remove if any variations deleted
      $inputVariationIds = collect($variations)->pluck('id')->toArray();
      $variationsToRemove = array_diff($existingVariationIds, $inputVariationIds);
      Variation::destroy($variationsToRemove);

      DB::commit();

      toastr()->success('Product updated successfully');

      return redirect()->route("products.index");

    } catch (\Exception $exception) {
      DB::rollBack();
      toastr()->error($exception->getMessage());
      return redirect()->back();
    }

  }

  function getVariationTemplate()
  {

    $id = \request()->input('id');
    //$ids = explode(',', $id);
    $templates = VariationTemplate::whereIn('id', $id)
      ->get();

    return view('product.partials.variation-template', compact('templates'));
  }

  function createVariation(Request $request)
  {
    $variation = $request->input('variation');

    $variations = explode('|', $variation);

    $variations = $this->cartesianProduct($variations);

    return view('product.partials.variations', compact('variations'));

  }

  private function cartesianProduct($arrays, $current = [], $result = [])
  {
    if (empty($arrays)) {
      return $result;
    }

    $value = array_shift($arrays);
    $firstArray = explode(',', $value);

    foreach ($firstArray as $value) {
      $current[] = $value;
      if (empty($arrays)) {
        $result[] = implode('|', $current);
      } else {
        $result = $this->cartesianProduct($arrays, $current, $result);
      }
      array_pop($current);
    }

    array_unshift($arrays, $firstArray); // Restore the arrays for the next iteration

    return $result;
  }

}
