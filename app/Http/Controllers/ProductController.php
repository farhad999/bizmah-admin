<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\VariationTemplate;
use App\Services\FileService;
use App\Models\Variation;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProductController extends Controller
{

  private $fileService;

  function __construct(FileService $fileService)
  {
    $this->fileService = $fileService;
  }

  function index()
  {

    if (\request()->ajax()) {

      $query = Product::with(['category', 'brand', 'variations'])
      ->orderBy('created_at', 'desc');

      if (\request()->input('category_id')) {
        $query->where('category_id', \request()->input('category_id'));
      }

      if (\request()->input('sub_category_id')) {
        $query->where('sub_category_id', \request()->input('sub_category_id'));
      }

      if(\request()->input('sub_sub_category_id')) {
        $query->where('sub_sub_category_id', \request()->input('sub_sub_category_id'));
      }

      if (\request()->input('brand_id')) {
        $query->where('brand_id', \request()->input('brand_id'));
      }

      if (\request()->input('visibility')) {
        $query->where('visibility', \request()->input('visibility'));
      }



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
        ->addColumn('price', function ($row) {
          $min = $row->variations->pluck('price')->min();
          $max = $row->variations->pluck('price')->max();

          if ($min == $max) {
            return $min;
          }

          return $min . ' - ' . $max;
        })
        ->rawColumns(['visibility', 'action', 'image'])
        ->make(true);
    }

    $categories = Category::getForDropdown();

    return view("product.index", compact('categories'));
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
      'image' => 'required|mimes:jpeg,png,jpg|max:2048',
      'secondary_image' => 'mimes:jpeg,png,jpg|max:2048',
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
    $productData['secondary_image'] = (new FileService())->upload($request, 'secondary_image');

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
      'image' => 'mimes:jpeg,png,jpg|max:2048',
      'secondary_image' => 'mimes:jpeg,png,jpg|max:2048',
    ]);

    $productData = $request->only(['name', 'short_description', 'description', 'category_id',
      'sub_category_id', 'sub_sub_category_id', 'brand_id', 'visibility'
    ]);

    $productData['sku'] = $request->input('sku') ?? 'sku001';
    $image = (new FileService())->upload($request, 'image');
    $secondaryImage = (new FileService())->upload($request, 'secondary_image');
    //update image if image provided
    if (!empty($image)) {

      $this->fileService->delete($product->image);

      $productData['image'] = $image;
    }

    if (!empty($secondaryImage)) {

      $this->fileService->delete($product->secondary_image);

      $productData['secondary_image'] = $secondaryImage;
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

          $image = (new FileService())->upload($request, "variations.{$index}.image");

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

  function destroy($id)
  {
    $product = Product::findOrFail($id);

    $this->fileService->delete($product->image);
    $this->fileService->delete($product->secondary_image);

    $product->delete();
    return response()->json(['status' => 'success', 'message' => 'Product deleted successfully']);
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

  function imageGallery($id)
  {
    $product = Product::findOrFail($id);

    return view('product.partials.image-gallery', compact('product'));
  }

  function upload(Request $request, $id)
  {
    $product = Product::findOrFail($id);

    $images = (new FileService())->uploadMulti($request, 'images');

    if (!empty($images)) {
      foreach ($images as $image) {
        ProductImage::create([
          'product_id' => $product->id,
          'image' => $image,
        ]);
      }
    }

    return response()->json(['status' => 'success']);

  }

  function loadImages($id)
  {

    $images = ProductImage::where('product_id', $id)
      ->get();

    return view('product.partials.image-gallery-container', compact('images'));

  }

  function deleteGalleryImage($id)
  {

    try {

      $imageId = \request()->input('image_id');

      $image = ProductImage::findOrFail($imageId);

      $this->fileService->delete($image->image);

      $image->delete();

      return response(['status' => 'success', 'message' => 'Image Successfully removed']);
    } catch (\Exception $exception) {
      return response(['status' => 'error', 'message' => $exception->getMessage()]);
    }
  }

  function search()
  {
    $q = \request()->input('q');
    $products = Product::where('name', 'like', '%' . $q . '%')->get();
    return response()->json($products);
  }

  function getVariation()
  {
    $id = \request()->input('id');
    $variation = Variation::findOrFail($id);
    return response()->json($variation);
  }

}
