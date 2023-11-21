@php
  $title = "Update Product"
@endphp

@extends('layouts.layoutMaster')
@section('title', $title)
@section('vendor-script')
  <script src="{{asset(mix('assets/vendor/libs/quill/quill.js'))}}"></script>
@endsection
@section('vendor-style')
  <link rel="stylesheet" href="{{asset(mix('assets/vendor/libs/quill/editor.css'))}}">
@endsection

@section('content')

  <x-error-alert />

  <x-form action="{{route('products.update', $product->id)}}"
          id="validate_form"
          method="PUT"
  >
    <div class="row">

      <div class="col-12"><h4 class="my-3">Update Product</h4></div>

      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Product Information</h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-sm-12">
                <x-form.input
                  name="name"
                  label="Product Name"
                  :required="true"
                  data-rules="required"
                  placeholder="Product Name"
                  value="{{$product->name}}"
                />
              </div>

              <div class="col-sm-6">
                <x-form.input
                  name="sku"
                  label="SKU"
                  :required="true"
                  data-rules="required"
                  value="{{$product->sku}}"
                />
              </div>

              <div class="col-sm-6">
                <x-form.select
                  name="category_id"
                  id="category_id"
                  label="Category"
                  placeholder="Category"
                  :options="$categories"
                  value="{{$product->category_id}}"
                />
              </div>

              <div class="col-sm-6">
                <x-form.select
                  name="sub_category_id"
                  id="sub_category_id"
                  label="Sub Category"
                  placeholder="Sub Category"
                  :options="$subCategories"
                  value="{{$product->sub_category_id}}"
                />
              </div>

              <div class="col-sm-6">
                <x-form.select
                  name="sub_sub_category_id"
                  id="sub_sub_category_id"
                  label="Sub Sub Category"
                  placeholder="Sub Sub Category"
                  :options="$subSubCategories"
                  value="{{$product->sub_sub_category_id}}"
                />
              </div>

              <div class="col-sm-6">
                <x-form.select
                  name="brand_id"
                  label="Brand"
                  placeholder="Brand"
                  :options="$brands"
                  value="{{$product->brand_id}}"
                />
              </div>
            </div>

            <h5 class="my-2">Product Variant and Pricing</h5>

            <div class="col-sm-6">
              <x-form.select
                name="type"
                label="Product Type"
                placeholder="Product Type"
                :required="true"
                options="[single=>Single, variable=>Variable]"
                value="single"
                noPlaceholder="true"
                value="{{$product->type}}"
                disabled
              />
            </div>

            <div id="variation_container">
              @if($product->type == 'variable')
                @include('product.partials.edit-variations', ['variations' => $product->variations])
              @else
                @include('product.partials.edit-single-product', ['variations' => $product->variations])
              @endif

            </div>

            <div>
              <x-form.textarea
                name="short_description"
                label="Short Description"
                placeholder="Short Description"
                :required="true"
                data-rules="required"
                value="{{$product->short_description}}"
              />
            </div>

            <div>
              <div>Description</div>
              <div id="description_quill">
                {!! $product->description !!}
              </div>
              <input id="description" type="hidden" name="description"
              value="{{$product->description}}"
              ></input>
            </div>

          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card" style="height: 100%">
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <x-form.file-input
                  name="image"
                  label="Product Image"
                  images="{{$product->image_url}}"
                />
              </div>

              {{--<div class="col-12">
                <x-form.file-input
                  name="gallery_images[]"
                  label="Image Gallery"
                  :multiple="true"
                />
              </div>--}}

              <div class="col-12">
                @include('partials.visibility', ['value' => $product->visibility ])
              </div>

            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="card mt-2">
      <div class="card-body d-flex justify-content-center">
        <button class="btn btn-primary">Submit</button>
      </div>
    </div>
  </x-form>
@endsection

@section('js')
  <script>

    $(document).ready(function () {

      let quill = new Quill('#description_quill', {
        theme: 'snow'
      });

      quill.on('text-change', function() {
        // Set the value on blur
        $('#description').val(quill.root.innerHTML);
      });

      $('#type').on("change", function () {
        let type = this.value;
        $.ajax({
          url: window.location.pathname,
          data: {
            type: type
          },
          success: function (html) {
            $('#variation_container').html(html);
            //now initiate select2
            if (type === 'variable') {
              $('#variable_template').select2({
                multiple: true,
                placeholder: 'Select Template'
              });
            }
          }
        })
      })

      //on category change

      $('#category_id').on('change', function () {
        let id = this.value;
        $.ajax({
          url: '/get-sub-categories',
          data: {
            id,
          },
          success: function (html) {
            $('#sub_category_id').html(html);
          }
        })
      })

      $('#sub_category_id').on('change', function () {
        let id = this.value;
        $.ajax({
          url: '/get-sub-categories',
          data: {
            id,
          },
          success: function (html) {
            $('#sub_sub_category_id').html(html);
          }
        })
      })


    })

    $(document).on('change', '#variable_template', function () {
      let template = $(this).val();
      $.ajax({
        data: {
          id: template,
        },
        url: '/get-variation-template',
        success: function (html) {
          $('#variable_template_container').html(html);
          $(".variable-template-values").select2({
            multiple: true,
            tags: true,
            placeholder: "Select Values"
          })
        }
      })
    })

    $(document).on('click', '#create_variation_btn', function () {

      let variation = [];

      $('.variable-template-values').each(function () {
        let value = $(this).val();
        if (value && value.length) {
          variation.push(value.join(','))
        }
      })

      $.ajax({
        url: '/create-variation',
        method: "POST",
        data: {
          variation: variation.join('|'),
        },
        success: function (html) {
          $('#variation_table').html(html);
        }
      })
    })

    $(document).on('click', '.remove-variation-btn', function () {
      let tr = $(this).closest('tr');
      tr.remove();
    })

  </script>
@endsection
