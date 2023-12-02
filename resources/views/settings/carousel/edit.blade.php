@extends('layouts.layoutMaster')

@section('content')
  <x-content title="Update Carousel">

    <x-slot name="buttons">
      <a href="{{ route('carousels.index') }}" class="btn btn-primary">Back</a>
    </x-slot>

    <x-form action="{{ route('carousels.update', $carousel->id) }}" method="PUT"
    class="validate-form"
    >
      <div class="row">
        <div class="col-sm-12">
          <x-form.input
            name="name"
            label="Carousel Name"
            value="{{$carousel->name}}"
            :required="true"
            data-rules="required"
          />
        </div>

        <div class="col-sm-12">
          <x-form.textarea
            name="description"
            label="Description"
            value="{{$carousel->description}}"
          />
        </div>

        <div class="col-12 d-flex justify-content-end">
          <button type="submit" class="btn btn-primary">Update</button>
        </div>

      </div>
    </x-form>

  </x-content>
@endsection
