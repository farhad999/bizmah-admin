<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
  function index()
  {

    if (\request()->ajax()) {
      $query = Brand::query();
      return datatables()->of($query)
        ->editColumn('image', function ($row) {
          return '<img src="' . $row->image_url . '" class="table-thumb">';
        })
        ->addColumn('action', function ($row) {
          return view('brand.action-buttons', compact('row'));
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
        ->rawColumns(['image', 'action', 'visibility', 'status'])
        ->make(true);

    }

    return view("brand.index");
  }

  function create()
  {
    return view("brand.create");
  }

  function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'status' => 'required',
      'visibility' => 'required',
      'description' => 'string',
      'image' => 'required|mimes:image:jpeg,png,jpg|max:2048'
    ]);

    if ($validator->fails()) {
      toastr()->error($validator->errors()->first());
    }

    $data = $validator->safe()->except('image');

    $data['image'] = (new FileService())->upload($request, 'image');

    Brand::create($data);

    toastr()->success("Brand created successfully");

    return redirect()->route('brands.index');

  }

  function edit($id)
  {
    $brand = Brand::find($id);
    return view('brand.edit', compact('brand'));
  }

  function update(Request $request, $id)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'status' => 'required',
      'visibility' => 'required',
      'description' => 'string',
      'image' => 'mimes:image:jpeg,png,jpg|max:2048'
    ]);

    if ($validator->fails()) {
      toastr()->error($validator->errors()->first());
    }

    $brand = Brand::findOrFail($id);

    $data = $validator->safe()->except('image');

    $image = (new FileService())->upload($request, 'image');

    if (!empty($image)) {
      unlink(storage_path('app/public/' . $brand->image));
      $data['image'] = $image;
    }

    $brand->update($data);

    toastr()->success('Brand updated successfully');

    return redirect()->route('brands.index');
  }

  function destroy($id)
  {
    $brand = Brand::find($id);
    unlink(storage_path('app/public/' . $brand->image));
    $brand->delete();

    return response()->json(['status' => 'success', 'message' => 'Brand deleted successfully']);
  }

}
