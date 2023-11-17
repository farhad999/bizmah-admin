@extends('layouts.layoutMaster')

@section('content')
  <x-content title="Brands">
    <x-slot name="buttons">
      <a href="{{route('brands.create')}}" class="btn btn-primary"><i class="fa fa-plus me-2"></i>Create</a>
    </x-slot>

    <div>
      <table class="table table-bordered" id="datatable">
        <thead>
        <tr>
          <th>Name</th>
          <th>Image</th>
          <th>Status</th>
          <th>Visibility</th>
          <th>Action</th>
        </tr>
        </thead>
      </table>
    </div>

  </x-content>
@endsection

@section('js')
  <script>
    $(document).ready(function () {
      $('#datatable').DataTable({
        ajax: window.location.pathname,
        columns: [
          {data: 'name'},
          {data: 'image'},
          {data: 'status'},
          {data: 'visibility'},
          {data: 'action'}
        ]
      })
    })
  </script>
@endsection
