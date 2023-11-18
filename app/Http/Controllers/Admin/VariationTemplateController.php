<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VariationTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VariationTemplateController extends Controller
{
  function index()
  {

    if (\request()->ajax()) {
      $query = VariationTemplate::query();
      return datatables()->of($query)
        ->addColumn('action', function ($row) {
          return view('variation-template.action-buttons', compact('row'));
        })
        ->editColumn('status', function ($row) {
          if ($row->status == 1) {
            return '<span class="badge bg-success">Active</span>';
          } else {
            return '<span class="badge bg-danger">Inactive</span>';
          }
        })
        ->rawColumns(['action', 'status'])
        ->make(true);
    }

    return view('variation-template.index');
  }

  function create()
  {
    return view('variation-template.create');
  }

  function store(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'values' => 'required',
      'status' => 'required'
    ]);

    if ($validator->fails()) {
      toastr()->error($validator->errors()->first());
      return redirect()->back();
    }

    $data = $validator->safe()->all();

    VariationTemplate::create($data);
    toastr()->success('Variation Template Created');
    return redirect()->route("variation-templates.index");

  }

  function edit($id)
  {
    $template = VariationTemplate::find($id);

    return view('variation-template.edit', compact('template'));
  }

  function update(Request $request, $id)
  {

    $validator = Validator::make($request->all(), [
      'name' => 'required',
      'values' => 'required',
      'status' => 'required'
    ]);

    if ($validator->fails()) {
      toastr()->error($validator->errors()->first());
      return redirect()->back();
    }

    $data = $validator->safe()->all();
    $template = VariationTemplate::findOrFail($id);
    $template->update($data);
    toastr()->success('Variation Template Updated');
    return redirect()->route("variation-templates.index");

  }

  function destroy($id){
    $template = VariationTemplate::findOrFail($id);
    $template->delete();

    return response()->json(['status' => 'success', 'message' => 'Variation Template Deleted']);
  }

}
