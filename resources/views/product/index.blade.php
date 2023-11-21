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

  </script>
@endsection
