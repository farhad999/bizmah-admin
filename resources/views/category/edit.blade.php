@php
  $title = "Update Category";
@endphp


@extends('layouts.layoutMaster')
@section('title', $title)
@section('content')
  <x-content :title="$title">
    <x-form action="{{route('categories.update', $category->id)}}"
            id="validate_form"
            method="PUT"
    >
      <div class="row">
        <div class="col-sm-6">
          <x-form.input
            name="name"
            label="Name"
            :required="true"
            data-rules="required"
            value="{{$category->name}}"
          />
        </div>

        <div class="col-sm-6">
          <div class="form-group mb-3">
            <label for="parent_id" class="form-label">Parent Category</label>
            <select class="form-control" name="parent_id">
              <option>--Select One--</option>
              @foreach($categories as $cat)
                <option value="{{$cat->id}}"
                        @if($cat->id == $category->parent_id) selected @endif
                >{{$cat->name}}</option>
                @foreach($cat->children as $item)
                  <option value="{{$item->id}}"
                          @if($item->id == $category->parent_id) selected="selected" @endif
                  >=> {{$item->name}}</option>
                @endforeach
              @endforeach
            </select>
          </div>

        </div>

        <div class="col-sm-6">
          <x-form.file-input
            accept="image/jpeg, image/png, image/jpg"
            label="Image"
            name="image"
            images="{{$category->image_url}}"
          />

        </div>

        <div class="col-sm-6">
          <x-form.file-input
            accept="image/jpeg, image/png, image/jpg"
            label="Banner Image"
            name="image"
            images="{{$category->banner_image_url}}"
          />

        </div>

        <div class="col-sm-12">
          <x-form.textarea
            name="description"
            label="Description"
            value="{{$category->description}}"
          />
        </div>
        <div class="col-sm-6">
          @include('partials.active-status', ['status' => $category->status])
        </div>
        <div class="col-sm-6">
          <x-form.select
            name="visibility"
            label="Visibility (Ecommerce)"
            :required="true"
            options="[1=>Yes, 0=>No]"
            no-placeholder="true"
            value="{{$category->visibility}}"
          />
        </div>
        <div class="col-sm-12 d-flex justify-content-end">
          <button class="btn btn-primary">Submit</button>
        </div>
      </div>
    </x-form>
  </x-content>
@endsection
