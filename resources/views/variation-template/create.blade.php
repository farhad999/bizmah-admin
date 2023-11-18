@php
  $title = "Add Variation Template";
@endphp

@extends('layouts.layoutMaster')
@section('title', $title)
@section('vendor-script')
  <script src="{{asset(mix('/assets/vendor/libs/tagify/tagify.js'))}}"></script>
@endsection
@section('vendor-style')
  <link rel="stylesheet" href="{{asset(mix('/assets/vendor/libs/tagify/tagify.css'))}}">
@endsection

@section('content')
  <x-content :title="$title">

    <x-form action="{{ route('variation-templates.store') }}"
            id="validate_form"
    >
      <div class="row">
        <div class="col-sm-6">
          <x-form.input
            name="name"
            label="Name"
            :required="true"
            data-rules="required|min:3"
          />
        </div>
        <div class="col-sm-6">
          <x-form.input
            name="values"
            label="Values"
            class="tags"
            :required="true"
          />
        </div>

        <div class="col-sm-6">
          @include('partials.active-status')
        </div>

      </div>
      <div class="col-12 d-flex justify-content-end">
        <button class="btn btn-primary" type="=submit">Submit</button>
      </div>
    </x-form>
  </x-content>
@endsection

@section('js')
  <script>
    $(document).ready(function () {

      let tagify = new Tagify(document.getElementsByClassName('tags')[0], {
        originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(',')
      });
    })
  </script>
@endsection
