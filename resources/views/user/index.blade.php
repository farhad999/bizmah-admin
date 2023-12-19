@extends('layouts.layoutMaster')

@section('title', 'Users')

@section('content')
  <x-content title="Users">
    <x-slot name="buttons">
      <a href="{{route("users.create")}}" class="btn btn-primary">Add User</a>
    </x-slot>

    <table class="table table-hover" id="datatable">
      <thead>
      <tr>
        <th>Name</th>
        <th>Username</th>
        <th>Email
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
          {data: 'username'},
          {data: 'email'},
          {data: 'status'},
          {data: 'action'}
        ]
      })
    })
  </script>
@endsection
