<?php

namespace App\Http\Controllers;

use App\Models\Carousel;
use App\Models\CarouselSlide;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CarouselController extends Controller
{
  function index()
  {
    $carousels = Carousel::withCount('slides')->get();

    return view("settings.carousel.index", compact('carousels'));
  }

  function create()
  {
    return view("settings.carousel.create");
  }

  function store(Request $request)
  {
    $validData = $request->validate([
      "name" => "required",
      "description" => "required",
    ]);

    //check if any active carousel

    $activeCarousel = Carousel::where('status', 1)
      ->first();

    if (empty($activeCarousel)) {
      $validData['status'] = 1;
    }

    Carousel::create($validData);

    return redirect()->route('carousels.index');

  }

  function edit($id)
  {
    $carousel = Carousel::findOrFail($id);

    return view('settings.carousel.edit', compact('carousel'));
  }

  function update(Request $request, $id)
  {
    $validData = $request->validate([
      "name" => "required",
      "description" => "required",
    ]);

    $carousel = Carousel::findOrFail($id);

    $carousel->update($validData);

    return redirect()->route('carousels.index');
  }

  function show($id)
  {
    $carousel = Carousel::with(['slides' => function ($query) {
      $query->orderBy('order');
    }])->findOrFail($id);

    return view('settings.carousel.show', compact('carousel'));
  }

  function makeActive($id)
  {
    $carousel = Carousel::findOrFail($id);
    //find previous active carousel
    //inactive previous all active carousel
    Carousel::where('status', 1)
      ->update(['status' => 0]);
    $carousel->status = 1;
    $carousel->save();

    toastr()->success('Carousel activated successfully');

    return redirect()->back();

  }

  function destroy($id)
  {
    $carousel = Carousel::findOrFail($id);
    $carousel->delete();

    return response()->json(['status' => 'success', 'message' => 'Carousel Deleted']);
  }

  //add slide

  function addSlide(Request $request, $id)
  {

    $validator = Validator::make($request->all(), [
      'slides.*' => 'required|mimes:jpg,jpeg,png|max:2048',
    ]);

    if ($validator->fails()) {
      toastr()->error($validator->errors()->first());
      return redirect()->back()->withErrors(['message' => $validator->errors()->first()]);
    }

    $carousel = Carousel::findOrFail($id);

    //get max order of this carousel slide

    $maxOrder = $carousel->slides()->max('order');

    //upload slides

    $slides = (new FileService())->uploadMulti($request, 'slides');

    //now save each slide to Carousel Slide
    foreach ($slides as $slide) {

      $maxOrder = $maxOrder + 1;

      $carousel->slides()->create([
        'image' => $slide,
        'order' => $maxOrder,
      ]);
    }

    toastr()->success('Slide added successfully');

    return redirect()->back();

  }

  function reorder($id, Request $request)
  {


    $slide = CarouselSlide::findOrFail($id);

    $action = request('action');

    if ($action == 'up') {
      $adjacentSlide = CarouselSlide::where('order', '<', $slide->order)
        ->orderBy('order', 'desc')
        ->first();
    } else {
      $adjacentSlide = CarouselSlide::where('order', '>', $slide->order)
        ->orderBy('order')
        ->first();
    }

    if (!empty($adjacentSlide)) {

      // Swap the order values between the current image and the adjacent image
      $tempOrder = $slide->order;
      $slide->order = $adjacentSlide->order;
      $adjacentSlide->order = $tempOrder;

      $slide->save();
      $adjacentSlide->save();

      toastr()->success('Slide reordered successfully');

    } else {
      toastr()->error('Unable to reorder slide');
    }


    return redirect()->back();

  }

  function removeSlide($id)
  {
    $slide = CarouselSlide::findOrFail($id);

    //remove the image
    Storage::delete($slide->image);

    $slide->delete();

    return response()->json(['status' => 'success', 'message' => 'Slide Removed']);
  }

}
