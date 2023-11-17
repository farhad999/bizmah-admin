@extends('layouts.layoutMaster')
@section('title', 'Create Brand')
@section('content')
  <x-content title="Create Brand">
    <x-form action="{{route('brands.store')}}"
            id="validate_form"
    >
      <div class="row">
        <div class="col-sm-6">
          <x-form.input
            name="name"
            label="Name"
            :required="true"
            data-rules="required"
          />
        </div>
        <div class="col-sm-6">
          <x-form.file-input
            accept="image/jpeg, image/png, image/jpg"
            label="Image"
            :required="true"
            name="image"
            />

          {{--<x-form.input
            name="image"
            label="Image"
            type="file"
            id="file"
            :required="true"
            accept="image/jpeg, image/png, image/jpg"
            data-rules="required"
          />--}}
        </div>

        <div class="col-sm-12">
          <x-form.textarea
            name="description"
            label="Description"
          />
        </div>
        <div class="col-sm-6">
          @include('partials.active-status')
        </div>
        <div class="col-sm-6">
          <x-form.select
            name="visibility"
            label="Visibility (Ecommerce)"
            :required="true"
            options="[1=>Yes, 0=>No]"
            no-placeholder="true"
          />
        </div>
        <div class="col-sm-12 d-flex justify-content-end">
          <button class="btn btn-primary">Submit</button>
        </div>
      </div>
    </x-form>
  </x-content>
@endsection
