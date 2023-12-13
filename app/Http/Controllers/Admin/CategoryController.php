<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

  private $fileService = null;

  function __construct(FileService $fileService)
  {
    $this->fileService = $fileService;
  }

  function index()
  {
    if (\request()->ajax()) {
      $query = Category::with('parent');
      return datatables()->of($query)
        ->editColumn('image', function ($row) {
          return '<img src="' . $row->image_url . '" class="table-thumb">';
        })
        ->addColumn('action', function ($row) {
          return view('category.action-buttons', compact('row'));
        })
        ->editColumn('status', function ($row) {
          if ($row->status == 1) {
            return '<span class="badge bg-success">Active</span>';
          } else {
            return '<span class="badge bg-danger">Inactive</span>';
          }
        })
        ->editColumn('visibility', function ($row) {
          if ($row->visibility == 1) {
            return '<span class="badge bg-success">Visible</span>';
          } else {
            return '<span class="badge bg-danger">Hidden</span>';
          }
        })
        ->editColumn('banner_image', function ($row) {
          return '<img src="' . $row->banner_image_url . '" class="table-thumb">';
        })
        ->addColumn('parent_name', function ($row) {
          if ($row->parent) {
            return $row->parent->name;
          }
          return "Parent";
        })
        ->rawColumns(['image', 'action', 'visibility', 'status', 'banner_image', 'parent_name'])
        ->make(true);

    }

    return view('category.index');
  }

  function create()
  {

    $categories = Category::with('children')
      ->select('id', 'name', 'parent_id')
      ->whereNull('parent_id')
      ->get();

    return view('category.create', compact('categories'));
  }

  function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'status' => 'required',
      'visibility' => 'required',
      'description' => 'nullable|string',
      'parent_id' => 'nullable|numeric',
      'image' => 'required|mimes:image:jpeg,png,jpg|max:2048',
      'banner_image' => 'mimes:image:jpeg,png,jpg|max:2048',
    ]);

    if ($validator->fails()) {
      toastr()->error($validator->errors()->first());
    }

    $parentId = $request->input('parent_id');

    //$parent = Category::find($parentId);

    $data = $validator->safe()->except('image');

    //$data['level'] = $parent->level + 1;

    $data['image'] = (new FileService())->upload($request, 'image');
    $data['banner_image'] = (new FileService())->upload($request, 'banner_image');

    Category::create($data);

    toastr()->success("Category created successfully");

    return redirect()->route('categories.index');

  }

  function edit($id)
  {
    $category = Category::find($id);

    $categories = Category::with(['children' => function ($query) use ($category) {
      $query->where('id', '<>', $category->id);
    }])
      ->select('id', 'name', 'parent_id')
      ->whereNull('parent_id')
      ->where('id', '<>', $category->id)
      ->get();

    return view('category.edit', compact('category', 'categories'));
  }

  function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'status' => 'required',
      'visibility' => 'required',
      'description' => 'nullable|string',
      'image' => 'mimes:image:jpeg,png,jpg|max:2048',
      'banner_image' => 'mimes:image:jpeg,png,jpg|max:2048',
    ]);

    if ($validator->fails()) {
      toastr()->error($validator->errors()->first());
    }

    $category = Category::findOrFail($id);

    $data = $validator->safe()->except('image');

    $image = (new FileService())->upload($request, 'image');
    $bannerImage = (new FileService())->upload($request, 'banner_image');

    if (!empty($image)) {
      $this->fileService->delete($category->image);
      $data['image'] = $image;
    }

    if (!empty($bannerImage)) {
      $this->fileService->delete($category->banner_image);
      $data['banner_image'] = $bannerImage;
    }

    $category->update($data);

    toastr()->success('Category updated successfully');

    return redirect()->route('categories.index');
  }

  function destroy($id)
  {
    $category = Category::find($id);

    //check if category being used by another category
    //or in other products

    $categories = Category::where('parent_id', $category->id)
      ->get();

    if (count($categories) > 0) {
      return response()->json(['status' => 'error', 'message' => "Unable to delete. This category has sub categories. Try to update."]);
    }

    //unlink different so that no error

    $this->fileService->delete($category->image);
    $this->fileService->delete($category->banner_image);

    $category->delete();

    return response()->json(['status' => 'success', 'message' => 'Category deleted successfully']);
  }

  function getSubCategories()
  {
    $id = \request()->input('id');

    $categories = Category::where('parent_id', $id)
      ->whereNotNull('parent_id')
      ->select('id', 'name')
      ->pluck('name', 'id');

    return view("category.sub-categories", compact('categories'));

  }

}
