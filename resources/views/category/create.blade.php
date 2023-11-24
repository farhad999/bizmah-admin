@extends('layouts.layoutMaster')
@section('title', 'Create Category')
@section('content')
  <x-content title="Create Category">
    <x-form action="{{route('categories.store')}}"
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

        </div>

        <div class="col-sm-6">
          <div class="form-group mb-3">
            <label for="parent_id" class="form-label">Parent Category</label>
            <select class="form-control" name="parent_id">
              <option value="">--Select One--</option>
              @foreach($categories as $category)
                <option value="{{$category->id}}">{{$category->name}}</option>
                @foreach($category->children as $item)
                  <option value="{{$item->id}}">=> {{$item->name}}</option>
                @endforeach
              @endforeach
            </select>
          </div>

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
