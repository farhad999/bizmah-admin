@extends('layouts.layoutMaster')

@section('content')
  <x-content title="Carousels">
    <x-slot name="buttons">
      <a href="{{route("carousels.create")}}" class="btn btn-primary">Add Carousel</a>
    </x-slot>

    <table class="table table-hover" id="datatable">
      <thead>
      <tr>
        <th>Name</th>
        <th>Slides</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
      </thead>
      <tbody>
      @foreach ($carousels as $carousel)
        <tr>
          <td>{{$carousel->name}}</td>
          <td>{{$carousel->slides_count}}</td>
          <td>
            @if($carousel->status == 1)
              <span class="badge bg-success">Active</span>
            @else
              <span class="badge bg-danger">Inactive</span>
            @endif
          </td>
          <td>

            <div class="dropdown">
              <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                Action
              </button>

              <ul class="dropdown-menu">
                <li>
                  <a href="{{route('carousels.show', $carousel->id)}}" class="dropdown-item">View</a>
                </li>
                <li>
                  <a href="{{route("carousels.edit", $carousel->id)}}" class="dropdown-item">Edit</a>
                </li>
                <li>
                  @if($carousel->status == 0)
                    <x-form action="{{route('carousels.make-active', $carousel->id)}}">
                      <button class="dropdown-item">Make Active</button>
                    </x-form>
                  @endif
                </li>
                <li>
                  <button class="dropdown-item delete-item-btn"
                          data-href="{{route('carousels.destroy', $carousel->id)}}">Delete
                  </button>
                </li>
              </ul>


            </div>


          </td>
        </tr>
      @endforeach
      </tbody>
    </table>

  </x-content>
@endsection
