@php
  $title = "Product"
@endphp
@section('title', 'Products')

@extends('layouts.layoutMaster')

@section('content')
  <x-content :title="$title">
    <x-slot name="buttons">
      <a href="{{route("products.create")}}" class="btn btn-primary btn-sm me-2"><i class="ti ti-plus"></i>Add New</a>
    </x-slot>

    <div>
      <table class="table table-bordered" id="datatable">
        <thead>
        <tr>
          <th>Action</th>
          <th>Image</th>
          <th>Name</th>
          <th>Sku</th>
          <th>Price</th>
          <th>Category</th>
          <th>Brand</th>
          <th>Visibility</th>
        </tr>
        </thead>
      </table>
    </div>

  </x-content>

  <x-modal.fade id="view_modal"></x-modal.fade>
  <x-modal.fade id="image_gallery"></x-modal.fade>

@endsection

@section('js')
  <script>
    $(document).ready(function () {
      $('#datatable').DataTable({
        ajax: window.location.pathname,
        columns: [
          {data: 'action'},
          {data: 'image'},
          {data: 'name'},
          {data: 'sku'},
          {data: 'price'},
          {data: 'category_name'},
          {data: 'brand_name'},
          {data: 'visibility'}
        ]
      })
    })

    $(document).on('click', '.view-modal-btn', function () {
      console.log("clicked");
      let url = $(this).data('href');
      $('#view_modal').load(url, function () {
        $(this).modal('show');
      });
    })

    $(document).on('click', '.image-gallery-btn', function () {
      let url = $(this).data('href');
      $('#image_gallery').load(url, function () {
        $(this).modal('show');
        loadImages()
      });
    })

    /*$('#image_gallery').on('shown.bs.modal', function (e) {
      loadImages();
    })*/

    $(document).on('submit', 'form#image_gallery_form', function (e) {

      e.preventDefault();
      let data = new FormData(this);

      $.ajax({
        method: 'POST',
        url: $(this).attr('action'),
        dataType: 'json',
        processData: false,
        contentType: false,
        data: data,
        success: function (result) {
          $('#upload_gallery').val("");
          toastr.success('Image Uploaded');
          loadImages();
          //clear file preview
          $('.image-preview-gallery>div').remove();
        },
        error: function (error) {
          console.log(error);
        }
      });

    })

    function loadImages() {
      $.ajax({
        url: '/products/' + $('.product-id').val() + '/load_images',
        type: 'get',
        data: {drop: true},
        success: function (html) {
          $('#image_gallery_container').html(html);
        }
      });
    }

    $(document).on('click', '.remove-btn', function () {

      let galleryItem = $(this).closest('.wrapper-item');

      $.ajax({
        method: 'DELETE',
        url: '/products/' + $('.product-id').val() + '/delete_gallery_image',
        dataType: 'json',
        data: {image_id: $(this).next().val()},
        success: function (res) {
          let {status, message} = res;
          if (status === 'success') {
            toastr.success('Image Removed');
            //remove this gallery item
            galleryItem.remove();

          } else {
            toastr.success('message');
          }
        },
      });

    });

  </script>
@endsection
