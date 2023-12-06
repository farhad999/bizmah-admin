<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
  function index()
  {
    $pages = Page::all();
    return view("page.index", ['pages' => $pages]);
  }

  function create()
  {
    return view("page.create");
  }

  function store(Request $request)
  {
    $validData = $request->validate([
      "title" => "required",
      'content' => 'required',
    ]);

    Page::create($validData);

    toastr()->success('Page created successfully');

    return redirect()->route('pages.index');

  }

  function edit(Page $page){
    return view('page.edit', ['page' => $page]);
  }

  function update(Request $request, Page $page){
    $validData = $request->validate([
      "title" => "required",
      'slug' => 'required|unique:pages,slug,' . $page->id,
      'content' => 'string',
    ]);

    $page->update($validData);

    toastr()->success('Page updated successfully');

    return redirect()->route('pages.index');

  }

  function destroy(Page $page){
    $page->delete();
    return response()->json([
      'status' => 'success',
      'message' => 'Page deleted successfully',
    ]);
  }

}
