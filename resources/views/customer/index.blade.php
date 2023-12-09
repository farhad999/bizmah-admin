@extends('layouts.layoutMaster')

@section('content')
  <x-content title="Customers">
    {{--<x-slot name="buttons">
      <a href="{{route("customers.create")}}" class="btn btn-primary">Add Customer</a>
    </x-slot>--}}

    <table class="table table-hover" id="datatable">
      <thead>
      <tr>
        <th>Name</th>
        <th>Mobile</th>
        <th>Email</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
      </thead>
    </table>
  </x-content>
@endsection

@section('js')
  <script>
    $(document).ready(function () {
      $('#datatable').DataTable({
        ajax: window.location.pathname,
        columns: [
          {data: 'name'},
          {data: 'mobile'},
          {data: 'email'},
          {data: 'status'},
          {data: 'action'}
        ]
      })
    })
  </script>
@endsection
