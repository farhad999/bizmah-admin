@extends('layouts.layoutMaster')

@section('title', 'Update Page')

@section('vendor-script')
  <script src="{{asset(mix('assets/vendor/libs/quill/quill.js'))}}"></script>
@endsection
@section('vendor-style')
  <link rel="stylesheet" href="{{asset(mix('assets/vendor/libs/quill/editor.css'))}}">
@endsection

@section('content')
  <x-content title="Update Page">
    <x-form action="{{route('pages.update', $page->id)}}"
    id="validate_form"
    >
      <div class="row">
        <div class="col-sm-12">
          <x-form.input
            name="title"
            label="Title"
            type="text"
            :required="true"
            data-rules="required"
            value="{{$page->title}}"
          />
        </div>

        <div class="col-sm-12">
          <x-form.input
            name="slug"
            label="Slug"
            type="text"
            :required="true"
            data-rules="required"
            value="{{$page->slug}}"
          />
        </div>

        <div class="col-12">
          <div>Description</div>
          <div id="description_quill">
            {!! $page->content !!}
          </div>
          <input id="description" type="hidden" name="content"
          value="{{$page->content}}"
          ></input>
        </div>

        <div class="col-sm-12 d-flex justify-content-end mt-2">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>

      </div>
    </x-form>
  </x-content>
@endsection

@section('js')
  <script>
    $(document).ready(function () {
      let quill = new Quill('#description_quill', {
        theme: 'snow'
      });

      quill.on('text-change', function () {
        // Set the value on blur
        $('#description').val(quill.root.innerHTML);
      });
    })

  </script>
@endsection
