@extends('layouts.layoutMaster')
@section('title', 'Create Brand')
@section('content')
  <x-content title="Update Brand">
    <x-form action="{{route('brands.update', $brand->id)}}"
            method="PUT"
            id="validate_form"
    >
      <div class="row">
        <div class="col-sm-6">
          <x-form.input
            name="name"
            label="Name"
            :required="true"
            data-rules="required"
            value="{{$brand->name}}"
          />
        </div>
        <div class="col-sm-6">
          <x-form.file-input
            accept="image/jpeg, image/png, image/jpg"
            label="Image"
            name="image"
            images="{{$brand->image_url}}"
          />
        </div>

        <div class="col-sm-12">
          <x-form.textarea
            name="description"
            label="Description"
            value="{{$brand->description}}"
          />
        </div>
        <div class="col-sm-6">
          @include('partials.active-status', ['status' => $brand->status])
        </div>
        <div class="col-sm-6">
          <x-form.select
            name="visibility"
            label="Visibility (Ecommerce)"
            :required="true"
            options="[1=>Yes, 0=>No]"
            no-placeholder="true"
            value="{{$brand->visible}}"
          />
        </div>
        <div class="col-sm-12 d-flex justify-content-end">
          <button class="btn btn-primary">Submit</button>
        </div>
      </div>
    </x-form>
  </x-content>
@endsection
