@extends('layouts.layoutMaster')

@section('content')
  <x-content title="Create Carousel">

    <x-slot name="buttons">
      <a href="{{ route('carousels.index') }}" class="btn btn-primary">Back</a>
    </x-slot>

    <form action="{{ route('carousels.store') }}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="row">
        <div class="col-sm-12">
          <x-form.input
            name="name"
            label="Carousel Name"
          />
        </div>

        <div class="col-sm-12">
          <x-form.textarea
            name="description"
            label="Description"
          />
        </div>

        <div class="col-12 d-flex justify-content-end">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>

      </div>
    </form>

  </x-content>
@endsection
