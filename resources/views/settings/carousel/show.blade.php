@extends('layouts.layoutMaster')

@section('content')
  <x-content title="Add New Slide">
    <x-slot name="buttons">
      <a href="{{route("carousels.index")}}" class="btn btn-primary">Back</a>
    </x-slot>
    <!-- Add Slide for carousel --->

    <x-error-alert/>

    <x-form action="{{route('carousels.add-slide', $carousel->id)}}">

      <div class="row">
        {{--<div class="col-12">
          <h4>Add New Slide</h4>
        </div>--}}

        <div class="col-12">
          <x-form.file-input
            name="slides[]"
            accept="image/*"
          />
        </div>

        <div class="d-flex justify-content-end">
          <button class="btn btn-primary">Upload</button>
        </div>

      </div>

    </x-form>

    <hr/>

    <h3>Slides</h3>

    <table class="table table-bordered">
      <thead>
      <tr>
        <th>Slide</th>
        <th>Action</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($carousel->slides as $slide)
        <tr>
          <td class="w-75" style="overflow: hidden">
            <img src="{{$slide->image_url}}" alt=""
                 style="height: 200px"
            >
          </td>
          <td>
            <form action="{{ route('carousels.reorder-slide', $slide->id) }}" method="post" style="display: inline;">
              @csrf
              <button type="submit" name="action" value="up" class="btn btn-success btn-sm">Move Up</button>
              <button type="submit" name="action" value="down" class="btn btn-warning btn-sm">Move Down</button>
            </form>
            <button class="btn btn-danger btn-sm delete-item-btn"
                    data-href="{{route('carousels.delete-slide', $slide->id)}}"
            >Remove
            </button>
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>


  </x-content>
@endsection

@section('js')

@endsection
